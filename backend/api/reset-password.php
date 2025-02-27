<?php
/**
 * Password Reset API
 * 
 * Handles password reset requests and password changes
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// Include required files
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/User.php';

// Get JSON input
$data = json_decode(file_get_contents('php://input'), true);

// Check if this is a request for a reset token or a password change
if (isset($data['email']) && !isset($data['token'])) {
    // This is a request for a reset token
    
    // Validate email
    $email = sanitizeInput($data['email']);
    if (!validateEmail($email)) {
        http_response_code(400);
        echo json_encode(['status' => 'error', 'message' => 'Invalid email format']);
        exit;
    }
    
    // Request password reset
    $user = new User();
    $result = $user->requestPasswordReset($email);
    
    // In a production environment, you would send an email with the reset link
    // For this example, we'll just return the token in the response
    
    http_response_code(200);
    echo json_encode($result);
    
} elseif (isset($data['token']) && isset($data['password'])) {
    // This is a password change request
    
    $token = sanitizeInput($data['token']);
    $password = $data['password']; // Don't sanitize password
    
    // Validate password strength
    if (!validatePasswordStrength($password)) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error', 
            'message' => 'Password must be at least 8 characters and contain uppercase, lowercase, and numbers'
        ]);
        exit;
    }
    
    // Reset the password
    $user = new User();
    $result = $user->resetPassword($token, $password);
    
    if ($result['status'] === 'success') {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    
    echo json_encode($result);
    
} else {
    // Invalid request
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid request parameters']);
} 