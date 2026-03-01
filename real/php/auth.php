<?php
/**
 * Authentication middleware for SSVET Admin Panel
 * Handles session validation, login checks, etc.
 */

require_once 'config.php';

class Auth {
    /**
     * Check if user is logged in
     * @return bool
     */
    public static function is_logged_in() {
        return isset($_SESSION[SESSION_KEY_ADMIN]) && $_SESSION[SESSION_KEY_ADMIN] === true;
    }
    
    /**
     * Get logged-in admin email
     * @return string|null
     */
    public static function get_admin_email() {
        return $_SESSION[SESSION_KEY_ADMIN_EMAIL] ?? null;
    }
    
    /**
     * Login admin user
     * @param string $email
     * @param string $password
     * @return bool
     */
    public static function login($email, $password) {
        // Simple authentication with hardcoded credentials
        // In production, use database and password hashing
        if ($email === ADMIN_EMAIL && $password === ADMIN_PASSWORD) {
            $_SESSION[SESSION_KEY_ADMIN] = true;
            $_SESSION[SESSION_KEY_ADMIN_EMAIL] = $email;
            
            // Log the login
            log_message("Admin logged in: $email", 'info');
            
            return true;
        }
        
        log_message("Failed login attempt for email: $email", 'warning');
        return false;
    }
    
    /**
     * Logout admin user
     * @return void
     */
    public static function logout() {
        if (self::is_logged_in()) {
            $email = self::get_admin_email();
            log_message("Admin logged out: $email", 'info');
        }
        
        session_destroy();
    }
    
    /**
     * Require login (redirect if not logged in)
     * @return void
     */
    public static function require_login() {
        if (!self::is_logged_in()) {
            redirect('admin_login.php');
        }
    }
    
    /**
     * Check session timeout
     * @return bool
     */
    public static function has_timed_out() {
        if (!isset($_SESSION['last_activity'])) {
            $_SESSION['last_activity'] = time();
            return false;
        }
        
        $timeout = SESSION_TIMEOUT;
        if (time() - $_SESSION['last_activity'] > $timeout) {
            self::logout();
            return true;
        }
        
        // Update last activity time
        $_SESSION['last_activity'] = time();
        return false;
    }
    
    /**
     * Generate and retrieve CSRF token
     * @return string
     */
    public static function csrf_token() {
        return generate_csrf_token();
    }
    
    /**
     * Verify CSRF token from request
     * @param string $token
     * @return bool
     */
    public static function verify_csrf($token) {
        return verify_csrf_token($token);
    }
    
    /**
     * Get CSRF token input HTML
     * @return string
     */
    public static function csrf_input() {
        return '<input type="hidden" name="csrf_token" value="' . self::csrf_token() . '">';
    }
}

/**
 * Function wrapper for Auth class methods
 */
function is_admin_logged_in() {
    return Auth::is_logged_in();
}

function require_admin_login() {
    Auth::require_login();
}

function get_admin_email() {
    return Auth::get_admin_email();
}

function admin_logout() {
    Auth::logout();
}

function get_csrf_token() {
    return Auth::csrf_token();
}

function verify_csrf_token_request($token) {
    return Auth::verify_csrf($token);
}

function csrf_token_input() {
    return Auth::csrf_input();
}
?>
