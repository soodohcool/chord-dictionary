<?php
/**
 * Progressions API endpoint
 * Handles CRUD operations for chord progressions
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Credentials: true');

// Handle preflight OPTIONS request
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Include required files
require_once __DIR__ . '/../config/auth.php';
require_once __DIR__ . '/../models/Progression.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    secureSessionStart();
}

// Initialize progression model
$progression = new Progression();

// Handle GET request - retrieve progressions
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if a specific progression is requested
    if (isset($_GET['id'])) {
        $id = (int)$_GET['id'];
        $userId = isLoggedIn() ? getCurrentUserId() : null;
        
        // Get the progression
        $result = $progression->getProgression($id, $userId);
        
        if ($result['status'] === 'success') {
            echo json_encode($result);
        } else {
            http_response_code(404);
            echo json_encode($result);
        }
    } else {
        // If user is logged in, get their progressions
        if (isLoggedIn()) {
            $userId = getCurrentUserId();
            $result = $progression->getUserProgressions($userId);
            echo json_encode($result);
        } else {
            // For non-authenticated users, get public progressions
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 20;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            
            $result = $progression->getPublicProgressions($limit, $offset);
            echo json_encode($result);
        }
    }
    exit;
}

// The following operations require authentication
if (!isLoggedIn()) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Authentication required'
    ]);
    exit;
}

// Get the current user ID
$userId = getCurrentUserId();

// Handle POST request - create a new progression
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($input['name']) || !isset($input['chords']) || !is_array($input['chords'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input. Name and chords array are required.'
        ]);
        exit;
    }
    
    // Sanitize input
    $name = sanitizeInput($input['name']);
    $chords = $input['chords']; // Array of chord names
    $isPublic = isset($input['is_public']) ? (bool)$input['is_public'] : false;
    
    // Save the progression
    $result = $progression->save($userId, $name, $chords, $isPublic);
    
    if ($result['status'] === 'success') {
        http_response_code(201); // Created
    } else {
        http_response_code(400);
    }
    
    echo json_encode($result);
    exit;
}

// Handle PUT request - update an existing progression
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Get JSON input
    $input = json_decode(file_get_contents('php://input'), true);
    
    // Validate input
    if (!isset($input['id']) || !isset($input['name']) || !isset($input['chords'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid input. ID, name, and chords are required.'
        ]);
        exit;
    }
    
    // Sanitize input
    $id = (int)$input['id'];
    $name = sanitizeInput($input['name']);
    $chords = $input['chords']; // Array of chord names
    $isPublic = isset($input['is_public']) ? (bool)$input['is_public'] : false;
    
    // Update the progression
    $result = $progression->update($id, $userId, $name, $chords, $isPublic);
    
    if ($result['status'] === 'success') {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    
    echo json_encode($result);
    exit;
}

// Handle DELETE request - delete a progression
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Check if an ID is provided
    if (!isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'status' => 'error',
            'message' => 'Progression ID is required'
        ]);
        exit;
    }
    
    $id = (int)$_GET['id'];
    
    // Delete the progression
    $result = $progression->delete($id, $userId);
    
    if ($result['status'] === 'success') {
        http_response_code(200);
    } else {
        http_response_code(400);
    }
    
    echo json_encode($result);
    exit;
}

// If we get here, the request method is not supported
http_response_code(405);
echo json_encode([
    'status' => 'error',
    'message' => 'Method not allowed'
]); 