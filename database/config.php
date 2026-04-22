<?php
$host = 'localhost';
$username = 'sbsmarti_rrhomes';
$password = 'Rd14072003@./';
$database = 'sbsmarti_rrhomes';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>