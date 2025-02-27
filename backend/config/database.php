<?php
/**
 * Database Configuration
 * 
 * This file contains the database connection parameters
 * It uses environment variables from .env file when available
 */

// Load environment variables from .env file if it exists
$envFile = __DIR__ . '/../../.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) {
            continue;
        }
        
        // Parse the line
        if (strpos($line, '=') !== false) {
            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);
            
            // Skip if it's not a database variable
            if (strpos($name, 'VITE_DB_') !== 0) {
                continue;
            }
            
            // Set as environment variable
            putenv("$name=$value");
            $_ENV[$name] = $value;
            $_SERVER[$name] = $value;
        }
    }
}

// Database credentials - use environment variables if available
$dbHostWithPort = getenv('VITE_DB_HOST') ?: 'localhost';

// Extract host and port if port is specified
$dbPort = null;
$dbHost = $dbHostWithPort;
if (strpos($dbHostWithPort, ':') !== false) {
    list($dbHost, $dbPort) = explode(':', $dbHostWithPort, 2);
}

// Check for socket path
$dbSocket = getenv('VITE_DB_SOCKET') ?: null;

// Common MySQL socket paths for MAMP, XAMPP, etc.
$commonSocketPaths = [
    '/Applications/MAMP/tmp/mysql/mysql.sock',
    '/tmp/mysql.sock',
    '/var/run/mysqld/mysqld.sock'
];

define('DB_HOST', $dbHost);
define('DB_PORT', $dbPort);
define('DB_SOCKET', $dbSocket);
define('DB_NAME', getenv('VITE_DB_NAME') ?: 'chord_dictionary');
define('DB_USER', getenv('VITE_DB_USER') ?: 'root');
define('DB_PASS', getenv('VITE_DB_PASS') ?: '');

// Define a debug mode constant
define('DB_DEBUG', getenv('VITE_APP_ENV') === 'development');

/**
 * Get database connection
 * 
 * @param bool $showDebugInfo Whether to show debug information on error
 * @return PDO Database connection object
 */
function getDbConnection($showDebugInfo = false) {
    $lastError = null;
    
    // Try socket connection first if on localhost
    if (DB_HOST === 'localhost' || DB_HOST === '127.0.0.1') {
        // Try with specified socket if available
        if (defined('DB_SOCKET') && DB_SOCKET) {
            try {
                $dsn = "mysql:unix_socket=" . DB_SOCKET . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                return new PDO($dsn, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                $lastError = $e;
                // Fall through to try common socket paths
            }
        }
        
        // Try common socket paths
        global $commonSocketPaths;
        foreach ($commonSocketPaths as $socketPath) {
            if (file_exists($socketPath)) {
                try {
                    $dsn = "mysql:unix_socket=" . $socketPath . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                    $options = [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                    ];
                    
                    return new PDO($dsn, DB_USER, DB_PASS, $options);
                } catch (PDOException $e) {
                    $lastError = $e;
                    // Continue to next socket path
                }
            }
        }
    }
    
    // Fall back to TCP connection
    try {
        // Build DSN with port if specified
        $dsn = "mysql:host=" . DB_HOST;
        
        // Add port if specified
        if (defined('DB_PORT') && DB_PORT) {
            $dsn .= ";port=" . DB_PORT;
        }
        
        // Add database name and charset
        $dsn .= ";dbname=" . DB_NAME . ";charset=utf8mb4";
        
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        // Create PDO instance
        return new PDO($dsn, DB_USER, DB_PASS, $options);
    } catch (PDOException $e) {
        // Use the last error from socket connection if available
        $error = $lastError ?: $e;
        
        // In development mode or if debug info is requested, show detailed error
        if (DB_DEBUG || $showDebugInfo) {
            $errorDetails = [
                'message' => 'Database Connection Error: ' . $error->getMessage(),
                'code' => $error->getCode(),
                'config' => [
                    'host' => DB_HOST,
                    'port' => defined('DB_PORT') ? DB_PORT : 'default',
                    'socket' => defined('DB_SOCKET') ? DB_SOCKET : 'not_set',
                    'tried_sockets' => $commonSocketPaths,
                    'database' => DB_NAME,
                    'user' => DB_USER,
                    'dsn' => isset($dsn) ? $dsn : 'not_set'
                ]
            ];
            
            // If this is an API request, return JSON
            if (strpos($_SERVER['REQUEST_URI'] ?? '', '/api/') !== false) {
                header('Content-Type: application/json');
                die(json_encode([
                    'status' => 'error',
                    'message' => 'Database connection failed',
                    'details' => $errorDetails
                ]));
            }
            
            // Otherwise, display as text
            die('<pre>' . print_r($errorDetails, true) . '</pre>');
        }
        
        // In production, show a generic error
        die('Database Connection Error: Unable to connect to the database. Please try again later.');
    }
} 