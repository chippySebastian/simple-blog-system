<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\PostService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Helpers\AuthHelper;

/**
 * AdminPostController
 * 
 * Admin post management
 */
class AdminPostController extends BaseController
{
    private $postService;
    private $userService;
    private $categoryService;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->categoryService = new CategoryService();
    }

    /**
     * List all posts
     */
    public function index()
    {
        $posts = $this->postService->all();
        
        foreach ($posts as &$post) {
            $post['author'] = $this->userService->find($post['author_id']);
        }

        echo $this->render('admin.posts.index', ['posts' => $posts]);
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        $post = $this->postService->find($id);

        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/admin/posts');
        }

        $this->postService->delete($id);
        $this->setFlash('success', 'Post deleted successfully');
        $this->redirect('/admin/posts');
    }
}
