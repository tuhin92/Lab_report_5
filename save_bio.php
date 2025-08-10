<?php include 'db.php';
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$name = $_POST['name'];
$dob = $_POST['dob'];
$gender = $_POST['gender'];
$email = $_POST['email'];
$phone = $_POST['phone'];
$address = $_POST['address'];
$education = $_POST['education'];
$profession = $_POST['profession'];
$skills = $_POST['skills'];

// Image upload
$photo = "";
if (!empty($_FILES['photo']['name'])) {
    $photo = time() . "_" . $_FILES['photo']['name'];
    move_uploaded_file($_FILES['photo']['tmp_name'], "uploads/" . $photo);
}

$res = $conn->query("SELECT * FROM bio_data WHERE user_id='$user_id'");
if ($res->num_rows > 0) {
    $sql = "UPDATE bio_data SET name='$name', dob='$dob', gender='$gender', email='$email', phone='$phone', address='$address', education='$education', profession='$profession', skills='$skills'";
    if ($photo) { $sql .= ", photo='$photo'"; }
    $sql .= " WHERE user_id='$user_id'";
    $conn->query($sql);
} else {
    $conn->query("INSERT INTO bio_data(user_id,name,dob,gender,email,phone,address,education,profession,skills,photo) VALUES('$user_id','$name','$dob','$gender','$email','$phone','$address','$education','$profession','$skills','$photo')");
}

header("Location: profile.php");
?>
