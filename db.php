<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bio_data_app";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}
session_start();
?>
