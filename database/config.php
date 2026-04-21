<?php
$host = 'localhost';
$username = 'jhdindus_rrhomes';
$password = 'Rd14072003@./';
$database = 'jhdindus_rrhomes';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>