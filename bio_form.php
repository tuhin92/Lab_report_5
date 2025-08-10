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
    <title><?= $data ? 'Update Bio Data' : 'Create Bio Data' ?> - BioData App</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        .bio-form-page {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem 0;
        }
        
        .form-header {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 2rem;
            border-radius: 20px 20px 0 0;
            text-align: center;
            border-bottom: 3px solid #667eea;
        }
        
        .form-container {
            max-width: 1000px;
            margin: 0 auto;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        
        .form-content {
            padding: 2rem;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .form-actions-fixed {
            position: sticky;
            bottom: 0;
            background: white;
            padding: 2rem;
            border-top: 2px solid #f1f3f4;
            display: flex;
            gap: 1rem;
            justify-content: center;
            box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.1);
        }
        
        .back-button {
            position: fixed;
            top: 2rem;
            left: 2rem;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            color: #667eea;
            border: 2px solid rgba(102, 126, 234, 0.3);
            padding: 0.75rem 1.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            z-index: 1000;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .back-button:hover {
            background: white;
            color: #764ba2;
            border-color: #764ba2;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.3);
        }
        
        .progress-bar {
            height: 4px;
            background: #e9ecef;
            border-radius: 2px;
            margin: 1rem 0;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            width: 0%;
            transition: width 0.3s ease;
        }
        
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .checkbox-group label {
            display: flex;
            align-items: center;
            padding: 0.5rem 0.75rem;
            background: #f8f9ff;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }
        
        .checkbox-group label:hover {
            background: #e9f0ff;
            border-color: #667eea;
        }
        
        .checkbox-group input[type="checkbox"] {
            margin-right: 0.5rem;
            width: 16px;
            height: 16px;
            accent-color: #667eea;
        }
        
        .checkbox-group label:has(input:checked) {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border-color: #667eea;
        }
    </style>
</head>
<body class="bio-form-page">
    <a href="profile.php" class="back-button">
        <svg width="20" height="20" fill="currentColor" viewBox="0 0 24 24">
            <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z"/>
        </svg>
        Back to Profile
    </a>

    <div class="form-container">
        <div class="form-header">
            <h1><?= $data ? 'Update Your Bio Data' : 'Create Your Bio Data Profile' ?></h1>
            <p>Complete your profile to connect with others and showcase yourself</p>
            <div class="progress-bar">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <?php
        // Display success/error messages
        if (isset($_SESSION['success'])) {
            echo "<div style='background: #e6ffe6; color: #00b894; padding: 1rem; margin: 1rem 2rem 0; border-radius: 8px; text-align: center; border-left: 4px solid #00b894;'>" . htmlspecialchars($_SESSION['success']) . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; margin: 1rem 2rem 0; border-radius: 8px; text-align: center; border-left: 4px solid #d63031;'>" . htmlspecialchars($_SESSION['error']) . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <form method="post" enctype="multipart/form-data" action="save_bio.php" class="bio-form" id="bioDataForm">
            <div class="form-content">
                <!-- Basic Information -->
                <div class="form-section" data-section="1">
                    <h3>📝 Basic Information</h3>
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
                <div class="form-section" data-section="2">
                    <h3>🏃‍♀️ Physical Attributes</h3>
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
                    </div>
                </div>

                <!-- Location & Background -->
                <div class="form-section" data-section="3">
                    <h3>🌍 Location & Background</h3>
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
                <div class="form-section" data-section="4">
                    <h3>🎓 Professional & Educational Details</h3>
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
                <div class="form-section" data-section="5">
                    <h3>🏖️ Lifestyle & Habits</h3>
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
                <div class="form-section" data-section="6">
                    <h3>👨‍👩‍👧‍👦 Family Information</h3>
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
                </div>

                <!-- Personal Information -->
                <div class="form-section" data-section="7">
                    <h3>💫 Personal Information</h3>
                    <div class="form-group">
                        <label>Hobbies</label>
                        <div class="checkbox-group">
                            <?php 
                            $hobby_options = ['Reading', 'Traveling', 'Music', 'Sports', 'Cooking', 'Photography', 'Dancing', 'Gaming', 'Gardening', 'Art & Crafts'];
                            $selected_hobbies = isset($data['hobbies']) ? explode(', ', $data['hobbies']) : [];
                            foreach($hobby_options as $hobby): 
                            ?>
                            <label><input type="checkbox" name="hobbies[]" value="<?= $hobby ?>" <?= in_array($hobby, $selected_hobbies) ? 'checked' : '' ?>> <?= $hobby ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Interests</label>
                        <div class="checkbox-group">
                            <?php 
                            $interest_options = ['Technology', 'Business', 'Arts', 'Science', 'Literature', 'Politics', 'Social Work', 'Fashion', 'Movies', 'History'];
                            $selected_interests = isset($data['interests']) ? explode(', ', $data['interests']) : [];
                            foreach($interest_options as $interest): 
                            ?>
                            <label><input type="checkbox" name="interests[]" value="<?= $interest ?>" <?= in_array($interest, $selected_interests) ? 'checked' : '' ?>> <?= $interest ?></label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="form-section" data-section="8">
                    <h3>📷 Profile Photo</h3>
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
            </div>

            <div class="form-actions-fixed">
                <button type="submit" class="btn-primary" style="min-width: 200px; padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                    <?= $data ? '✨ Update Bio Data' : '🚀 Save Bio Data' ?>
                </button>
                <?php if ($data): ?>
                    <a href="delete.php?id=<?= $user_id ?>" class="btn-danger" style="min-width: 150px; padding: 1rem 2rem; font-size: 1rem; text-align: center;" 
                       onclick="return confirm('Are you sure you want to delete your bio data? This action cannot be undone.')">
                       🗑️ Delete Bio Data
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <script>
        // Progress bar functionality
        function updateProgress() {
            const sections = document.querySelectorAll('.form-section');
            const totalSections = sections.length;
            let completedSections = 0;

            sections.forEach(section => {
                const inputs = section.querySelectorAll('input[required], select[required], textarea[required]');
                let sectionComplete = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        sectionComplete = false;
                    }
                });

                if (sectionComplete && inputs.length > 0) {
                    completedSections++;
                }
            });

            const progress = (completedSections / totalSections) * 100;
            document.getElementById('progressFill').style.width = progress + '%';
        }

        // Update progress on input change
        document.addEventListener('input', updateProgress);
        document.addEventListener('change', updateProgress);

        // Initial progress calculation
        updateProgress();

        // Smooth scrolling for better UX
        document.querySelectorAll('.form-section h3').forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                header.parentElement.scrollIntoView({ behavior: 'smooth', block: 'start' });
            });
        });
    </script>
</body>
</html>
