<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - BioData App</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <div class="auth-container">
        <h1>Welcome Back</h1>
        <p style="text-align: center; color: #666; margin-bottom: 2rem;">Sign in to your account</p>
        
        <?php
        if (isset($_POST['login'])) {
            $email = trim($_POST['email']);
            $pass = $_POST['password'];

            if (empty($email) || empty($pass)) {
                echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Please fill in all fields.</div>";
            } else {
                $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $res = $stmt->get_result();
                
                if ($res->num_rows > 0) {
                    $row = $res->fetch_assoc();
                    if (password_verify($pass, $row['password'])) {
                        $_SESSION['user_id'] = $row['id'];
                        echo "<div style='background: #e6ffe6; color: #00b894; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Login successful! Redirecting...</div>";
                        echo "<script>setTimeout(() => { window.location.href = 'profile.php'; }, 1500);</script>";
                    } else {
                        echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Invalid password.</div>";
                    }
                } else {
                    echo "<div style='background: #ffe6e6; color: #d63031; padding: 1rem; border-radius: 8px; margin-bottom: 1rem; text-align: center;'>Email not found.</div>";
                }
                $stmt->close();
            }
        }
        ?>
        
        <form method="post">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" name="login">Sign In</button>
        </form>
        
        <div class="auth-links">
            <p>New user? <a href="register.php">Create an account</a></p>
        </div>
    </div>
</body>
</html>
