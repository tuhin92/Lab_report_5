<?php include 'db.php'; 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
$user_id = $_SESSION['user_id'];

// Fetch user info
$user_res = $conn->query("SELECT * FROM users WHERE id='$user_id'");
$user = $user_res->fetch_assoc();

// Fetch existing bio data
$res = $conn->query("SELECT * FROM bio_data WHERE user_id='$user_id'");
$data = $res->fetch_assoc();
?>
<!DOCTYPE html>
<html>
<head>
    <title>My Profile - BioData App</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="profile-container">
        <div class="profile-header">
            <?php if (!empty($data['photo'])): ?>
                <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" class="current-photo" alt="Profile Photo">
            <?php else: ?>
                <div class="current-photo" style="background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 3rem;">
                    <?= strtoupper(substr($user['username'], 0, 1)) ?>
                </div>
            <?php endif; ?>
            <h1>Welcome, <?= htmlspecialchars($user['username']) ?></h1>
            <p><?= $data ? 'Update your bio data below' : 'Complete your bio data profile' ?></p>
        </div>

        <div class="container">
            <?php
            // Display success/error messages
            if (isset($_SESSION['success'])) {
                echo "<div style='background: #e6ffe6; color: #00b894; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>" . htmlspecialchars($_SESSION['success']) . "</div>";
                unset($_SESSION['success']);
            }
            if (isset($_SESSION['error'])) {
                echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>" . htmlspecialchars($_SESSION['error']) . "</div>";
                unset($_SESSION['error']);
            }
            ?>
            
            <h2><?= $data ? 'Update Bio Data' : 'Add Bio Data' ?></h2>
            <form method="post" enctype="multipart/form-data" action="save_bio.php">
                <div class="form-row">
                    <div class="form-group">
                        <label for="name">Full Name *</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" 
                               value="<?= htmlspecialchars($data['name'] ?? '') ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="dob">Date of Birth *</label>
                        <input type="date" id="dob" name="dob" 
                               value="<?= htmlspecialchars($data['dob'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="gender">Gender *</label>
                        <select id="gender" name="gender" required>
                            <option value="">Select Gender</option>
                            <option value="Male" <?= (isset($data['gender']) && $data['gender']=='Male')?'selected':'' ?>>Male</option>
                            <option value="Female" <?= (isset($data['gender']) && $data['gender']=='Female')?'selected':'' ?>>Female</option>
                            <option value="Other" <?= (isset($data['gender']) && $data['gender']=='Other')?'selected':'' ?>>Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="phone">Phone Number *</label>
                        <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                               value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email"
                           value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                </div>

                <div class="form-group">
                    <label for="address">Address *</label>
                    <textarea id="address" name="address" placeholder="Enter your complete address" required><?= htmlspecialchars($data['address'] ?? '') ?></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="education">Education</label>
                        <input type="text" id="education" name="education" placeholder="e.g., Bachelor's in Computer Science"
                               value="<?= htmlspecialchars($data['education'] ?? '') ?>">
                    </div>
                    <div class="form-group">
                        <label for="profession">Profession</label>
                        <input type="text" id="profession" name="profession" placeholder="e.g., Software Engineer"
                               value="<?= htmlspecialchars($data['profession'] ?? '') ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="skills">Skills</label>
                    <input type="text" id="skills" name="skills" placeholder="e.g., PHP, JavaScript, MySQL"
                           value="<?= htmlspecialchars($data['skills'] ?? '') ?>">
                </div>

                <div class="form-group">
                    <label for="photo">Profile Photo</label>
                    <input type="file" id="photo" name="photo" accept="image/*">
                    <?php if (!empty($data['photo'])): ?>
                        <p style="color: #666; font-size: 0.9rem; margin-top: 0.5rem;">
                            Current photo: <?= htmlspecialchars($data['photo']) ?>
                        </p>
                    <?php endif; ?>
                </div>

                <button type="submit" class="btn-primary" style="width: 100%;">
                    <?= $data ? 'Update Bio Data' : 'Save Bio Data' ?>
                </button>
            </form>

            <?php if ($data): ?>
                <div class="profile-actions">
                    <a href="index.php" class="btn-primary">View Dashboard</a>
                    <a href="delete.php?id=<?= $user_id ?>" class="btn-danger" 
                       onclick="return confirm('Are you sure you want to delete your bio data? This action cannot be undone.')">
                       Delete My Bio Data
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
