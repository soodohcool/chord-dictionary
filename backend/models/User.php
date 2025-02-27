<?php
/**
 * User Model
 * 
 * Handles all user-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class User {
    private $conn;
    
    /**
     * Constructor - initializes database connection
     */
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    /**
     * Register a new user
     * 
     * @param string $username User's username
     * @param string $email User's email
     * @param string $password User's password (plain text)
     * @return array Response with status and message
     */
    public function register($username, $email, $password) {
        try {
            // Check if username already exists
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE username = ?");
            $stmt->execute([$username]);
            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Username already exists'
                ];
            }
            
            // Check if email already exists
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            if ($stmt->rowCount() > 0) {
                return [
                    'status' => 'error',
                    'message' => 'Email already exists'
                ];
            }
            
            // Hash the password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert the new user
            $stmt = $this->conn->prepare("
                INSERT INTO users (username, email, password) 
                VALUES (?, ?, ?)
            ");
            
            $stmt->execute([$username, $email, $hashedPassword]);
            
            return [
                'status' => 'success',
                'message' => 'User registered successfully',
                'user_id' => $this->conn->lastInsertId()
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Registration failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Login a user
     * 
     * @param string $username Username or email
     * @param string $password Plain text password
     * @param bool $remember Whether to remember the user
     * @return array Response with status and user data if successful
     */
    public function login($username, $password, $remember = false) {
        try {
            // Check if input is email or username
            $field = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
            
            // Get user by username or email
            $stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE $field = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ];
            }
            
            $user = $stmt->fetch();
            
            // Verify password
            if (!password_verify($password, $user['password'])) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid credentials'
                ];
            }
            
            // Update last login time
            $stmt = $this->conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
            $stmt->execute([$user['id']]);
            
            // Create session token if remember me is checked
            $token = null;
            if ($remember) {
                $token = $this->createRememberToken($user['id']);
            }
            
            // Remove password from response
            unset($user['password']);
            
            return [
                'status' => 'success',
                'message' => 'Login successful',
                'user' => $user,
                'remember_token' => $token
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Login failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Create a remember me token
     * 
     * @param int $userId User ID
     * @return string|null Token or null on failure
     */
    private function createRememberToken($userId) {
        try {
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            
            // Set expiration to 30 days from now
            $expiresAt = date('Y-m-d H:i:s', strtotime('+30 days'));
            
            // Delete any existing tokens for this user
            $stmt = $this->conn->prepare("DELETE FROM user_sessions WHERE user_id = ?");
            $stmt->execute([$userId]);
            
            // Insert new token
            $stmt = $this->conn->prepare("
                INSERT INTO user_sessions (user_id, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            
            $stmt->execute([$userId, $token, $expiresAt]);
            
            return $token;
            
        } catch (Exception $e) {
            return null;
        }
    }
    
    /**
     * Verify a remember me token
     * 
     * @param string $token Remember me token
     * @return array|null User data or null if invalid
     */
    public function verifyRememberToken($token) {
        try {
            $stmt = $this->conn->prepare("
                SELECT u.id, u.username, u.email 
                FROM user_sessions s
                JOIN users u ON s.user_id = u.id
                WHERE s.token = ? AND s.expires_at > NOW()
            ");
            
            $stmt->execute([$token]);
            
            if ($stmt->rowCount() === 0) {
                return null;
            }
            
            return $stmt->fetch();
            
        } catch (PDOException $e) {
            return null;
        }
    }
    
    /**
     * Logout a user by removing their remember me token
     * 
     * @param string $token Remember me token
     * @return bool Success status
     */
    public function logout($token) {
        try {
            $stmt = $this->conn->prepare("DELETE FROM user_sessions WHERE token = ?");
            $stmt->execute([$token]);
            
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }
    
    /**
     * Request a password reset
     * 
     * @param string $email User's email
     * @return array Response with status and message
     */
    public function requestPasswordReset($email) {
        try {
            // Check if email exists
            $stmt = $this->conn->prepare("SELECT id FROM users WHERE email = ?");
            $stmt->execute([$email]);
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Email not found'
                ];
            }
            
            // Generate a secure random token
            $token = bin2hex(random_bytes(32));
            
            // Set expiration to 1 hour from now
            $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
            
            // Delete any existing tokens for this email
            $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);
            
            // Insert new token
            $stmt = $this->conn->prepare("
                INSERT INTO password_resets (email, token, expires_at) 
                VALUES (?, ?, ?)
            ");
            
            $stmt->execute([$email, $token, $expiresAt]);
            
            return [
                'status' => 'success',
                'message' => 'Password reset link sent',
                'token' => $token // In production, you would email this token instead of returning it
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Password reset request failed: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Reset a password using a token
     * 
     * @param string $token Reset token
     * @param string $password New password
     * @return array Response with status and message
     */
    public function resetPassword($token, $password) {
        try {
            // Check if token exists and is valid
            $stmt = $this->conn->prepare("
                SELECT email FROM password_resets 
                WHERE token = ? AND expires_at > NOW()
            ");
            
            $stmt->execute([$token]);
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Invalid or expired token'
                ];
            }
            
            $email = $stmt->fetchColumn();
            
            // Hash the new password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Update the user's password
            $stmt = $this->conn->prepare("UPDATE users SET password = ? WHERE email = ?");
            $stmt->execute([$hashedPassword, $email]);
            
            // Delete the used token
            $stmt = $this->conn->prepare("DELETE FROM password_resets WHERE token = ?");
            $stmt->execute([$token]);
            
            return [
                'status' => 'success',
                'message' => 'Password reset successful'
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Password reset failed: ' . $e->getMessage()
            ];
        }
    }
} 