<?php

namespace App\Controllers;

use App\Services\CategoryService;
use App\Services\PostService;
use App\Helpers\AuthHelper;

/**
 * CategoryController
 * 
 * Handles category operations
 */
class CategoryController extends BaseController
{
    private $categoryService;
    private $postService;

    public function __construct()
    {
        $this->categoryService = new CategoryService();
        $this->postService = new PostService();
    }

    /**
     * List all categories
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();
        
        // Add post count to each category
        foreach ($categories as &$category) {
            $category['post_count'] = $this->categoryService->getPostCount($category['id']);
        }

        echo $this->render('categories.index', ['categories' => $categories]);
    }

    /**
     * Show posts by category
     */
    public function show($id)
    {
        $category = $this->categoryService->find($id);

        if (!$category) {
            $this->setFlash('error', 'Category not found');
            $this->redirect('/categories');
        }

        $posts = $this->postService->getByCategory($id);
        
        // Filter only published posts for non-admin
        if (!$this->isAdmin()) {
            $posts = array_filter($posts, fn($p) => $p['status'] === 'published');
        }

        echo $this->render('categories.show', [
            'category' => $category,
            'posts' => $posts
        ]);
    }
}
