
-- ZIPS Chau E-Library Database Schema
-- Version: 1.0
-- Created for: Zambia Institute of Purchasing and Supply - Chalimbana University Chapter

-- Create database
CREATE DATABASE IF NOT EXISTS zips_elibrary CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE zips_elibrary;

-- Resources table - Stores uploaded files
CREATE TABLE IF NOT EXISTS resources (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    file_type ENUM('module', 'picture', 'video', 'article') NOT NULL,
    file_size BIGINT DEFAULT 0,
    share_token VARCHAR(64) UNIQUE,
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    views INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_file_type (file_type),
    INDEX idx_share_token (share_token),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Graduates table - Graduate information
CREATE TABLE IF NOT EXISTS graduates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    student_id VARCHAR(50),
    program VARCHAR(255),
    graduation_year INT,
    graduation_date DATE,
    honors VARCHAR(100),
    photo VARCHAR(500),
    bio TEXT,
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_published TINYINT(1) DEFAULT 0,
    INDEX idx_graduation_year (graduation_year),
    INDEX idx_is_published (is_published)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    full_name VARCHAR(255),
    created_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_login DATETIME,
    is_active TINYINT(1) DEFAULT 1,
    INDEX idx_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Access logs table - Track library access
CREATE TABLE IF NOT EXISTS access_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_address VARCHAR(45),
    user_agent TEXT,
    access_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    page_accessed VARCHAR(255),
    success TINYINT(1) DEFAULT 1,
    INDEX idx_access_date (access_date),
    INDEX idx_ip_address (ip_address)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Educational apps table
CREATE TABLE IF NOT EXISTS edu_apps (
    id INT AUTO_INCREMENT PRIMARY KEY,
    app_name VARCHAR(255) NOT NULL,
    description TEXT,
    app_url VARCHAR(500) NOT NULL,
    icon_url VARCHAR(500),
    category VARCHAR(100),
    is_active TINYINT(1) DEFAULT 1,
    added_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_category (category),
    INDEX idx_is_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Contact messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    subject VARCHAR(255),
    message TEXT NOT NULL,
    submitted_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read TINYINT(1) DEFAULT 0,
    INDEX idx_submitted_date (submitted_date),
    INDEX idx_is_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default admin user (password: zips2025)
INSERT INTO admin_users (username, password_hash, email, full_name) VALUES
('admin', '$2y$10$YourHashedPasswordHere', 'admin@zipschau.com', 'System Administrator');

-- Insert sample educational apps
INSERT INTO edu_apps (app_name, description, app_url, category) VALUES
('Khan Academy', 'Free world-class education for anyone, anywhere. Covers math, science, computing, and more.', 'https://www.khanacademy.org', 'Learning Platform'),
('Coursera', 'Access courses from top universities and companies worldwide.', 'https://www.coursera.org', 'Learning Platform'),
('edX', 'Free online courses from Harvard, MIT, and more.', 'https://www.edx.org', 'Learning Platform'),
('Duolingo', 'Learn languages for free with gamified lessons.', 'https://www.duolingo.com', 'Language Learning'),
('Quizlet', 'Study tools and flashcards to help you learn anything.', 'https://quizlet.com', 'Study Tools'),
('Google Scholar', 'Search scholarly literature across many disciplines.', 'https://scholar.google.com', 'Research'),
('ResearchGate', 'Professional network for scientists and researchers.', 'https://www.researchgate.net', 'Research'),
('JSTOR', 'Digital library of academic journals, books, and primary sources.', 'https://www.jstor.org', 'Research'),
('Grammarly', 'Writing assistant that helps improve your grammar and writing.', 'https://www.grammarly.com', 'Writing Tools'),
('Notion', 'All-in-one workspace for notes, tasks, and collaboration.', 'https://www.notion.so', 'Productivity');
