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
        <!-- User Basic Info Section -->
        <div class="user-info-card">
            <div class="user-avatar">
                <?php if (!empty($data['photo'])): ?>
                    <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Profile Photo">
                <?php else: ?>
                    <div class="avatar-placeholder">
                        <?= strtoupper(substr($user['username'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="user-details">
                <h2><?= htmlspecialchars($user['username']) ?></h2>
                <p class="user-email"><?= htmlspecialchars($user['email']) ?></p>
                <p class="member-since">Member since <?= date('M Y', strtotime($user['created_at'])) ?></p>
            </div>
        </div>

        <!-- Bio Data Card Section -->
        <div class="bio-data-section">
            <h3>My Bio Data Profile</h3>
            <?php if ($data): ?>
                <div class="bio-preview-card" onclick="window.location.href='bio_form.php'">
                    <div class="bio-preview-header">
                        <div class="bio-preview-photo">
                            <?php if ($data['photo']): ?>
                                <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Bio Photo">
                            <?php else: ?>
                                <div class="photo-placeholder">
                                    <?= strtoupper(substr($data['name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="bio-preview-info">
                            <h4><?= htmlspecialchars($data['name']) ?></h4>
                            <p><?= htmlspecialchars($data['profession'] ?: 'No profession specified') ?></p>
                            <p class="age-gender">
                                <?= date_diff(date_create($data['dob']), date_create('today'))->y ?> years, 
                                <?= htmlspecialchars($data['gender']) ?>
                            </p>
                        </div>
                        <div class="bio-preview-status">
                            <span class="status-badge complete">Complete Profile</span>
                            <p class="click-hint">Click to view/edit details</p>
                        </div>
                    </div>
                    <div class="bio-preview-details">
                        <div class="detail-item">
                            <strong>Location:</strong> <?= htmlspecialchars(substr($data['address'], 0, 50)) ?>...
                        </div>
                        <div class="detail-item">
                            <strong>Education:</strong> <?= htmlspecialchars($data['education'] ?: 'Not specified') ?>
                        </div>
                        <?php if ($data['marital_status']): ?>
                        <div class="detail-item">
                            <strong>Marital Status:</strong> <?= htmlspecialchars($data['marital_status']) ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="no-bio-card" onclick="window.location.href='bio_form.php'">
                    <div class="no-bio-icon">
                        <svg width="48" height="48" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14H7v-2h7v2zm3-4H7v-2h10v2zm0-4H7V7h10v2z"/>
                        </svg>
                    </div>
                    <h4>Create Your Bio Data Profile</h4>
                    <p>Complete your profile to let others know about you</p>
                    <button class="btn-primary">Add Bio Data</button>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php
    // Display success/error messages
    if (isset($_SESSION['success'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = document.createElement('div');
                message.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #e6ffe6; color: #00b894; padding: 1rem; border-radius: 8px; z-index: 9999; box-shadow: 0 5px 15px rgba(0,0,0,0.2);';
                message.textContent = '" . addslashes($_SESSION['success']) . "';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
            });
        </script>";
        unset($_SESSION['success']);
    }
    
    if (isset($_SESSION['error'])) {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function() {
                const message = document.createElement('div');
                message.style.cssText = 'position: fixed; top: 20px; right: 20px; background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; z-index: 9999; box-shadow: 0 5px 15px rgba(0,0,0,0.2);';
                message.textContent = '" . addslashes($_SESSION['error']) . "';
                document.body.appendChild(message);
                setTimeout(() => message.remove(), 5000);
            });
        </script>";
        unset($_SESSION['error']);
    }
    ?>
</body>
</html>
