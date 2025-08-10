<?php
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['error'] = "Invalid request.";
    header("Location: profile.php");
    exit();
}

$user_id = (int)$_GET['id'];
$current_user_id = $_SESSION['user_id'];

// Security check: Only allow users to delete their own data
if ($user_id !== $current_user_id) {
    $_SESSION['error'] = "You can only delete your own bio data.";
    header("Location: profile.php");
    exit();
}

try {
    // Get photo filename before deleting record
    $stmt = $conn->prepare("SELECT photo FROM bio_data WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        
        // Delete the bio data record
        $delete_stmt = $conn->prepare("DELETE FROM bio_data WHERE user_id = ?");
        $delete_stmt->bind_param("i", $user_id);
        
        if ($delete_stmt->execute()) {
            // Delete photo file if it exists
            if ($data['photo'] && file_exists("uploads/" . $data['photo'])) {
                unlink("uploads/" . $data['photo']);
            }
            
            $_SESSION['success'] = "Your bio data has been deleted successfully.";
        } else {
            $_SESSION['error'] = "Failed to delete bio data. Please try again.";
        }
        
        $delete_stmt->close();
    } else {
        $_SESSION['error'] = "No bio data found to delete.";
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred while deleting bio data.";
}

header("Location: profile.php");
exit();
?>
