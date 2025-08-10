<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Register - BioData App</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="auth-container">
        <h1>Create Account</h1>
        <p style="text-align: center; color: #666; margin-bottom: 2rem;">Join our community today</p>
        
        <?php
        if (isset($_POST['register'])) {
            $username = trim($_POST['username']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $confirm_password = $_POST['confirm_password'];

            // Validation
            if (empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
                echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Please fill in all fields.</div>";
            } elseif ($password !== $confirm_password) {
                echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Passwords do not match.</div>";
            } elseif (strlen($password) < 6) {
                echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Password must be at least 6 characters long.</div>";
            } else {
                // Check if email already exists
                $check_stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
                $check_stmt->bind_param("s", $email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();
                
                if ($check_result->num_rows > 0) {
                    echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Email already exists. Please use a different email.</div>";
                } else {
                    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                    $stmt->bind_param("sss", $username, $email, $hashed_password);
                    
                    if ($stmt->execute()) {
                        echo "<div style='background: #e6ffe6; color: #00b894; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Registration successful! You can now login.</div>";
                        echo "<script>setTimeout(() => { window.location.href = 'login.php'; }, 2000);</script>";
                    } else {
                        echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Registration failed. Please try again.</div>";
                    }
                    $stmt->close();
                }
                $check_stmt->close();
            }
        }
        ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" placeholder="Choose a username" 
                       value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" 
                       value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Create a password (min. 6 characters)" required>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm your password" required>
            </div>
            
            <button type="submit" name="register">Create Account</button>
        </form>
        
        <div class="auth-links">
            <p>Already have an account? <a href="login.php">Sign in</a></p>
        </div>
    </div>
</body>
</html>
