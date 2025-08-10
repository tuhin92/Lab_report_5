<?php
include 'db.php';

echo "Setting up database tables..." . PHP_EOL;

// Create users table
$users_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if ($conn->query($users_sql) === TRUE) {
    echo "Users table created successfully!" . PHP_EOL;
} else {
    echo "Error creating users table: " . $conn->error . PHP_EOL;
}

// Create bio_data table with all the fields we've been using
$bio_data_sql = "CREATE TABLE IF NOT EXISTS bio_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    dob DATE NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    education VARCHAR(200),
    profession VARCHAR(100),
    skills TEXT,
    photo VARCHAR(255),
    
    -- Physical Attributes
    height VARCHAR(20),
    weight VARCHAR(20),
    hair_color VARCHAR(50),
    eye_color VARCHAR(50),
    body_type ENUM('Slim', 'Average', 'Athletic', 'Heavy', 'Other'),
    complexion ENUM('Fair', 'Wheatish', 'Dark', 'Other'),
    
    -- Background Information
    religion VARCHAR(50),
    caste VARCHAR(50),
    mother_tongue VARCHAR(50),
    nationality VARCHAR(50),
    
    -- Lifestyle
    smoking ENUM('Yes', 'No', 'Occasionally'),
    drinking ENUM('Yes', 'No', 'Occasionally'),
    diet ENUM('Vegetarian', 'Non-Vegetarian', 'Vegan', 'Jain'),
    
    -- Family Information
    family_type ENUM('Joint Family', 'Nuclear Family', 'Other'),
    family_status ENUM('Middle Class', 'Upper Middle Class', 'Rich', 'Other'),
    about_family TEXT,
    
    -- Personal Information
    hobbies TEXT,
    interests TEXT,
    partner_preferences TEXT,
    
    -- Marital Status
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed'),
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";

if ($conn->query($bio_data_sql) === TRUE) {
    echo "Bio_data table created successfully!" . PHP_EOL;
} else {
    echo "Error creating bio_data table: " . $conn->error . PHP_EOL;
}

// Display final status
$result = $conn->query('SHOW TABLES');
echo "\nTables now in database:" . PHP_EOL;
while ($row = $result->fetch_array()) {
    echo "- " . $row[0] . PHP_EOL;
}

echo "\nDatabase setup completed!" . PHP_EOL;
?>
