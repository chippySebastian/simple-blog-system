<?php

namespace App\Services;

use App\Models\Comment;

/**
 * CommentService
 * 
 * Service for managing comments
 */
class CommentService
{
    private $commentModel;
    
    public function __construct()
    {
        $this->commentModel = new Comment();
    }
    
    public function find($id)
    {
        return $this->commentModel->find($id);
    }
    
    public function getAll()
    {
        return $this->commentModel->getAll('created_at', 'DESC');
    }
    
    public function getByPost($postId, $status = 'approved')
    {
        return $this->commentModel->getByPost($postId, $status);
    }
    
    public function getReplies($commentId, $status = 'approved')
    {
        return $this->commentModel->getReplies($commentId, $status);
    }
    
    public function getByUser($userId, $limit = null)
    {
        return $this->commentModel->getByUser($userId, $limit);
    }
    
    public function getRecent($limit = 5)
    {
        return $this->commentModel->getRecent($limit);
    }
    
    public function getPending($limit = null)
    {
        return $this->commentModel->getPending($limit);
    }
    
    public function getByStatus($status, $limit = null)
    {
        return $this->commentModel->getByStatus($status, $limit);
    }
    
    public function create($data)
    {
        // Set default status if not provided
        if (!isset($data['status'])) {
            $data['status'] = 'approved'; // or 'pending' for moderation
        }
        
        return $this->commentModel->create($data);
    }
    
    public function update($id, $data)
    {
        return $this->commentModel->update($id, $data);
    }
    
    public function delete($id)
    {
        return $this->commentModel->delete($id);
    }
    
    public function approve($id)
    {
        return $this->commentModel->approve($id);
    }
    
    public function reject($id)
    {
        return $this->commentModel->reject($id);
    }
    
    public function countByPost($postId, $status = 'approved')
    {
        return $this->commentModel->countByPost($postId, $status);
    }
    
    public function count()
    {
        return $this->commentModel->count();
    }
}
