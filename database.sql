-- Database setup for BioData App
-- Run these queries in your MySQL database

-- Create database (if not exists)
CREATE DATABASE IF NOT EXISTS bio_data_app;
USE bio_data_app;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bio data table with matrimonial fields
CREATE TABLE IF NOT EXISTS bio_data (
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
    
    -- Additional matrimonial fields
    height VARCHAR(10),
    hair_color VARCHAR(50),
    eye_color VARCHAR(50),
    marital_status ENUM('Single', 'Married', 'Divorced', 'Widowed') DEFAULT 'Single',
    religion VARCHAR(100),
    caste VARCHAR(100),
    mother_tongue VARCHAR(100),
    nationality VARCHAR(100),
    weight VARCHAR(10),
    body_type ENUM('Slim', 'Average', 'Athletic', 'Heavy', 'Other'),
    complexion ENUM('Fair', 'Wheatish', 'Dark', 'Other'),
    smoking ENUM('Yes', 'No', 'Occasionally') DEFAULT 'No',
    drinking ENUM('Yes', 'No', 'Occasionally') DEFAULT 'No',
    diet ENUM('Vegetarian', 'Non-Vegetarian', 'Vegan', 'Jain') DEFAULT 'Vegetarian',
    hobbies TEXT,
    interests TEXT,
    family_type ENUM('Joint Family', 'Nuclear Family', 'Other'),
    family_status ENUM('Middle Class', 'Upper Middle Class', 'Rich', 'Other'),
    about_family TEXT,
    partner_preferences TEXT,
    
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
