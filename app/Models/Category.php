<?php

namespace App\Models;

/**
 * Category Model
 */
class Category extends BaseModel
{
    protected $table = 'categories';
    
    /**
     * Find category by slug
     */
    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get category with post count
     */
    public function getWithPostCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as post_count
                FROM categories c
                LEFT JOIN posts p ON c.id = p.category_id AND p.status = 'published'
                GROUP BY c.id
                ORDER BY c.name";
        
        $stmt = $this->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get category posts
     */
    public function getPosts($categoryId, $limit = null)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.category_id = ? AND p.status = 'published'
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, [$categoryId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
