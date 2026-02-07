<?php

namespace App\Services;

use App\Models\User;

/**
 * UserService
 * 
 * Service for managing users
 */
class UserService
{
    private $userModel;
    
    public function __construct()
    {
        $this->userModel = new User();
    }
    
    public function find($id)
    {
        return $this->userModel->find($id);
    }
    
    public function getAll()
    {
        return $this->userModel->getAll('created_at', 'DESC');
    }
    
    public function findByEmail($email)
    {
        return $this->userModel->findByEmail($email);
    }
    
    public function findByUsername($username)
    {
        return $this->userModel->findByUsername($username);
    }
    
    public function create($data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default values
        if (!isset($data['status'])) {
            $data['status'] = 'active';
        }
        if (!isset($data['role'])) {
            $data['role'] = 'user';
        }
        if (!isset($data['email_verified'])) {
            $data['email_verified'] = false;
        }
        
        return $this->userModel->create($data);
    }
    
    public function update($id, $data)
    {
        return $this->userModel->update($id, $data);
    }
    
    public function delete($id)
    {
        return $this->userModel->delete($id);
    }
    
    public function verifyPassword($user, $password)
    {
        return $this->userModel->verifyPassword($user, $password);
    }
    
    public function updatePassword($id, $newPassword)
    {
        return $this->userModel->updatePassword($id, $newPassword);
    }
    
    public function isAdmin($userId)
    {
        return $this->userModel->isAdmin($userId);
    }
    
    public function getAuthors()
    {
        return $this->userModel->getAuthors();
    }
    
    public function getUserPosts($userId, $limit = null)
    {
        return $this->userModel->getPosts($userId, $limit);
    }
    
    public function count()
    {
        return $this->userModel->count();
    }
}
