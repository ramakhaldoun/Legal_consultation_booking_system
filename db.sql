
CREATE DATABASE IF NOT EXISTS legal_consultation;
USE legal_consultation;

CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    full_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('client', 'lawyer', 'admin') NOT NULL,
    phone VARCHAR(255) NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active'
);

CREATE TABLE IF NOT EXISTS specializations (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS lawyer_profiles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL UNIQUE,
    specialization_id INT NOT NULL,
    bio TEXT NOT NULL,
    years_experience INT NOT NULL,
    consultation_fee DECIMAL(10, 2) NOT NULL DEFAULT 0.00,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id)
        REFERENCES users(id) ON DELETE CASCADE,

    FOREIGN KEY (specialization_id)
        REFERENCES specializations(id)
);

CREATE TABLE IF NOT EXISTS appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lawyer_id INT NOT NULL,
    app_date DATE NOT NULL,
    start_time TIME NOT NULL,
    end_time TIME NOT NULL,
    status ENUM('available', 'booked', 'unavailable')
        NOT NULL DEFAULT 'available',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (lawyer_id)
        REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS bookings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT NOT NULL,
    app_id INT NOT NULL,
    status ENUM('pending', 'confirmed', 'rejected', 'cancelled')
        NOT NULL DEFAULT 'pending',
    notes TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (app_id) REFERENCES appointments(id)
);

CREATE TABLE IF NOT EXISTS lawyer_profile_changes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    lawyer_id INT NOT NULL,
    specialization_id INT NULL,
    bio TEXT NULL,
    years_experience INT NULL,
    consultation_fee DECIMAL(10, 2) NULL,
    status ENUM('pending', 'approved', 'rejected')
        NOT NULL DEFAULT 'pending',
    reviewed_at TIMESTAMP NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (lawyer_id) REFERENCES users(id),
    FOREIGN KEY (specialization_id) REFERENCES specializations(id)
);

CREATE TABLE IF NOT EXISTS admin_actions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT NOT NULL,
    action_type VARCHAR(100) NOT NULL,
    description TEXT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (admin_id) REFERENCES users(id)
);
