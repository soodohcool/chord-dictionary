<?php
/**
 * Authentication Utilities
 * 
 * Helper functions for authentication and session management
 */

require_once __DIR__ . '/../models/User.php';

/**
 * Start a secure session
 */
function secureSessionStart() {
    $session_name = 'chord_dictionary_session';
    $secure = false; // Set to true if using HTTPS
    $httponly = true;
    
    // Force session to use cookies only
    ini_set('session.use_only_cookies', 1);
    
    // Get current cookie params
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );
    
    // Set session name
    session_name($session_name);
    
    // Start the session
    session_start();
    
    // Regenerate session ID to prevent session fixation
    session_regenerate_id(true);
}

/**
 * Check if user is logged in
 * 
 * @return bool True if user is logged in
 */
function isLoggedIn() {
    // Check if session exists
    if (isset($_SESSION['user_id'])) {
        return true;
    }
    
    // Check for remember me cookie
    if (isset($_COOKIE['remember_token'])) {
        $user = new User();
        $userData = $user->verifyRememberToken($_COOKIE['remember_token']);
        
        if ($userData) {
            // Set session variables
            $_SESSION['user_id'] = $userData['id'];
            $_SESSION['username'] = $userData['username'];
            $_SESSION['email'] = $userData['email'];
            
            return true;
        }
    }
    
    return false;
}

/**
 * Get current user ID
 * 
 * @return int|null User ID or null if not logged in
 */
function getCurrentUserId() {
    return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
}

/**
 * Get current user data
 * 
 * @return array|null User data or null if not logged in
 */
function getCurrentUser() {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'email' => $_SESSION['email']
        ];
    }
    
    return null;
}

/**
 * Set remember me cookie
 * 
 * @param string $token Remember me token
 * @param int $days Number of days to remember
 */
function setRememberMeCookie($token, $days = 30) {
    $expiry = time() + (86400 * $days); // 86400 = 1 day in seconds
    setcookie('remember_token', $token, $expiry, '/', '', false, true);
}

/**
 * Clear remember me cookie
 */
function clearRememberMeCookie() {
    setcookie('remember_token', '', time() - 3600, '/', '', false, true);
}

/**
 * Require authentication for a page
 * 
 * @param string $redirectUrl URL to redirect to if not logged in
 */
function requireAuth($redirectUrl = '/login.php') {
    if (!isLoggedIn()) {
        header("Location: $redirectUrl");
        exit;
    }
}

/**
 * Generate CSRF token
 * 
 * @return string CSRF token
 */
function generateCsrfToken() {
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 * 
 * @param string $token Token to verify
 * @return bool True if token is valid
 */
function verifyCsrfToken($token) {
    if (!isset($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Sanitize input data
 * 
 * @param string $data Input data
 * @return string Sanitized data
 */
function sanitizeInput($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

/**
 * Validate email
 * 
 * @param string $email Email to validate
 * @return bool True if email is valid
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Validate password strength
 * 
 * @param string $password Password to validate
 * @return bool True if password is strong enough
 */
function validatePasswordStrength($password) {
    // At least 8 characters, contains at least one uppercase, one lowercase, and one number
    return preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password);
} 