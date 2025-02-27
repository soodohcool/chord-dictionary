<?php
/**
 * API Index
 * 
 * Provides information about available API endpoints
 */

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
    exit;
}

// API information
$apiInfo = [
    'name' => 'Chord Dictionary API',
    'version' => '1.0.0',
    'description' => 'API for the Guitar Chord Dictionary application',
    'endpoints' => [
        [
            'path' => '/api/register.php',
            'method' => 'POST',
            'description' => 'Register a new user',
            'requires_auth' => false
        ],
        [
            'path' => '/api/login.php',
            'method' => 'POST',
            'description' => 'Login a user',
            'requires_auth' => false
        ],
        [
            'path' => '/api/logout.php',
            'method' => 'POST',
            'description' => 'Logout a user',
            'requires_auth' => true
        ],
        [
            'path' => '/api/user.php',
            'method' => 'GET',
            'description' => 'Get current user information',
            'requires_auth' => true
        ],
        [
            'path' => '/api/reset-password.php',
            'method' => 'POST',
            'description' => 'Request password reset or reset password with token',
            'requires_auth' => false
        ],
        [
            'path' => '/api/progressions.php',
            'method' => 'GET',
            'description' => 'Get chord progressions (public or user\'s)',
            'requires_auth' => false
        ],
        [
            'path' => '/api/progressions.php',
            'method' => 'POST',
            'description' => 'Create a new chord progression',
            'requires_auth' => true
        ],
        [
            'path' => '/api/progressions.php',
            'method' => 'PUT',
            'description' => 'Update an existing chord progression',
            'requires_auth' => true
        ],
        [
            'path' => '/api/progressions.php',
            'method' => 'DELETE',
            'description' => 'Delete a chord progression',
            'requires_auth' => true
        ]
    ]
];

// Return API information
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'api' => $apiInfo
]); 