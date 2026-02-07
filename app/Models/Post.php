<?php

namespace App\Models;

/**
 * Post Model
 */
class Post extends BaseModel
{
    protected $table = 'posts';
    
    /**
     * Get all posts (admin view)
     */
    public function getAll($orderBy = 'created_at', $direction = 'DESC')
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.{$orderBy} {$direction}";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get published posts
     */
    public function getPublished($limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'published'
                ORDER BY p.published_at DESC, p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit} OFFSET {$offset}";
        }
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Find post by slug
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name, u.avatar, c.name as category_name, c.slug as category_slug
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.slug = ?";
        
        $stmt = $this->query($sql, [$slug]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
    
    /**
     * Get posts by category
     */
    public function getByCategory($categoryId, $limit = null)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.category_id = ? AND p.status = 'published'
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, [$categoryId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get posts by user
     */
    public function getByUser($userId, $status = null, $limit = null)
    {
        $sql = "SELECT p.*, c.name as category_name
                FROM posts p
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.user_id = ?";
        
        $params = [$userId];
        
        if ($status) {
            $sql .= " AND p.status = ?";
            $params[] = $status;
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get posts by status
     */
    public function getByStatus($status)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = ?
                ORDER BY p.created_at DESC";
        
        $stmt = $this->query($sql, [$status]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Search posts
     */
    public function search($query, $limit = null)
    {
        $searchTerm = "%{$query}%";
        
        $sql = "SELECT p.*, u.username, u.full_name as author_name, c.name as category_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                WHERE p.status = 'published'
                AND (p.title ILIKE ? OR p.content ILIKE ? OR p.excerpt ILIKE ?)
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, [$searchTerm, $searchTerm, $searchTerm]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Increment view count
     */
    public function incrementViews($postId)
    {
        $sql = "UPDATE posts SET views = views + 1 WHERE id = ?";
        $stmt = $this->query($sql, [$postId]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Get recent posts
     */
    public function getRecent($limit = 5)
    {
        return $this->getPublished($limit);
    }
    
    /**
     * Get popular posts (by views)
     */
    public function getPopular($limit = 5)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.status = 'published'
                ORDER BY p.views DESC
                LIMIT {$limit}";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
