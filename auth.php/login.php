
<?php
session_start();
require_once '../config.php';
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt=$db->prepare('SELECT id, full_name, email,password_hash,role
     FROM users WHERE email=:email');

     $stmt->execute(['email'=>$email]);
     $user = $stmt->fetch(PDO::FETCH_ASSOC); //retrieves the matching row as an associative array.

     if(!$user){
        echo "invalid email or password";
     }
     else{
      if (password_verify($password, $user['password_hash'])) {
    session_regenerate_id(true);

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['full_name'] = $user['full_name'];
    $_SESSION['role'] = $user['role'];

   if ($user['role'] === 'client') {
    header('Location: ../client.php');
    exit;
} elseif ($user['role'] === 'lawyer') {
    header('Location: ../lawyer.php');
    exit;
} elseif ($user['role'] === 'admin') {
    header('Location: ../admin.php');
    exit;
}
}
     else{
        echo "invalid email or password";
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
    <h1>Welcome back!</h1>
    <p>Log in to your Legal Consultation Booking System account.</p>
    <form method="POST" action="">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br><br>

        <button type="submit">Login</button>

    </form>
     <p>Don't have an account?</p>
    <a href="register.php">Create an account</a>
</body>
</html>