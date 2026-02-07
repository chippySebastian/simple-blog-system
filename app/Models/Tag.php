<?php

namespace App\Models;

/**
 * Tag Model
 */
class Tag extends BaseModel
{
    protected $table = 'tags';
    
    /**
     * Find tag by slug
     */
    public function findBySlug($slug)
    {
        return $this->findBy('slug', $slug);
    }
    
    /**
     * Get posts by tag
     */
    public function getPosts($tagId, $limit = null)
    {
        $sql = "SELECT p.*, u.username, u.full_name as author_name
                FROM posts p
                INNER JOIN post_tags pt ON p.id = pt.post_id
                LEFT JOIN users u ON p.user_id = u.id
                WHERE pt.tag_id = ? AND p.status = 'published'
                ORDER BY p.published_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $this->query($sql, [$tagId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Get tags for a post
     */
    public function getByPost($postId)
    {
        $sql = "SELECT t.* FROM tags t
                INNER JOIN post_tags pt ON t.id = pt.tag_id
                WHERE pt.post_id = ?
                ORDER BY t.name";
        
        $stmt = $this->query($sql, [$postId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Attach tag to post
     */
    public function attachToPost($tagId, $postId)
    {
        $sql = "INSERT INTO post_tags (post_id, tag_id) VALUES (?, ?) 
                ON CONFLICT DO NOTHING";
        $stmt = $this->query($sql, [$postId, $tagId]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Detach tag from post
     */
    public function detachFromPost($tagId, $postId)
    {
        $sql = "DELETE FROM post_tags WHERE post_id = ? AND tag_id = ?";
        $stmt = $this->query($sql, [$postId, $tagId]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Sync tags for a post
     */
    public function syncToPost($postId, array $tagIds)
    {
        // Remove all existing tags
        $sql = "DELETE FROM post_tags WHERE post_id = ?";
        $this->query($sql, [$postId]);
        
        // Add new tags
        if (!empty($tagIds)) {
            foreach ($tagIds as $tagId) {
                $this->attachToPost($tagId, $postId);
            }
        }
        
        return true;
    }
}
