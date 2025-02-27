<?php
/**
 * Login API endpoint
 * Handles user authentication
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

// Get JSON input
$input = json_decode(file_get_contents('php://input'), true);

// Validate input
if (!isset($input['username']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Username and password are required'
    ]);
    exit;
}

// Sanitize input
$username = sanitizeInput($input['username']);
$password = $input['password']; // Don't sanitize password
$remember = isset($input['remember']) ? (bool)$input['remember'] : false;

// Authenticate user
$user = new User();
$result = $user->login($username, $password, $remember);

if ($result['status'] === 'success') {
    // Set session variables
    $_SESSION['user_id'] = $result['user']['id'];
    $_SESSION['username'] = $result['user']['username'];
    $_SESSION['email'] = $result['user']['email'];
    
    // Set remember me cookie if requested
    if ($remember && isset($result['remember_token'])) {
        setRememberMeCookie($result['remember_token']);
    }
    
    // Return success response
    echo json_encode([
        'status' => 'success',
        'message' => 'Login successful',
        'user' => $result['user']
    ]);
} else {
    // Return error response
    http_response_code(401); // Unauthorized
    echo json_encode([
        'status' => 'error',
        'message' => $result['message']
    ]);
} 