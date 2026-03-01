<?php
/**
 * Helper functions for SSVET Admin Panel
 * Contains utility functions for JSON handling, validation, etc.
 */

require_once 'config.php';

// ===========================
// === JSON FILE FUNCTIONS ===
// ===========================

/**
 * Load JSON data from file
 * @param string $file_path - Full path to JSON file
 * @return array - Decoded JSON data or empty array
 */
function load_json_file($file_path) {
    if (!file_exists($file_path)) {
        return array();
    }
    
    $json_content = file_get_contents($file_path);
    
    if ($json_content === false) {
        return array();
    }
    
    $data = json_decode($json_content, true);
    return is_array($data) ? $data : array();
}

/**
 * Save data to JSON file
 * @param string $file_path - Full path to JSON file
 * @param array $data - Data to encode and save
 * @return bool - True on success, false on failure
 */
function save_json_file($file_path, $data) {
    $json_content = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
    if ($json_content === false) {
        return false;
    }
    
    $result = file_put_contents($file_path, $json_content);
    return $result !== false;
}

/**
 * Get JSON file with error handling
 * @param string $file_path
 * @return array
 */
function get_json_data($file_path, $default = []) {
    if (!file_exists($file_path)) {
        return $default;
    }
    
    $data = load_json_file($file_path);
    return is_array($data) ? $data : $default;
}

// ===========================
// === VALIDATION FUNCTIONS ==
// ===========================

/**
 * Validate email format
 * @param string $email
 * @return bool
 */
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate URL format
 * @param string $url
 * @return bool
 */
function is_valid_url($url) {
    return filter_var($url, FILTER_VALIDATE_URL) !== false;
}

/**
 * Validate if string is alphanumeric
 * @param string $string
 * @return bool
 */
function is_alphanumeric($string) {
    return preg_match('/^[a-zA-Z0-9]+$/', $string) === 1;
}

/**
 * Validate required field
 * @param string $value
 * @return bool
 */
function is_required($value) {
    return !empty(trim($value));
}

/**
 * Sanitize user input by trimming and escaping HTML characters
 * @param string $value
 * @return string
 */
