<?php

namespace App\Models;

/**
 * Comment Model
 */
class Comment extends BaseModel
{
    protected $table = 'comments';
    
    /**
     * Get comments for a post
     */
    public function getByPost($postId, $status = 'approved')
    {
        $sql = "SELECT c.*, u.username, u.full_name as author_name, u.avatar
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                WHERE c.post_id = ? AND c.status = ?
                ORDER BY c.created_at ASC";
        
        $stmt = $this->query($sql, [$postId, $status]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get comments by user
     */
    public function getByUser($userId, $limit = null)
    {
        $sql = "SELECT c.*, p.title as post_title, p.slug as post_slug
                FROM comments c
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.user_id = ?
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, [$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get recent comments
     */
    public function getRecent($limit = 5)
    {
        $sql = "SELECT c.*, u.username, u.full_name as author_name, p.title as post_title, p.slug as post_slug
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'approved'
                ORDER BY c.created_at DESC
                LIMIT {$limit}";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get pending comments (for moderation)
     */
    public function getPending($limit = null)
    {
        $sql = "SELECT c.*, u.username, u.full_name as author_name, p.title as post_title
                FROM comments c
                LEFT JOIN users u ON c.user_id = u.id
                LEFT JOIN posts p ON c.post_id = p.id
                WHERE c.status = 'pending'
                ORDER BY c.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Count comments by post
     */
    public function countByPost($postId, $status = 'approved')
    {
        return $this->count(['post_id' => $postId, 'status' => $status]);
    }
    
    /**
     * Approve comment
     */
    public function approve($commentId)
    {
        return $this->update($commentId, ['status' => 'approved']);
    }
    
    /**
     * Reject comment
     */
    public function reject($commentId)
    {
        return $this->update($commentId, ['status' => 'rejected']);
    }
}
