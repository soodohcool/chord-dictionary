<?php
/**
 * Logout API endpoint
 * Handles user logout
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include required files
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/User.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    secureSessionStart();
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Check if user is logged in
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Not authenticated'
    ]);
    exit;
}

// Clear remember me cookie if it exists
if (isset($_COOKIE['remember_token'])) {
    $user = new User();
    $user->logout($_COOKIE['remember_token']);
    clearRememberMeCookie();
}

// Clear session data
$_SESSION = [];

// If a session cookie is used, clear it
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params['path'],
        $params['domain'],
        $params['secure'],
        $params['httponly']
    );
}

// Destroy the session
session_destroy();

// Return success response
echo json_encode([
    'status' => 'success',
    'message' => 'Logout successful'
]); 