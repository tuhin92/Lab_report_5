<?php include 'db.php'; ?>
<!DOCTYPE html>
<html>
<head>
    <title>BioData App - Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include 'navbar.php'; ?>
    
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
        
        <h1>All Users Bio Data</h1>
        <div class="bio-grid">
            <?php
            $res = $conn->query("SELECT * FROM bio_data ORDER BY id DESC");
            if ($res && $res->num_rows > 0) {
                while ($row = $res->fetch_assoc()) {
                    echo "<div class='bio-card' onclick='openBioModal(" . $row['id'] . ")'>";
                    echo "<div class='bio-header'>";
                    if ($row['photo']) {
                        echo "<img src='uploads/" . htmlspecialchars($row['photo']) . "' class='bio-photo' alt='Profile Photo'>";
                    } else {
                        echo "<div class='bio-photo' style='background: linear-gradient(135deg, #667eea, #764ba2); display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.5rem;'>";
                        echo strtoupper(substr($row['name'], 0, 1));
                        echo "</div>";
                    }
                    echo "<div class='bio-info'>";
                    echo "<h3>" . htmlspecialchars($row['name']) . "</h3>";
                    echo "<p style='color: #666; margin: 0;'>" . htmlspecialchars($row['profession']) . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "<div class='bio-details'>";
                    echo "<div class='bio-detail'><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</div>";
                    echo "<div class='bio-detail'><strong>Phone:</strong> " . htmlspecialchars($row['phone']) . "</div>";
                    if ($row['education']) {
                        echo "<div class='bio-detail'><strong>Education:</strong> " . htmlspecialchars($row['education']) . "</div>";
                    }
                    if ($row['skills']) {
                        echo "<div class='bio-detail'><strong>Skills:</strong> " . htmlspecialchars($row['skills']) . "</div>";
                    }
                    echo "</div>";
                    echo "</div>";
                }
            } else {
                echo "<div class='text-center' style='padding: 2rem; color: #666;'>";
                echo "<h3>No bio data found</h3>";
                echo "<p>Be the first to add your bio data!</p>";
                if (!isset($_SESSION['user_id'])) {
                    echo "<a href='register.php' class='btn-primary' style='display: inline-block; margin-top: 1rem;'>Register Now</a>";
                }
                echo "</div>";
            }
            ?>
        </div>
    </div>

    <!-- Bio Detail Modal -->
    <div id="bioModal" class="modal">
        <div class="modal-content">
            <button class="modal-close" onclick="closeBioModal()">&times;</button>
            <div id="modalContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function openBioModal(bioId) {
            const modal = document.getElementById('bioModal');
            const content = document.getElementById('modalContent');
            
            // Show loading
            content.innerHTML = '<div style="text-align: center; padding: 2rem;"><div class="loading"></div></div>';
            modal.classList.add('active');
            
            // Fetch bio details
            fetch('get_bio_details.php?id=' + bioId)
                .then(response => response.text())
                .then(data => {
                    content.innerHTML = data;
                })
                .catch(error => {
                    content.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ff4b2b;">Error loading bio details</div>';
                });
        }
        
        function closeBioModal() {
            document.getElementById('bioModal').classList.remove('active');
        }
        
        // Close modal when clicking outside
        document.getElementById('bioModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBioModal();
            }
        });
    </script>
</body>
</html>
