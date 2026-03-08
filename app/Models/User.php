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

    /**
     * Find user by valid reset token (not expired)
     */
    public function findByValidResetToken($token)
    {
        $sql = "SELECT * FROM users 
                WHERE reset_token = ? 
                AND reset_token_expires IS NOT NULL
                AND reset_token_expires > (CURRENT_TIMESTAMP AT TIME ZONE 'UTC')
                LIMIT 1";

        $stmt = $this->query($sql, [$token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Set password reset token and expiry
     */
    public function setResetToken($userId, $token, $expiresAt)
    {
        return $this->update($userId, [
            'reset_token' => $token,
            'reset_token_expires' => $expiresAt
        ]);
    }

    /**
     * Clear password reset token fields
     */
    public function clearResetToken($userId)
    {
        return $this->update($userId, [
            'reset_token' => null,
            'reset_token_expires' => null
        ]);
    }

    /**
     * Set email verification token and expiry
     */
    public function setEmailVerificationToken($userId, $token, $expiresAt)
    {
        return $this->update($userId, [
            'email_verification_token' => $token,
            'email_verification_expires' => $expiresAt
        ]);
    }

    /**
     * Find user by valid email verification token
     */
    public function findByValidEmailVerificationToken($token)
    {
        $sql = "SELECT * FROM users
                WHERE email_verification_token = ?
                AND email_verification_expires IS NOT NULL
                AND email_verification_expires > (CURRENT_TIMESTAMP AT TIME ZONE 'UTC')
                LIMIT 1";

        $stmt = $this->query($sql, [$token]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Mark user email as verified and clear verification token
     */
    public function markEmailVerified($userId)
    {
        return $this->update($userId, [
            'email_verified' => true,
            'email_verification_token' => null,
            'email_verification_expires' => null
        ]);
    }
}
