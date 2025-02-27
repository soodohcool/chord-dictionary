<?php
/**
 * Database Connection Test
 * 
 * This script tests the database connection and displays the connection details
 */

// Set headers
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Include database configuration
require_once __DIR__ . '/../config/database.php';

// Prepare response
$response = [
    'status' => 'pending',
    'message' => 'Testing database connection...',
    'config' => [
        'host' => DB_HOST,
        'port' => defined('DB_PORT') ? DB_PORT : 'default',
        'database' => DB_NAME,
        'user' => DB_USER,
        // Not including password for security reasons
    ],
    'env_vars' => [
        'VITE_DB_HOST' => getenv('VITE_DB_HOST'),
        'VITE_DB_NAME' => getenv('VITE_DB_NAME'),
        'VITE_DB_USER' => getenv('VITE_DB_USER'),
        'VITE_APP_ENV' => getenv('VITE_APP_ENV'),
    ],
    'timestamp' => date('Y-m-d H:i:s')
];

try {
    // Attempt to connect to the database with debug info enabled
    $conn = getDbConnection(true);
    
    // Test query
    $stmt = $conn->query('SELECT VERSION() as version');
    $dbVersion = $stmt->fetch()['version'];
    
    // Update response with success
    $response['status'] = 'success';
    $response['message'] = 'Database connection successful';
    $response['db_version'] = $dbVersion;
    
    // Get server info
    $response['server_info'] = [
        'php_version' => phpversion(),
        'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
        'pdo_drivers' => PDO::getAvailableDrivers(),
    ];
    
} catch (PDOException $e) {
    // Update response with error
    $response['status'] = 'error';
    $response['message'] = 'Database connection failed';
    $response['error'] = [
        'code' => $e->getCode(),
        'message' => $e->getMessage(),
    ];
}

// Output response
echo json_encode($response, JSON_PRETTY_PRINT); 