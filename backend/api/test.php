<?php
/**
 * Simple test endpoint
 * Returns a JSON response to verify the API is working
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Return a simple response
echo json_encode([
    'status' => 'success',
    'message' => 'API is working',
    'timestamp' => time(),
    'php_version' => phpversion()
]); 