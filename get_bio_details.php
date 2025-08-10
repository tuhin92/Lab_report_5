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

// Calculate age
$age = '';
if ($bio['dob']) {
    $age = date_diff(date_create($bio['dob']), date_create('today'))->y . ' years';
}

// Function to display array items as comma-separated values
function displayArrayAsString($jsonString) {
    if (empty($jsonString)) return 'Not specified';
    $array = json_decode($jsonString, true);
    if (is_array($array) && !empty($array)) {
        return implode(', ', $array);
    }
    return $jsonString; // fallback for non-JSON data
}
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
    <?php if ($age): ?>
        <p style="color: #666; margin: 0.25rem 0 0 0;"><?= $age ?> • <?= htmlspecialchars($bio['gender']) ?></p>
    <?php endif; ?>
</div>

<!-- Basic Information -->
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Basic Information</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <div>
            <strong style="color: #333;">Email:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['email']) ?></span>
        </div>
        <div>
            <strong style="color: #333;">Phone:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['phone']) ?></span>
        </div>
        <?php if ($bio['dob']): ?>
        <div>
            <strong style="color: #333;">Date of Birth:</strong><br>
            <span style="color: #666;"><?= date('F j, Y', strtotime($bio['dob'])) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['marital_status']): ?>
        <div>
            <strong style="color: #333;">Marital Status:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['marital_status']) ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Physical Attributes -->
<?php if ($bio['height'] || $bio['weight'] || $bio['hair_color'] || $bio['eye_color'] || $bio['body_type']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Physical Attributes</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <?php if ($bio['height']): ?>
        <div>
            <strong style="color: #333;">Height:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['height']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['weight']): ?>
        <div>
            <strong style="color: #333;">Weight:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['weight']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['hair_color']): ?>
        <div>
            <strong style="color: #333;">Hair Color:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['hair_color']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['eye_color']): ?>
        <div>
            <strong style="color: #333;">Eye Color:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['eye_color']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['body_type']): ?>
        <div>
            <strong style="color: #333;">Body Type:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['body_type']) ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Background Information -->
<?php if ($bio['religion'] || $bio['mother_tongue'] || $bio['nationality']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Background</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <?php if ($bio['religion']): ?>
        <div>
            <strong style="color: #333;">Religion:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['religion']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['mother_tongue']): ?>
        <div>
            <strong style="color: #333;">Mother Tongue:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['mother_tongue']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['nationality']): ?>
        <div>
            <strong style="color: #333;">Nationality:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['nationality']) ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Professional Information -->
<?php if ($bio['education'] || $bio['profession'] || $bio['skills']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Professional Details</h3>
    <?php if ($bio['education']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Education:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars($bio['education']) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($bio['profession']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Profession:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars($bio['profession']) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($bio['skills']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Skills:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars($bio['skills']) ?></span>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Lifestyle -->
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Lifestyle</h3>
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
        <div>
            <strong style="color: #333;">Smoking:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['smoking'] ?: 'Not specified') ?></span>
        </div>
        <div>
            <strong style="color: #333;">Drinking:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['drinking'] ?: 'Not specified') ?></span>
        </div>
        <div>
            <strong style="color: #333;">Diet:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['diet'] ?: 'Not specified') ?></span>
        </div>
    </div>
</div>

<!-- Personal Interests -->
<?php if ($bio['hobbies'] || $bio['interests']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Personal Interests</h3>
    <?php if ($bio['hobbies']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Hobbies:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars(displayArrayAsString($bio['hobbies'])) ?></span>
    </div>
    <?php endif; ?>
    <?php if ($bio['interests']): ?>
    <div style="margin-bottom: 1rem;">
        <strong style="color: #333;">Interests:</strong><br>
        <span style="color: #666;"><?= htmlspecialchars(displayArrayAsString($bio['interests'])) ?></span>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Family Information -->
<?php if ($bio['family_type'] || $bio['family_status']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Family Information</h3>
    <?php if ($bio['family_type'] || $bio['family_status']): ?>
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
        <?php if ($bio['family_type']): ?>
        <div>
            <strong style="color: #333;">Family Type:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['family_type']) ?></span>
        </div>
        <?php endif; ?>
        <?php if ($bio['family_status']): ?>
        <div>
            <strong style="color: #333;">Family Status:</strong><br>
            <span style="color: #666;"><?= htmlspecialchars($bio['family_status']) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>

<!-- Address -->
<?php if ($bio['address']): ?>
<div style="margin-bottom: 2rem;">
    <h3 style="color: #667eea; border-bottom: 2px solid #667eea; padding-bottom: 0.5rem; margin-bottom: 1rem;">Location</h3>
    <div>
        <strong style="color: #333;">Address:</strong><br>
        <span style="color: #666;"><?= nl2br(htmlspecialchars($bio['address'])) ?></span>
    </div>
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
        <button onclick="parent.openBioForm(); parent.closeBioModal();" class="btn-primary" style="margin-right: 0.5rem; display: inline-block; background: linear-gradient(135deg, #667eea, #764ba2); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; text-decoration: none;">Edit Profile</button>
        <a href="delete.php?id=<?= $_SESSION['user_id'] ?>" style="background: linear-gradient(135deg, #ff416c, #ff4b2b); color: white; padding: 0.75rem 1.5rem; border: none; border-radius: 8px; cursor: pointer; text-decoration: none; display: inline-block;" 
           onclick="return confirm('Are you sure you want to delete your bio data? This action cannot be undone.')">Delete</a>
    </div>
<?php endif; ?>
