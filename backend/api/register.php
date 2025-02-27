<?php
/**
 * Register API endpoint
 * Handles user registration
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
if (!isset($input['username']) || !isset($input['email']) || !isset($input['password'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Username, email, and password are required'
    ]);
    exit;
}

// Sanitize and validate input
$username = sanitizeInput($input['username']);
$email = sanitizeInput($input['email']);
$password = $input['password']; // Don't sanitize password

// Validate email format
if (!validateEmail($email)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid email format'
    ]);
    exit;
}

// Validate password strength
if (!validatePasswordStrength($password)) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Password must be at least 8 characters and contain uppercase, lowercase, and numbers'
    ]);
    exit;
}

// Create user
$user = new User();
$result = $user->register($username, $email, $password);

if ($result['status'] === 'success') {
    // Set session variables
    $_SESSION['user_id'] = $result['user_id'];
    $_SESSION['username'] = $username;
    $_SESSION['email'] = $email;
    
    // Return success response
    http_response_code(201); // Created
    echo json_encode([
        'status' => 'success',
        'message' => 'Registration successful',
        'user' => [
            'id' => $result['user_id'],
            'username' => $username,
            'email' => $email
        ]
    ]);
} else {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => $result['message']
    ]);
} 