<?php
include 'db.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div style='text-align: center; padding: 2rem; color: #ff4b2b;'>Invalid bio ID</div>";
    exit;
}

$bio_id = (int)$_GET['id'];
$res = $conn->query("SELECT * FROM bio_data WHERE id = $bio_id");

if (!$res || $res->num_rows === 0) {
    echo "<div style='text-align: center; padding: 2rem; color: #ff4b2b;'>Bio data not found</div>";
    exit;
}

$bio = $res->fetch_assoc();
?>

<div style="text-align: center; margin-bottom: 2rem;">
    <?php if ($bio['photo']): ?>
        <img src="uploads/<?= htmlspecialchars($bio['photo']) ?>" 
             style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover; border: 5px solid #667eea; margin-bottom: 1rem;" 
             alt="Profile Photo">
    <?php else: ?>
        <div style="width: 150px; height: 150px; border-radius: 50%; background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 3rem; margin: 0 auto 1rem; border: 5px solid #667eea;">
            <?= strtoupper(substr($bio['name'], 0, 1)) ?>
        </div>
    <?php endif; ?>
    <h2 style="margin: 0; color: #333;"><?= htmlspecialchars($bio['name']) ?></h2>
    <?php if ($bio['profession']): ?>
        <p style="color: #667eea; font-weight: 600; margin: 0.5rem 0 0 0;"><?= htmlspecialchars($bio['profession']) ?></p>
    <?php endif; ?>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
    <?php if ($bio['dob']): ?>
        <div>
            <strong style="color: #333;">Date of Birth:</strong><br>
            <span style="color: #666;"><?= date('F j, Y', strtotime($bio['dob'])) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if ($bio['gender']): ?>
        <div>
            <strong style="color: #333;">Gender:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['gender']) ?></span>
        </div>
    <?php endif; ?>
    
    <div>
        <strong style="color: #333;">Email:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars($bio['email']) ?></span>
    </div>
    
    <div>
        <strong style="color: #333;">Phone:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars($bio['phone']) ?></span>
    </div>
    
    <?php if ($bio['education']): ?>
        <div>
            <strong style="color: #333;">Education:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['education']) ?></span>
        </div>
    <?php endif; ?>
    
    <?php if ($bio['skills']): ?>
        <div>
            <strong style="color: #333;">Skills:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['skills']) ?></span>
        </div>
    <?php endif; ?>
</div>

<?php if ($bio['address']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Address:</strong><br>
        <span style="color: #666;"><?= nl2br(htmlspecialchars($bio['address'])) ?></span>
    </div>
<?php endif; ?>

<?php
// Check if current user owns this bio data
$is_owner = false;
if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $bio['user_id']) {
    $is_owner = true;
}
?>

<?php if ($is_owner): ?>
    <div style="text-align: center; margin-top: 2rem; padding-top: 1rem; border-top: 1px solid #eee;">
        <p style="color: #667eea; font-weight: 600; margin-bottom: 1rem;">This is your bio data</p>
        <a href="profile.php" class="btn-primary" style="margin-right: 0.5rem;">Edit Profile</a>
        <a href="delete.php?id=<?= $_SESSION['user_id'] ?>" class="btn-danger" 
           onclick="return confirm('Are you sure you want to delete your bio data? This action cannot be undone.')">Delete</a>
    </div>
<?php endif; ?>
