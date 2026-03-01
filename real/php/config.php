<?php
/**
 * Configuration file for SSVET Admin Panel
 * Contains database paths, constants, and settings
 */

// Environment settings
define('ENVIRONMENT', 'development'); // development | production
define('DEBUG_MODE', ENVIRONMENT === 'development');

// Project paths
define('BASE_PATH', dirname(dirname(__FILE__)));
define('JSON_PATH', BASE_PATH . '/json/');
define('TEMPLATE_PATH', BASE_PATH . '/templates/');
define('UPLOAD_PATH', BASE_PATH . '/uploads/');

// JSON file paths
define('FACULTY_JSON', JSON_PATH . 'facultyData.json');
define('NOTICES_JSON', JSON_PATH . 'notices.json');
define('FEES_JSON', JSON_PATH . 'fees.json');
define('FINANCIALS_JSON', JSON_PATH . 'financials.json');
define('TOPPERS_JSON', JSON_PATH . 'toppersData.json');
define('PHOTOS_JSON', JSON_PATH . 'photos.json');
define('VIDEOS_JSON', JSON_PATH . 'videos.json');

// Admin authentication
define('ADMIN_EMAIL', 'admin@example.com');
define('ADMIN_PASSWORD', 'admin123'); // In production, use hashed passwords

// URL and paths for web
define('BASE_URL', '/Project_SSV_Website/real');
define('STATIC_URL', BASE_URL . '/static');
define('IMAGES_URL', BASE_URL . '/images');

// Session configuration
define('SESSION_TIMEOUT', 3600); // 1 hour
define('SESSION_KEY_ADMIN', 'admin_logged_in');
define('SESSION_KEY_ADMIN_EMAIL', 'admin_email');

// File upload settings
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
define('ALLOWED_IMAGE_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif', 'webp']);

// Application metadata
define('APP_NAME', 'SHRI SIDDHI VINAYAK EDUCATIONAL TRUST TIBBI');
define('APP_SHORT_NAME', 'SSVET');
define('TIMEZONE', 'Asia/Kolkata');

// Color scheme
define('COLOR_PRIMARY', '#244855');
define('COLOR_SECONDARY', '#f5a623');
define('COLOR_ACCENT', '#e63946');

// Set timezone
date_default_timezone_set(TIMEZONE);

// Enable error reporting during development
if (DEBUG_MODE) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Global response helper function
 */
function send_response($success, $message, $data = null, $redirect = null) {
    $response = [
        'success' => (bool) $success,
        'message' => $message
    ];
    
    if ($data !== null) {
        $response['data'] = $data;
    }
    
    if ($redirect !== null) {
        $response['redirect'] = $redirect;
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}

/**
 * Global error handler
 */
function handle_error($error_message, $error_code = 500) {
    if (DEBUG_MODE) {
        echo $error_message;
    } else {
        http_response_code($error_code);
        echo "An error occurred. Please try again later.";
    }
    exit;
}

/**
 * Safe output function to prevent XSS
 */
function safe_output($string, $encoding = 'UTF-8') {
    return htmlspecialchars($string, ENT_QUOTES, $encoding);
}

/**
 * Redirect to URL
 */
function redirect($url) {
    header("Location: " . $url);
    exit;
}
?>
