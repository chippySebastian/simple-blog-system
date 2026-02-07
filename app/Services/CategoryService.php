<?php

namespace App\Services;

use App\Models\Category;

/**
 * CategoryService
 * 
 * Service for managing categories
 */
class CategoryService
{
    private $categoryModel;
    
    public function __construct()
    {
        $this->categoryModel = new Category();
    }
    
    public function find($id)
    {
        return $this->categoryModel->find($id);
    }
    
    public function findBySlug($slug)
    {
        return $this->categoryModel->findBySlug($slug);
    }
    
    public function getAll()
    {
        return $this->categoryModel->getAll('name', 'ASC');
    }
    
    public function getWithPostCount()
    {
        return $this->categoryModel->getWithPostCount();
    }
    
    public function getPostCount($categoryId)
    {
        return $this->categoryModel->getPostCount($categoryId);
    }
    
    public function getPosts($categoryId, $limit = null)
    {
        return $this->categoryModel->getPosts($categoryId, $limit);
    }
    
    public function create($data)
    {
        // Generate slug if not provided
        if (!isset($data['slug']) && isset($data['name'])) {
            $data['slug'] = $this->generateSlug($data['name']);
        }
        
        return $this->categoryModel->create($data);
    }
    
    public function update($id, $data)
    {
        return $this->categoryModel->update($id, $data);
    }
    
    public function delete($id)
    {
        return $this->categoryModel->delete($id);
    }
    
    public function count()
    {
        return $this->categoryModel->count();
    }
    
    private function generateSlug($name)
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));
    }
}
