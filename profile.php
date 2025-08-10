<?php include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch existing bio data
$res = $conn->query("SELECT * FROM bio_data WHERE user_id='$user_id'");
$data = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>My Profile</h1>
    <form method="post" enctype="multipart/form-data" action="save_bio.php">
        <input type="text" name="name" placeholder="Full Name" value="<?= $data['name'] ?? '' ?>" required><br><br>
        <input type="date" name="dob" value="<?= $data['dob'] ?? '' ?>" required><br><br>
        <select name="gender" required>
            <option value="">Select Gender</option>
            <option value="Male" <?= (isset($data['gender']) && $data['gender']=='Male')?'selected':'' ?>>Male</option>
            <option value="Female" <?= (isset($data['gender']) && $data['gender']=='Female')?'selected':'' ?>>Female</option>
        </select><br><br>
        <input type="email" name="email" value="<?= $data['email'] ?? '' ?>" required><br><br>
        <input type="text" name="phone" value="<?= $data['phone'] ?? '' ?>" required><br><br>
        <textarea name="address" required><?= $data['address'] ?? '' ?></textarea><br><br>
        <input type="text" name="education" placeholder="Education" value="<?= $data['education'] ?? '' ?>"><br><br>
        <input type="text" name="profession" placeholder="Profession" value="<?= $data['profession'] ?? '' ?>"><br><br>
        <input type="text" name="skills" placeholder="Skills" value="<?= $data['skills'] ?? '' ?>"><br><br>
        <input type="file" name="photo"><br><br>
        <?php if (!empty($data['photo'])) { ?>
            <img src="uploads/<?= $data['photo'] ?>" width="100">
        <?php } ?>
        <button type="submit">Save</button>
    </form>
    <br>
    <a href="delete.php?id=<?= $user_id ?>">Delete Profile</a>
    <br><br>
    <a href="logout.php">Logout</a>
</div>
</body>
</html>