function sanitize_input($value) {
    if (!is_string($value)) {
        return $value;
    }
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * Validate minimum string length
 * @param string $value
 * @param int $min_length
 * @return bool
 */
function is_min_length($value, $min_length) {
    return strlen(trim($value)) >= $min_length;
}

/**
 * Validate maximum string length
 * @param string $value
 * @param int $max_length
 * @return bool
 */
function is_max_length($value, $max_length) {
    return strlen(trim($value)) <= $max_length;
}

/**
 * Validate numeric value
 * @param mixed $value
 * @return bool
 */
function is_numeric_value($value) {
    return is_numeric($value);
}

/**
 * Validate integer value
 * @param mixed $value
 * @return bool
 */
function is_integer_value($value) {
    return filter_var($value, FILTER_VALIDATE_INT) !== false;
}

/**
 * Validate file extension
 * @param string $filename
 * @param array $allowed_extensions
 * @return bool
 */
function is_valid_file_extension($filename, $allowed_extensions) {
    $extension = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
    return in_array($extension, $allowed_extensions);
}

/**
 * Validate faculty data
 * @param array $faculty_data - Faculty data to validate
 * @return array - Errors array (empty if valid)
 */
function validate_faculty($faculty_data) {
    $errors = array();
    
    // Validate name
    if (!is_required($faculty_data['name'] ?? '')) {
        $errors[] = "Faculty name is required.";
    } elseif (!is_max_length($faculty_data['name'], 255)) {
        $errors[] = "Faculty name must not exceed 255 characters.";
    }
    
    // Validate title
    if (!is_required($faculty_data['title'] ?? '')) {
        $errors[] = "Title/Designation is required.";
    } elseif (!is_max_length($faculty_data['title'], 255)) {
        $errors[] = "Title must not exceed 255 characters.";
    }
    
    // Validate image path
    if (!is_required($faculty_data['image'] ?? '')) {
        $errors[] = "Image path is required.";
    } elseif (!is_max_length($faculty_data['image'], 500)) {
        $errors[] = "Image path must not exceed 500 characters.";
    }
    
    return $errors;
}

// ===========================
// === STRING FUNCTIONS ======
// ===========================

/**
 * Safe trim with null check
 * @param mixed $value
 * @return string
 */
function safe_trim($value) {
    return is_null($value) ? '' : trim($value);
}

/**
 * Slugify string for URLs
 * @param string $string
 * @return string
 */
function slugify($string) {
    $string = strtolower(trim($string));
    $string = preg_replace('/[^a-z0-9-]+/', '-', $string);
    $string = trim($string, '-');
    return preg_replace('/-+/', '-', $string);
}

/**
 * Truncate string with ellipsis
 * @param string $string
 * @param int $limit
 * @param string $break
 * @param string $pad
 * @return string
 */
function truncate_string($string, $limit, $break = " ", $pad = "...") {
    if (strlen($string) <= $limit) return $string;
    
    if (false !== ($breakpoint = strpos($string, $break, $limit))) {
        if ($breakpoint < strlen($string) - 1) {
            $string = substr($string, 0, $breakpoint) . $pad;
        }
    }
    return $string;
}

// ===========================
// === ARRAY FUNCTIONS =======
// ===========================

/**
 * Find item in array by key and value
 * @param array $array
 * @param string $key
 * @param mixed $value
 * @return mixed - Found item or null
 */
function find_in_array($array, $key, $value) {
    foreach ($array as $item) {
        if (isset($item[$key]) && $item[$key] == $value) {
            return $item;
        }
    }
    return null;
}

/**
 * Find index of item in array by key and value
 * @param array $array
 * @param string $key
 * @param mixed $value
 * @return int - Index or -1
 */
function find_index_in_array($array, $key, $value) {
    foreach ($array as $index => $item) {
        if (isset($item[$key]) && $item[$key] == $value) {
            return $index;
        }
    }
    return -1;
}

/**
 * Remove duplicates from array of associative arrays
 * @param array $array
 * @param string $key
 * @return array
 */
function array_unique_by_key($array, $key) {
    $result = array();
    $seen = array();
    
    foreach ($array as $item) {
        if (!in_array($item[$key], $seen)) {
            $seen[] = $item[$key];
            $result[] = $item;
        }
    }
    
    return $result;
}

// ===========================
// === DATE/TIME FUNCTIONS ===
// ===========================

/**
 * Get current date
 * @param string $format
 * @return string
 */
function get_current_date($format = 'Y-m-d H:i:s') {
    return date($format);
}

/**
 * Format date for display
 * @param string $date
 * @param string $format
 * @return string
 */
function format_date($date, $format = 'M d, Y') {
    $timestamp = strtotime($date);
    return $timestamp ? date($format, $timestamp) : $date;
}

/**
 * Get time ago string
 * @param string $date
 * @return string
 */
function get_time_ago($date) {
    $timestamp = strtotime($date);
    $now = time();
    $diff = $now - $timestamp;
    
    if ($diff < 60) {
        return "Just now";
    } elseif ($diff < 3600) {
        return round($diff / 60) . " minutes ago";
    } elseif ($diff < 86400) {
        return round($diff / 3600) . " hours ago";
    } elseif ($diff < 604800) {
        return round($diff / 86400) . " days ago";
    } else {
        return round($diff / 604800) . " weeks ago";
    }
}

// ===========================
// === SECURITY FUNCTIONS ====
// ===========================

/**
 * Generate CSRF token
 * @return string
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * @param string $token
 * @return bool
 */
function verify_csrf_token($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Hash password
 * @param string $password
 * @return string
 */
function hash_password($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 * @param string $password
 * @param string $hash
 * @return bool
 */
function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

/**
 * Generate unique ID
 * @param string $prefix
 * @return string
 */
function generate_unique_id($prefix = '') {
    $id = $prefix . round(microtime(true) * 1000);
    return $id;
}

// ===========================
// === FILE FUNCTIONS ========
// ===========================

/**
 * Check if file exists
 * @param string $path
 * @return bool
 */
function file_exists_safe($path) {
    return file_exists($path) && is_file($path);
}

/**
 * Check if directory exists
 * @param string $path
 * @return bool
 */
function dir_exists_safe($path) {
    return file_exists($path) && is_dir($path);
}

/**
 * Create directory if not exists
 * @param string $path
 * @param int $mode
 * @return bool
 */
function create_dir_if_not_exists($path, $mode = 0755) {
    if (!dir_exists_safe($path)) {
        return mkdir($path, $mode, true);
    }
    return true;
}

/**
 * Get file size in readable format
 * @param int $bytes
 * @param int $precision
 * @return string
 */
function format_file_size($bytes, $precision = 2) {
    $units = array('B', 'KB', 'MB', 'GB', 'TB');
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}

// ===========================
// === LOGGER FUNCTION =======
// ===========================

/**
 * Log message to file
 * @param string $message
 * @param string $type
 * @return bool
 */
function log_message($message, $type = 'info') {
    $log_file = BASE_PATH . '/logs/admin.log';
    
    // Create logs directory if not exists
    create_dir_if_not_exists(dirname($log_file));
    
    $timestamp = get_current_date('Y-m-d H:i:s');
    $log_entry = "[$timestamp] [$type] $message\n";
    
    return file_put_contents($log_file, $log_entry, FILE_APPEND) !== false;
}

// ===========================
// === ADMIN FUNCTIONS =======
// ===========================

/**
 * Get current admin name from session
 * @return string
 */
function get_current_admin_name() {
    if (isset($_SESSION['admin_email'])) {
        $email = $_SESSION['admin_email'];
        // Extract name from email (part before @)
        return ucfirst(explode('@', $email)[0]);
    }
    return 'Admin';
}
?>
