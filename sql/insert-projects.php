<?php
require_once '../database/config.php';

$sql = "CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    short_description TEXT,
    description LONGTEXT,
    specifications LONGTEXT,
    hero_slides TEXT, -- JSON array of image paths
    images TEXT, -- JSON array of image paths
    seo_meta_title VARCHAR(255),
    seo_meta_description TEXT,
    seo_meta_keywords VARCHAR(255),
    seo_schema TEXT,
    og_information TEXT,
    seo_featured_image VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)";

if ($conn->query($sql) === TRUE) {
    echo "Table 'projects' created successfully or already exists.<br>";
} else {
    die("Error creating table projects: " . $conn->error);
}

$conn->close();
?>
