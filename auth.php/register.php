<?php

require_once '../config.php';

if($_SERVER['REQUEST_METHOD'] == 'POST'){

 
$full_name=$_POST['fullName'];
$email=$_POST['email'];
$password=$_POST['password'];
$phone=$_POST['phone'];
$confirm_password=$_POST['confirm_password'];
$role=$_POST['role'];

if ($password !== $confirm_password) {
    echo "Passwords don't match";
} else {
    $stmt = $db->prepare(
        "SELECT id FROM users WHERE email = :email"
    );

    $stmt->execute(['email' => $email]);

    if ($stmt->fetch()) { //checks whether the email query returned a row.
        echo "This email is already registered.";
    } else {
       $password_hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $db->prepare("
        INSERT INTO users (full_name, email, password_hash, phone, role)
        VALUES (:full_name, :email, :password_hash, :phone, :role)
        ");

        $stmt->execute([
    'full_name' => $full_name,
    'email' => $email,
    'password_hash' => $password_hash,
    'phone' => $phone,
    'role' => $role
     ]);

     echo "Registration successful!";
    }
}
}
?>






<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Create an Account</h1>
    <p>Register to book legal consultations or offer legal services.</p>
    <form method="POST" action="">
        <label for="fullName">Full Name:</label>
        <input type="text" name="fullName" id="fullName" required><br><br>

        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required><br><br>

        <label for="phone">Phone Number:</label>
        <input type="tel" name="phone" id="phone" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required><br><br>

        <label for="confirm_password">Confirm your password:</label>
        <input type="password" name="confirm_password" id="confirm_password" required><br><br>

        <label for="role">Register As:</label>
        <select id="role" name="role" required>
            <option value="">Choose Your role</option>
            <option value="client">Client</option>
            <option value="lawyer">Lawyer</option>
        </select>

        <button type="submit">Register</button>
    </form>

    <p>Already have an account?</p>
    <a href="login.php">Login here</a>
</body>
</html>