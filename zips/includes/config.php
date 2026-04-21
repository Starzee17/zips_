
<?php
/**
 * ZIPS Chau E-Library Configuration
 * Database settings and application constants
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'zips_elibrary');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application Settings
define('SITE_NAME', 'ZIPS Chau E-Library');
define('SITE_TAGLINE', 'Zambia Institute of Purchasing and Supply - Chalimbana University Chapter');
define('LIBRARY_PIN', 'zips2025');
define('ADMIN_PASSWORD', 'zips2025');

// File Upload Settings
define('MAX_UPLOAD_SIZE', 52428800); // 50MB in bytes
define('UPLOAD_PATH', __DIR__ . '/../assets/uploads/');

// Allowed file extensions by type
define('ALLOWED_MODULES', ['pdf', 'doc', 'docx', 'ppt', 'pptx', 'xls', 'xlsx']);
define('ALLOWED_PICTURES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('ALLOWED_VIDEOS', ['mp4', 'avi', 'mov', 'webm', 'mkv']);
define('ALLOWED_ARTICLES', ['pdf', 'doc', 'docx', 'txt']);

// Base URL (adjust based on your setup)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';
define('BASE_URL', $protocol . '://' . $host . '/zips-elibrary');

// Database Connection
function getDBConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];
            $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
        } catch (PDOException $e) {
            // For demo purposes, return null if no database
            // In production, log error and show user-friendly message
            return null;
        }
    }

    return $pdo;
}

// Error Reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Timezone
date_default_timezone_set('Africa/Lusaka');
