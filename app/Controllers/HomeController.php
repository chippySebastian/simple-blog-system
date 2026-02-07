<?php

namespace App\Controllers;

use App\Services\PostService;
use App\Services\UserService;
use App\Services\CategoryService;

/**
 * HomeController
 * 
 * Handles home page and public routes
 */
class HomeController extends BaseController
{
    private $postService;
    private $userService;
    private $categoryService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->categoryService = new CategoryService();
    }

    /**
     * Display home page
     */
    public function index()
    {
        // Get recent posts (already includes author info from JOIN)
        $recentPosts = $this->postService->getPublished(6);

        $categories = $this->categoryService->getAll();

        echo $this->render('home', [
            'recentPosts' => $recentPosts,
            'categories' => $categories,
            'isAuthenticated' => $this->isAuthenticated()
        ]);
    }

    /**
     * Display health check
     */
    public function health()
    {
        return $this->json([
            'status' => 'success',
            'message' => 'Application is running',
            'timestamp' => date('Y-m-d H:i:s')
        ]);
    }
}
