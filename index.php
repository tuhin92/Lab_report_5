<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>All Users Bio Data</h1>
    <?php
    $res = $conn->query("SELECT * FROM bio_data");
    while ($row = $res->fetch_assoc()) {
        echo "<div style='border:1px solid #ddd; padding:10px; margin:10px;'>";
        echo "<h3>".$row['name']."</h3>";
        if ($row['photo']) {
            echo "<img src='uploads/".$row['photo']."' width='100'><br>";
        }
        echo "Email: ".$row['email']."<br>";
        echo "Phone: ".$row['phone']."<br>";
        echo "Profession: ".$row['profession']."<br>";
        echo "</div>";
    }
    ?>
    <a href="login.php">Login</a> | <a href="register.php">Register</a>
</div>
</body>
</html>
