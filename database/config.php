<?php
$host = 'localhost';
$username = 'mineib_i1_rrhomes';
$password = 'Rd14072003@./';
$database = 'mineib_i1_mineib';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>