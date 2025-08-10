<?php
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$id = $_GET['id'];
$conn->query("DELETE FROM bio_data WHERE user_id='$id'");
header("Location: index.php");
?>
