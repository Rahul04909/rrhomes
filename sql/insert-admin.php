<?php
require_once '../database/config.php';

$sql = "CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    mobile VARCHAR(20) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    profile_image VARCHAR(255) DEFAULT 'user-avtar.png',
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'admins' created or already exists.<br>";
} else {
    die("Error creating table: " . $conn->error);
}

// Insert default admin
$name = "Admin";
$email = "admin@example.com";
$mobile = "1234567890";
$username = "admin";
$password = password_hash("admin123", PASSWORD_DEFAULT);
$profile_image = "user-avtar.png";

$check_sql = "SELECT id FROM admins WHERE username = 'admin'";
$result = $conn->query($check_sql);

if ($result->num_rows == 0) {
    // Insert
    $stmt = $conn->prepare("INSERT INTO admins (name, email, mobile, username, profile_image, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $email, $mobile, $username, $profile_image, $password);
    if ($stmt->execute()) {
        echo "Default admin inserted successfully. Username: admin, Password: admin123<br>";
    } else {
        echo "Error inserting default admin: " . $stmt->error . "<br>";
    }
} else {
    echo "Default admin already exists.<br>";
}

$conn->close();
?>
