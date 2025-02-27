<?php
/**
 * Progression Model
 * 
 * Handles all chord progression-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Progression {
    private $conn;
    
    /**
     * Constructor - initializes database connection
     */
    public function __construct() {
        $this->conn = getDbConnection();
    }
    
    /**
     * Save a chord progression
     * 
     * @param int $userId User ID
     * @param string $name Progression name
     * @param array $chords Array of chord names
     * @param bool $isPublic Whether the progression is public
     * @return array Response with status and message
     */
    public function save($userId, $name, $chords, $isPublic = false) {
        try {
            // Convert chords array to JSON string
            $chordsJson = json_encode($chords);
            
            $stmt = $this->conn->prepare("
                INSERT INTO saved_progressions (user_id, name, chords, is_public) 
                VALUES (?, ?, ?, ?)
            ");
            
            $stmt->execute([$userId, $name, $chordsJson, $isPublic ? 1 : 0]);
            
            return [
                'status' => 'success',
                'message' => 'Progression saved successfully',
                'progression_id' => $this->conn->lastInsertId()
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to save progression: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Update a chord progression
     * 
     * @param int $progressionId Progression ID
     * @param int $userId User ID (for verification)
     * @param string $name Progression name
     * @param array $chords Array of chord names
     * @param bool $isPublic Whether the progression is public
     * @return array Response with status and message
     */
    public function update($progressionId, $userId, $name, $chords, $isPublic = false) {
        try {
            // Check if progression exists and belongs to user
            $stmt = $this->conn->prepare("
                SELECT id FROM saved_progressions 
                WHERE id = ? AND user_id = ?
            ");
            
            $stmt->execute([$progressionId, $userId]);
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Progression not found or access denied'
                ];
            }
            
            // Convert chords array to JSON string
            $chordsJson = json_encode($chords);
            
            $stmt = $this->conn->prepare("
                UPDATE saved_progressions 
                SET name = ?, chords = ?, is_public = ? 
                WHERE id = ?
            ");
            
            $stmt->execute([$name, $chordsJson, $isPublic ? 1 : 0, $progressionId]);
            
            return [
                'status' => 'success',
                'message' => 'Progression updated successfully'
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to update progression: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Delete a chord progression
     * 
     * @param int $progressionId Progression ID
     * @param int $userId User ID (for verification)
     * @return array Response with status and message
     */
    public function delete($progressionId, $userId) {
        try {
            // Check if progression exists and belongs to user
            $stmt = $this->conn->prepare("
                SELECT id FROM saved_progressions 
                WHERE id = ? AND user_id = ?
            ");
            
            $stmt->execute([$progressionId, $userId]);
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Progression not found or access denied'
                ];
            }
            
            $stmt = $this->conn->prepare("DELETE FROM saved_progressions WHERE id = ?");
            $stmt->execute([$progressionId]);
            
            return [
                'status' => 'success',
                'message' => 'Progression deleted successfully'
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to delete progression: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get a user's saved progressions
     * 
     * @param int $userId User ID
     * @return array List of progressions or error
     */
    public function getUserProgressions($userId) {
        try {
            $stmt = $this->conn->prepare("
                SELECT id, name, chords, is_public, created_at, updated_at 
                FROM saved_progressions 
                WHERE user_id = ? 
                ORDER BY updated_at DESC
            ");
            
            $stmt->execute([$userId]);
            
            $progressions = $stmt->fetchAll();
            
            // Parse JSON chord data
            foreach ($progressions as &$progression) {
                $progression['chords'] = json_decode($progression['chords'], true);
                $progression['is_public'] = (bool)$progression['is_public'];
            }
            
            return [
                'status' => 'success',
                'progressions' => $progressions
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to retrieve progressions: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get public progressions
     * 
     * @param int $limit Maximum number of progressions to return
     * @param int $offset Offset for pagination
     * @return array List of public progressions or error
     */
    public function getPublicProgressions($limit = 20, $offset = 0) {
        try {
            $stmt = $this->conn->prepare("
                SELECT p.id, p.name, p.chords, p.created_at, p.updated_at, u.username as created_by
                FROM saved_progressions p
                JOIN users u ON p.user_id = u.id
                WHERE p.is_public = 1
                ORDER BY p.updated_at DESC
                LIMIT ? OFFSET ?
            ");
            
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->bindParam(2, $offset, PDO::PARAM_INT);
            $stmt->execute();
            
            $progressions = $stmt->fetchAll();
            
            // Parse JSON chord data
            foreach ($progressions as &$progression) {
                $progression['chords'] = json_decode($progression['chords'], true);
            }
            
            return [
                'status' => 'success',
                'progressions' => $progressions
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to retrieve public progressions: ' . $e->getMessage()
            ];
        }
    }
    
    /**
     * Get a specific progression by ID
     * 
     * @param int $progressionId Progression ID
     * @param int $userId Optional user ID for access control
     * @return array Progression data or error
     */
    public function getProgression($progressionId, $userId = null) {
        try {
            // If user ID is provided, check if progression belongs to user or is public
            if ($userId) {
                $stmt = $this->conn->prepare("
                    SELECT p.id, p.name, p.chords, p.is_public, p.created_at, p.updated_at, u.username as created_by
                    FROM saved_progressions p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = ? AND (p.user_id = ? OR p.is_public = 1)
                ");
                
                $stmt->execute([$progressionId, $userId]);
            } else {
                // If no user ID, only return public progressions
                $stmt = $this->conn->prepare("
                    SELECT p.id, p.name, p.chords, p.is_public, p.created_at, p.updated_at, u.username as created_by
                    FROM saved_progressions p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = ? AND p.is_public = 1
                ");
                
                $stmt->execute([$progressionId]);
            }
            
            if ($stmt->rowCount() === 0) {
                return [
                    'status' => 'error',
                    'message' => 'Progression not found or access denied'
                ];
            }
            
            $progression = $stmt->fetch();
            $progression['chords'] = json_decode($progression['chords'], true);
            $progression['is_public'] = (bool)$progression['is_public'];
            
            return [
                'status' => 'success',
                'progression' => $progression
            ];
            
        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to retrieve progression: ' . $e->getMessage()
            ];
        }
    }
} 