<?php 
include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Sanitize and validate input
$name = trim($_POST['name']);
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$address = trim($_POST['address']);
$education = trim($_POST['education'] ?? '');
$profession = trim($_POST['profession'] ?? '');
$skills = trim($_POST['skills'] ?? '');

// Additional matrimonial fields
$height = trim($_POST['height'] ?? '');
$weight = trim($_POST['weight'] ?? '');
$hair_color = trim($_POST['hair_color'] ?? '');
$eye_color = trim($_POST['eye_color'] ?? '');
$marital_status = $_POST['marital_status'] ?? '';
$religion = trim($_POST['religion'] ?? '');
$mother_tongue = trim($_POST['mother_tongue'] ?? '');
$nationality = trim($_POST['nationality'] ?? '');
$body_type = $_POST['body_type'] ?? '';
$smoking = $_POST['smoking'] ?? 'No';
$drinking = $_POST['drinking'] ?? 'No';
$diet = $_POST['diet'] ?? 'Vegetarian';
$hobbies = isset($_POST['hobbies']) && is_array($_POST['hobbies']) ? implode(', ', $_POST['hobbies']) : '';
$interests = isset($_POST['interests']) && is_array($_POST['interests']) ? implode(', ', $_POST['interests']) : '';
$family_type = $_POST['family_type'] ?? '';
$family_status = $_POST['family_status'] ?? '';

// Validation
if (empty($name) || empty($dob) || empty($gender) || empty($email) || empty($phone) || empty($address)) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header("Location: profile.php");
    exit();
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $_SESSION['error'] = "Please enter a valid email address.";
    header("Location: profile.php");
    exit();
}

// Handle photo upload
$photo = "";
$upload_error = false;

if (!empty($_FILES['photo']['name'])) {
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB
    
    if (!in_array($_FILES['photo']['type'], $allowed_types)) {
        $_SESSION['error'] = "Please upload a valid image file (JPG, PNG, GIF only).";
        $upload_error = true;
    } elseif ($_FILES['photo']['size'] > $max_size) {
        $_SESSION['error'] = "File size too large. Maximum 5MB allowed.";
        $upload_error = true;
    } else {
        // Create uploads directory if it doesn't exist
        if (!file_exists("uploads/")) {
            mkdir("uploads/", 0777, true);
        }
        
        $file_extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $photo = time() . "_" . uniqid() . "." . $file_extension;
        
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo)) {
            $_SESSION['error'] = "Failed to upload image. Please try again.";
            $upload_error = true;
        }
    }
}

if ($upload_error) {
    header("Location: profile.php");
    exit();
}

try {
    // Check if bio data already exists
    $check_stmt = $conn->prepare("SELECT photo FROM bio_data WHERE user_id = ?");
    $check_stmt->bind_param("i", $user_id);
    $check_stmt->execute();
    $result = $check_stmt->get_result();
    
    if ($result->num_rows > 0) {
        // Update existing bio data
        if ($photo) {
            // Delete old photo if new photo is uploaded
            $old_data = $result->fetch_assoc();
            if ($old_data['photo'] && file_exists("uploads/" . $old_data['photo'])) {
                unlink("uploads/" . $old_data['photo']);
            }
            
            $stmt = $conn->prepare("UPDATE bio_data SET 
                name=?, dob=?, gender=?, email=?, phone=?, address=?, education=?, profession=?, skills=?, photo=?,
                height=?, weight=?, hair_color=?, eye_color=?, marital_status=?, religion=?, mother_tongue=?, 
                nationality=?, body_type=?, smoking=?, drinking=?, diet=?, hobbies=?, interests=?, 
                family_type=?, family_status=?
                WHERE user_id=?");
            $stmt->bind_param("ssssssssssssssssssssssssssi", 
                $name, $dob, $gender, $email, $phone, $address, $education, $profession, $skills, $photo,
                $height, $weight, $hair_color, $eye_color, $marital_status, $religion, $mother_tongue,
                $nationality, $body_type, $smoking, $drinking, $diet, $hobbies_json, $interests_json,
                $family_type, $family_status, $user_id);
        } else {
            $stmt = $conn->prepare("UPDATE bio_data SET 
                name=?, dob=?, gender=?, email=?, phone=?, address=?, education=?, profession=?, skills=?,
                height=?, weight=?, hair_color=?, eye_color=?, marital_status=?, religion=?, mother_tongue=?, 
                nationality=?, body_type=?, smoking=?, drinking=?, diet=?, hobbies=?, interests=?, 
                family_type=?, family_status=?
                WHERE user_id=?");
            $stmt->bind_param("sssssssssssssssssssssssssi", 
                $name, $dob, $gender, $email, $phone, $address, $education, $profession, $skills,
                $height, $weight, $hair_color, $eye_color, $marital_status, $religion, $mother_tongue,
                $nationality, $body_type, $smoking, $drinking, $diet, $hobbies_json, $interests_json,
                $family_type, $family_status, $user_id);
        }
        
        $_SESSION['success'] = "Bio data updated successfully!";
    } else {
        // Insert new bio data
        $stmt = $conn->prepare("INSERT INTO bio_data (
            user_id, name, dob, gender, email, phone, address, education, profession, skills, photo,
            height, weight, hair_color, eye_color, marital_status, religion, mother_tongue, 
            nationality, body_type, smoking, drinking, diet, hobbies, interests, 
            family_type, family_status
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssssssssssssssssssssss", 
            $user_id, $name, $dob, $gender, $email, $phone, $address, $education, $profession, $skills, $photo,
            $height, $weight, $hair_color, $eye_color, $marital_status, $religion, $mother_tongue,
            $nationality, $body_type, $smoking, $drinking, $diet, $hobbies_json, $interests_json,
            $family_type, $family_status);
        
        $_SESSION['success'] = "Bio data saved successfully!";
    }
    
    if (!$stmt->execute()) {
        throw new Exception("Database error occurred.");
    }
    
    $stmt->close();
    $check_stmt->close();
    
} catch (Exception $e) {
    $_SESSION['error'] = "An error occurred while saving bio data. Please try again.";
    
    // Delete uploaded photo if database operation failed
    if ($photo && file_exists("uploads/" . $photo)) {
        unlink("uploads/" . $photo);
    }
}

header("Location: profile.php");
exit();
?>
