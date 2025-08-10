<?php
// Get user information if logged in
$user_data = null;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user_res = $conn->query("SELECT u.username, u.email, b.photo FROM users u LEFT JOIN bio_data b ON u.id = b.user_id WHERE u.id = '$user_id'");
    if ($user_res && $user_res->num_rows > 0) {
        $user_data = $user_res->fetch_assoc();
    }
}
?>

<nav class="navbar">
    <div class="nav-content">
        <div class="nav-left">
            <a href="index.php" class="nav-brand">BioData App</a>
            <a href="index.php" class="nav-home">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
                Home
            </a>
        </div>
        <div class="nav-user">
            <?php if (isset($_SESSION['user_id']) && $user_data): ?>
                <div class="user-icon">
                    <?php if (!empty($user_data['photo'])): ?>
                        <img src="uploads/<?= htmlspecialchars($user_data['photo']) ?>" alt="Profile">
                    <?php else: ?>
                        <?= strtoupper(substr($user_data['username'], 0, 1)) ?>
                    <?php endif; ?>
                </div>
                <div class="dropdown-menu">
                    <a href="profile.php">Profile</a>
                    <a href="logout.php">Logout</a>
                </div>
            <?php else: ?>
                <div class="user-icon">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                </div>
                <div class="dropdown-menu">
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
