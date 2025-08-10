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
                <div class="bio-preview-card" onclick="openBioForm()">
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
                <div class="no-bio-card" onclick="openBioForm()">
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

    <!-- Bio Data Form Modal -->
    <div id="bioFormModal" class="modal">
        <div class="modal-content large-modal">
            <button class="modal-close" onclick="closeBioForm()">&times;</button>
            <div class="modal-header">
                <h2><?= $data ? 'Update Your Bio Data' : 'Create Your Bio Data Profile' ?></h2>
                <p>Please fill in the details below to complete your profile</p>
            </div>
            
            <form method="post" enctype="multipart/form-data" action="save_bio.php" class="bio-form">
                <!-- Basic Information -->
                <div class="form-section">
                    <h3>Basic Information</h3>
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
                            <label for="marital_status">Marital Status</label>
                            <select id="marital_status" name="marital_status">
                                <option value="">Select Status</option>
                                <option value="Single" <?= (isset($data['marital_status']) && $data['marital_status']=='Single')?'selected':'' ?>>Single</option>
                                <option value="Married" <?= (isset($data['marital_status']) && $data['marital_status']=='Married')?'selected':'' ?>>Married</option>
                                <option value="Divorced" <?= (isset($data['marital_status']) && $data['marital_status']=='Divorced')?'selected':'' ?>>Divorced</option>
                                <option value="Widowed" <?= (isset($data['marital_status']) && $data['marital_status']=='Widowed')?'selected':'' ?>>Widowed</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="email">Email Address *</label>
                            <input type="email" id="email" name="email" placeholder="Enter your email"
                                   value="<?= htmlspecialchars($data['email'] ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Phone Number *</label>
                            <input type="tel" id="phone" name="phone" placeholder="Enter your phone number"
                                   value="<?= htmlspecialchars($data['phone'] ?? '') ?>" required>
                        </div>
                    </div>
                </div>

                <!-- Physical Attributes -->
                <div class="form-section">
                    <h3>Physical Attributes</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="height">Height</label>
                            <input type="text" id="height" name="height" placeholder="e.g., 5'6&quot; or 168 cm"
                                   value="<?= htmlspecialchars($data['height'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="weight">Weight</label>
                            <input type="text" id="weight" name="weight" placeholder="e.g., 65 kg or 143 lbs"
                                   value="<?= htmlspecialchars($data['weight'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="hair_color">Hair Color</label>
                            <input type="text" id="hair_color" name="hair_color" placeholder="e.g., Black, Brown, Blonde"
                                   value="<?= htmlspecialchars($data['hair_color'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="eye_color">Eye Color</label>
                            <input type="text" id="eye_color" name="eye_color" placeholder="e.g., Brown, Black, Blue"
                                   value="<?= htmlspecialchars($data['eye_color'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="body_type">Body Type</label>
                            <select id="body_type" name="body_type">
                                <option value="">Select Body Type</option>
                                <option value="Slim" <?= (isset($data['body_type']) && $data['body_type']=='Slim')?'selected':'' ?>>Slim</option>
                                <option value="Average" <?= (isset($data['body_type']) && $data['body_type']=='Average')?'selected':'' ?>>Average</option>
                                <option value="Athletic" <?= (isset($data['body_type']) && $data['body_type']=='Athletic')?'selected':'' ?>>Athletic</option>
                                <option value="Heavy" <?= (isset($data['body_type']) && $data['body_type']=='Heavy')?'selected':'' ?>>Heavy</option>
                                <option value="Other" <?= (isset($data['body_type']) && $data['body_type']=='Other')?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="complexion">Complexion</label>
                            <select id="complexion" name="complexion">
                                <option value="">Select Complexion</option>
                                <option value="Fair" <?= (isset($data['complexion']) && $data['complexion']=='Fair')?'selected':'' ?>>Fair</option>
                                <option value="Wheatish" <?= (isset($data['complexion']) && $data['complexion']=='Wheatish')?'selected':'' ?>>Wheatish</option>
                                <option value="Dark" <?= (isset($data['complexion']) && $data['complexion']=='Dark')?'selected':'' ?>>Dark</option>
                                <option value="Other" <?= (isset($data['complexion']) && $data['complexion']=='Other')?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Location & Background -->
                <div class="form-section">
                    <h3>Location & Background</h3>
                    <div class="form-group">
                        <label for="address">Address *</label>
                        <textarea id="address" name="address" placeholder="Enter your complete address" required><?= htmlspecialchars($data['address'] ?? '') ?></textarea>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="religion">Religion</label>
                            <input type="text" id="religion" name="religion" placeholder="e.g., Hindu, Muslim, Christian"
                                   value="<?= htmlspecialchars($data['religion'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="caste">Caste/Community</label>
                            <input type="text" id="caste" name="caste" placeholder="Enter your caste or community"
                                   value="<?= htmlspecialchars($data['caste'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="mother_tongue">Mother Tongue</label>
                            <input type="text" id="mother_tongue" name="mother_tongue" placeholder="e.g., Hindi, English, Tamil"
                                   value="<?= htmlspecialchars($data['mother_tongue'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="nationality">Nationality</label>
                            <input type="text" id="nationality" name="nationality" placeholder="e.g., Indian, American"
                                   value="<?= htmlspecialchars($data['nationality'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Professional & Educational -->
                <div class="form-section">
                    <h3>Professional & Educational Details</h3>
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
                </div>

                <!-- Lifestyle & Habits -->
                <div class="form-section">
                    <h3>Lifestyle & Habits</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label>Smoking Habit</label>
                            <div class="radio-group">
                                <label><input type="radio" name="smoking" value="Yes" <?= (isset($data['smoking']) && $data['smoking']=='Yes')?'checked':'' ?>> Yes</label>
                                <label><input type="radio" name="smoking" value="No" <?= (isset($data['smoking']) && $data['smoking']=='No')?'checked':'' ?>> No</label>
                                <label><input type="radio" name="smoking" value="Occasionally" <?= (isset($data['smoking']) && $data['smoking']=='Occasionally')?'checked':'' ?>> Occasionally</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Drinking Habit</label>
                            <div class="radio-group">
                                <label><input type="radio" name="drinking" value="Yes" <?= (isset($data['drinking']) && $data['drinking']=='Yes')?'checked':'' ?>> Yes</label>
                                <label><input type="radio" name="drinking" value="No" <?= (isset($data['drinking']) && $data['drinking']=='No')?'checked':'' ?>> No</label>
                                <label><input type="radio" name="drinking" value="Occasionally" <?= (isset($data['drinking']) && $data['drinking']=='Occasionally')?'checked':'' ?>> Occasionally</label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Dietary Preferences</label>
                        <div class="checkbox-group">
                            <label><input type="radio" name="diet" value="Vegetarian" <?= (isset($data['diet']) && $data['diet']=='Vegetarian')?'checked':'' ?>> Vegetarian</label>
                            <label><input type="radio" name="diet" value="Non-Vegetarian" <?= (isset($data['diet']) && $data['diet']=='Non-Vegetarian')?'checked':'' ?>> Non-Vegetarian</label>
                            <label><input type="radio" name="diet" value="Vegan" <?= (isset($data['diet']) && $data['diet']=='Vegan')?'checked':'' ?>> Vegan</label>
                            <label><input type="radio" name="diet" value="Jain" <?= (isset($data['diet']) && $data['diet']=='Jain')?'checked':'' ?>> Jain</label>
                        </div>
                    </div>
                </div>

                <!-- Family Information -->
                <div class="form-section">
                    <h3>Family Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="family_type">Family Type</label>
                            <select id="family_type" name="family_type">
                                <option value="">Select Family Type</option>
                                <option value="Joint Family" <?= (isset($data['family_type']) && $data['family_type']=='Joint Family')?'selected':'' ?>>Joint Family</option>
                                <option value="Nuclear Family" <?= (isset($data['family_type']) && $data['family_type']=='Nuclear Family')?'selected':'' ?>>Nuclear Family</option>
                                <option value="Other" <?= (isset($data['family_type']) && $data['family_type']=='Other')?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="family_status">Family Status</label>
                            <select id="family_status" name="family_status">
                                <option value="">Select Family Status</option>
                                <option value="Middle Class" <?= (isset($data['family_status']) && $data['family_status']=='Middle Class')?'selected':'' ?>>Middle Class</option>
                                <option value="Upper Middle Class" <?= (isset($data['family_status']) && $data['family_status']=='Upper Middle Class')?'selected':'' ?>>Upper Middle Class</option>
                                <option value="Rich" <?= (isset($data['family_status']) && $data['family_status']=='Rich')?'selected':'' ?>>Rich</option>
                                <option value="Other" <?= (isset($data['family_status']) && $data['family_status']=='Other')?'selected':'' ?>>Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="about_family">About Family</label>
                        <textarea id="about_family" name="about_family" placeholder="Tell us about your family"><?= htmlspecialchars($data['about_family'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="form-section">
                    <h3>Personal Information</h3>
                    <div class="form-group">
                        <label for="hobbies">Hobbies</label>
                        <input type="text" id="hobbies" name="hobbies" placeholder="e.g., Reading, Traveling, Music"
                               value="<?= htmlspecialchars($data['hobbies'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="interests">Interests</label>
                        <input type="text" id="interests" name="interests" placeholder="e.g., Technology, Sports, Arts"
                               value="<?= htmlspecialchars($data['interests'] ?? '') ?>">
                    </div>

                    <div class="form-group">
                        <label for="partner_preferences">Partner Preferences</label>
                        <textarea id="partner_preferences" name="partner_preferences" placeholder="Describe what you're looking for in a partner"><?= htmlspecialchars($data['partner_preferences'] ?? '') ?></textarea>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="form-section">
                    <h3>Profile Photo</h3>
                    <div class="form-group">
                        <label for="photo">Upload Photo</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <?php if (!empty($data['photo'])): ?>
                            <div class="current-photo-preview">
                                <img src="uploads/<?= htmlspecialchars($data['photo']) ?>" alt="Current Photo">
                                <p>Current photo: <?= htmlspecialchars($data['photo']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-primary">
                        <?= $data ? 'Update Bio Data' : 'Save Bio Data' ?>
                    </button>
                    <?php if ($data): ?>
                        <a href="delete.php?id=<?= $user_id ?>" class="btn-danger" 
                           onclick="return confirm('Are you sure you want to delete your bio data? This action cannot be undone.')">
                           Delete Bio Data
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openBioForm() {
            document.getElementById('bioFormModal').classList.add('active');
        }
        
        function closeBioForm() {
            document.getElementById('bioFormModal').classList.remove('active');
        }
        
        // Close modal when clicking outside
        document.getElementById('bioFormModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBioForm();
            }
        });
    </script>

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
