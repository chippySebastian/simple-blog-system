<?php

namespace App\Models;

/**
 * User Model
 */
class User extends BaseModel
{
    protected $table = 'users';
    
    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }
    
    /**
     * Find user by username
     */
    public function findByUsername($username)
    {
        return $this->findBy('username', $username);
    }
    
    /**
     * Get user's posts
     */
    public function getPosts($userId, $limit = null)
    {
        $sql = "SELECT * FROM posts WHERE user_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get user's comments
     */
    public function getComments($userId, $limit = null)
    {
        $sql = "SELECT * FROM comments WHERE user_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin($userId)
    {
        $user = $this->find($userId);
        return $user && $user['role'] === 'admin';
    }
    
    /**
     * Verify password
     */
    public function verifyPassword($user, $password)
    {
        return password_verify($password, $user['password']);
    }
    
    /**
     * Update password
     */
    public function updatePassword($userId, $newPassword)
    {
        return $this->update($userId, [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ]);
    }
    
    /**
     * Get all authors (users who have published posts)
     */
    public function getAuthors()
    {
        $sql = "SELECT DISTINCT u.* FROM users u
                INNER JOIN posts p ON u.id = p.user_id
                WHERE p.status = 'published'
                ORDER BY u.full_name";
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Find by reset token
     */
    public function findByResetToken($token)
    {
        return $this->findBy('reset_token', $token);
    }
}
