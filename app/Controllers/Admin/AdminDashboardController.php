<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PostService;
use App\Services\UserService;
use App\Services\CommentService;
use App\Services\CategoryService;
use App\Helpers\AuthHelper;

/**
 * AdminDashboardController
 * 
 * Admin dashboard with statistics
 */
class AdminDashboardController extends BaseController
{
    private $postService;
    private $userService;
    private $commentService;
    private $categoryService;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->commentService = new CommentService();
        $this->categoryService = new CategoryService();
    }

    /**
     * Show dashboard
     */
    public function index()
    {
        $stats = [
            'total_posts' => count($this->postService->all()),
            'published_posts' => count($this->postService->getPublished()),
            'draft_posts' => count($this->postService->where('status', 'draft')),
            'total_users' => count($this->userService->all()),
            'total_comments' => count($this->commentService->all()),
            'pending_comments' => count($this->commentService->getByStatus('pending')),
            'total_categories' => count($this->categoryService->all())
        ];

        // Recent posts
        $recentPosts = array_slice($this->postService->all(), 0, 5);
        foreach ($recentPosts as &$post) {
            $post['author'] = $this->userService->find($post['author_id']);
        }

        // Recent comments
        $recentComments = array_slice($this->commentService->all(), 0, 5);
        foreach ($recentComments as &$comment) {
            $comment['user'] = $this->userService->find($comment['user_id']);
            $comment['post'] = $this->postService->find($comment['post_id']);
        }

        // Recent users
        $recentUsers = array_slice($this->userService->all(), 0, 5);

        echo $this->render('admin.dashboard', [
            'stats' => $stats,
            'recentPosts' => $recentPosts,
            'recentComments' => $recentComments,
            'recentUsers' => $recentUsers
        ]);
    }
}
