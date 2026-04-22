<?php
require_once '../database/config.php';

$sql = "CREATE TABLE IF NOT EXISTS enquiries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    message TEXT,
    source VARCHAR(255) DEFAULT 'Contact Page',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Table 'enquiries' created successfully.";
} else {
    echo "Error creating table: " . mysqli_error($conn);
}
?>
