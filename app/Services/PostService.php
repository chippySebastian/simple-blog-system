<?php

namespace App\Services;

use App\Models\Post;

/**
 * PostService
 * 
 * Service for managing blog posts
 */
class PostService
{
    private $postModel;
    
    public function __construct()
    {
        $this->postModel = new Post();
    }
    
    public function find($id)
    {
        return $this->postModel->find($id);
    }
    
    public function findBySlug($slug)
    {
        return $this->postModel->findBySlug($slug);
    }
    
    public function getAll()
    {
        return $this->postModel->getAll('created_at', 'DESC');
    }
    
    public function getPublished($limit = null, $offset = 0)
    {
        return $this->postModel->getPublished($limit, $offset);
    }
    
    public function getByCategory($categoryId, $limit = null)
    {
        return $this->postModel->getByCategory($categoryId, $limit);
    }
    
    public function getByUser($userId, $status = null, $limit = null)
    {
        return $this->postModel->getByUser($userId, $status, $limit);
    }
    
    public function getByStatus($status)
    {
        return $this->postModel->getByStatus($status);
    }
    
    public function search($query, $limit = null)
    {
        return $this->postModel->search($query, $limit);
    }
    
    public function create($data)
    {
        // Set published_at if status is published and not set
        if ($data['status'] === 'published' && !isset($data['published_at'])) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }
        
        return $this->postModel->create($data);
    }
    
    public function update($id, $data)
    {
        // Update published_at if changing to published
        if (isset($data['status']) && $data['status'] === 'published') {
            $post = $this->find($id);
            if ($post && $post['status'] !== 'published' && !isset($data['published_at'])) {
                $data['published_at'] = date('Y-m-d H:i:s');
            }
        }
        
        return $this->postModel->update($id, $data);
    }
    
    public function delete($id)
    {
        return $this->postModel->delete($id);
    }
    
    public function incrementViews($id)
    {
        return $this->postModel->incrementViews($id);
    }
    
    public function getRecent($limit = 5)
    {
        return $this->postModel->getRecent($limit);
    }
    
    public function getPopular($limit = 5)
    {
        return $this->postModel->getPopular($limit);
    }
    
    public function count($conditions = [])
    {
        return $this->postModel->count($conditions);
    }
}
