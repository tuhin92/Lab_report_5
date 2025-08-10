<?php
include 'db.php';

// Destroy all session data
session_unset();
session_destroy();

// Set success message for next page load
session_start();
$_SESSION['success'] = "You have been logged out successfully.";

header("Location: index.php");
exit();
?>
