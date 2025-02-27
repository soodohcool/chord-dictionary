<?php
/**
 * User API endpoint
 * Handles user authentication status and profile information
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include required files
require_once __DIR__ . '/../config/auth.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    secureSessionStart();
}

// GET request - check if user is logged in
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isLoggedIn()) {
        // User is logged in, get current user data
        $userData = getCurrentUser();
        
        echo json_encode([
            'status' => 'success',
            'user' => $userData
        ]);
    } else {
        // User is not logged in
        http_response_code(401); // Unauthorized
        echo json_encode([
            'status' => 'error',
            'message' => 'Not authenticated'
        ]);
    }
    exit;
}

// Invalid request method
http_response_code(405);
echo json_encode([
    'status' => 'error',
    'message' => 'Method not allowed'
]); 