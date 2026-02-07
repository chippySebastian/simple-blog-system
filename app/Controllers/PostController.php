<?php

namespace App\Controllers;

use App\Services\PostService;
use App\Services\CategoryService;
use App\Services\CommentService;
use App\Services\UserService;
use App\Helpers\AuthHelper;

/**
 * PostController
 * 
 * Handles blog post operations
 */
class PostController extends BaseController
{
    private $postService;
    private $categoryService;
    private $commentService;
    private $userService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
        $this->commentService = new CommentService();
        $this->userService = new UserService();
    }

    /**
     * List all published posts
     */
    public function index()
    {
        $posts = $this->postService->getPublished();

        // Add comment count to each post (author info already included from JOIN)
        foreach ($posts as &$post) {
            $post['comment_count'] = $this->commentService->countByPost($post['id']);
        }

        echo $this->render('posts.index', ['posts' => $posts]);
    }

    /**
     * Show single post
     */
    public function show($id)
    {
        $post = $this->postService->find($id);

        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/posts');
        }

        // Check if user can view this post
        if ($post['status'] !== 'published') {
            if (!$this->isAuthenticated() || 
                ($this->getCurrentUserId() != $post['user_id'] && !$this->isAdmin())) {
                $this->setFlash('error', 'Post not found');
                $this->redirect('/posts');
            }
        }

        // Increment views
        $this->postService->incrementViews($id);

        // Author info already included from JOIN
        
        // Get comments
        $comments = $this->commentService->getByPost($id);
        foreach ($comments as &$comment) {
            $comment['user'] = $this->userService->find($comment['user_id']);
            
            // Get replies
            $replies = $this->commentService->getReplies($comment['id']);
            foreach ($replies as &$reply) {
                $reply['user'] = $this->userService->find($reply['user_id']);
            }
            $comment['replies'] = $replies;
        }

        echo $this->render('posts.show', [
            'post' => $post,
            'comments' => array_filter($comments, fn($c) => $c['parent_id'] === null)
        ]);
    }

    /**
     * Show create post form
     */
    public function create()
    {
        AuthHelper::requireAuth();
        $categories = $this->categoryService->getAll();
        echo $this->render('posts.create', ['categories' => $categories]);
    }

    /**
     * Store new post
     */
    public function store()
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/posts/create');
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $featuredImage = $_POST['featured_image'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $categories = $_POST['categories'] ?? [];

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Title is required';
        }

        if (empty($content)) {
            $errors[] = 'Content is required';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/posts/create');
        }

        // Generate slug from title
        $slug = $this->generateSlug($title);

        $post = $this->postService->create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt ?: substr(strip_tags($content), 0, 150) . '...',
            'featured_image' => $featuredImage,
            'user_id' => $this->getCurrentUserId(),
            'status' => $status,
            'categories' => array_map('intval', $categories),
            'views' => 0
        ]);

        $this->setFlash('success', 'Post created successfully');
        $this->redirect('/posts/' . $post['id']);
    }

    /**
     * Show edit post form
     */
    public function edit($id)
    {
        AuthHelper::requireAuth();
        
        $post = $this->postService->find($id);

        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/posts');
        }

        // Check if user can edit this post
        if ($post['user_id'] != $this->getCurrentUserId() && !$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to edit this post');
            $this->redirect('/posts/' . $id);
        }

        $categories = $this->categoryService->getAll();
        echo $this->render('posts.edit', [
            'post' => $post,
            'categories' => $categories
        ]);
    }

    /**
     * Update post
     */
    public function update($id)
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/posts/' . $id . '/edit');
        }

        $post = $this->postService->find($id);

        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/posts');
        }

        // Check if user can edit this post
        if ($post['user_id'] != $this->getCurrentUserId() && !$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to edit this post');
            $this->redirect('/posts/' . $id);
        }

        $title = $_POST['title'] ?? '';
        $content = $_POST['content'] ?? '';
        $excerpt = $_POST['excerpt'] ?? '';
        $featuredImage = $_POST['featured_image'] ?? '';
        $status = $_POST['status'] ?? 'draft';
        $categories = $_POST['categories'] ?? [];

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Title is required';
        }

        if (empty($content)) {
            $errors[] = 'Content is required';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/posts/' . $id . '/edit');
        }

        $slug = $this->generateSlug($title);

        $this->postService->update($id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt ?: substr(strip_tags($content), 0, 150) . '...',
            'featured_image' => $featuredImage,
            'status' => $status,
            'categories' => array_map('intval', $categories)
        ]);

        $this->setFlash('success', 'Post updated successfully');
        $this->redirect('/posts/' . $id);
    }

    /**
     * Delete post
     */
    public function delete($id)
    {
        AuthHelper::requireAuth();
        
        $post = $this->postService->find($id);

        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/posts');
        }

        // Check if user can delete this post
        if ($post['user_id'] != $this->getCurrentUserId() && !$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to delete this post');
            $this->redirect('/posts/' . $id);
        }

        $this->postService->delete($id);
        $this->setFlash('success', 'Post deleted successfully');
        $this->redirect('/posts');
    }

    /**
     * My posts
     */
    public function myPosts()
    {
        AuthHelper::requireAuth();
        
        $posts = $this->postService->getByAuthor($this->getCurrentUserId());
        
        foreach ($posts as &$post) {
            $post['category_names'] = $this->getCategoryNames($post['categories'] ?? []);
            $post['comment_count'] = count($this->commentService->getByPost($post['id']));
        }

        echo $this->render('posts.my-posts', ['posts' => $posts]);
    }

    /**
     * Generate slug from title
     */
    private function generateSlug($title)
    {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        return $slug;
    }

    /**
     * Get category names
     */
    private function getCategoryNames($categoryIds)
    {
        $names = [];
        foreach ($categoryIds as $id) {
            $category = $this->categoryService->find($id);
            if ($category) {
                $names[] = $category['name'];
            }
        }
        return $names;
    }
}
