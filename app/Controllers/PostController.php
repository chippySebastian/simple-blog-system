<?php

namespace App\Controllers;

use App\Services\PostService;
use App\Services\CategoryService;
use App\Services\CommentService;
use App\Services\UserService;
use App\Services\ImageUploadService;
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
    private $imageService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->categoryService = new CategoryService();
        $this->commentService = new CommentService();
        $this->userService = new UserService();
        $this->imageService = new ImageUploadService();
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
            'comments' => array_filter($comments, fn($c) => $c['parent_id'] === null),
            'isAuthenticated' => $this->isAuthenticated(),
            'isAdmin' => $this->isAdmin(),
            'getCurrentUserId' => fn() => $this->getCurrentUserId()
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
        $status = $_POST['status'] ?? 'draft';
        $categories = $_POST['categories'] ?? [];
        
        // Get first category (posts table only supports single category_id)
        $categoryId = !empty($categories) ? intval($categories[0]) : null;

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Title is required';
        }

        // Check if content is empty or just contains empty HTML tags
        $contentText = strip_tags($content);
        if (empty($content) || empty(trim($contentText))) {
            $errors[] = 'Content is required';
        }
        
        // Handle image upload
        $featuredImage = '';
        if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->imageService->upload($_FILES['featured_image_file']);
            
            if ($uploadResult['success']) {
                $featuredImage = $uploadResult['filename'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/posts/create');
        }

        // Generate slug from title
        $slug = $this->generateUniqueSlug($title);

        $postId = $this->postService->create([
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt ?: substr(strip_tags($content), 0, 150) . '...',
            'featured_image' => $featuredImage,
            'category_id' => $categoryId,
            'user_id' => $this->getCurrentUserId(),
            'status' => $status,
            'views' => 0
        ]);

        $this->setFlash('success', 'Post created successfully');
        $this->redirect('/posts/' . $postId);
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
        $status = $_POST['status'] ?? 'draft';
        $categories = $_POST['categories'] ?? [];
        $existingImage = $_POST['existing_image'] ?? '';
        $deleteImage = isset($_POST['delete_image']) && $_POST['delete_image'] == '1';

        $errors = [];

        if (empty($title)) {
            $errors[] = 'Title is required';
        }

        // Check if content is empty or just contains empty HTML tags
        $contentText = strip_tags($content);
        if (empty($content) || empty(trim($contentText))) {
            $errors[] = 'Content is required';
        }
        
        // Handle image management
        $featuredImage = $existingImage; // Keep existing by default
        
        // Check if user wants to delete current image
        if ($deleteImage && !empty($existingImage)) {
            $this->imageService->delete($existingImage);
            $featuredImage = '';
        }
        
        // Handle new file upload
        if (isset($_FILES['featured_image_file']) && $_FILES['featured_image_file']['error'] !== UPLOAD_ERR_NO_FILE) {
            // Delete old image
            if (!empty($existingImage)) {
                $this->imageService->delete($existingImage);
            }
            
            $uploadResult = $this->imageService->upload($_FILES['featured_image_file']);
            
            if ($uploadResult['success']) {
                $featuredImage = $uploadResult['filename'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/posts/' . $id . '/edit');
        }

        $slug = $this->generateUniqueSlug($title, $id);
        
        // Get first category (posts table only supports single category_id)
        $categoryId = !empty($categories) ? intval($categories[0]) : null;

        $this->postService->update($id, [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt ?: substr(strip_tags($content), 0, 150) . '...',
            'featured_image' => $featuredImage,
            'category_id' => $categoryId,
            'status' => $status
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

        // Delete associated image
        if (!empty($post['featured_image'])) {
            $this->imageService->delete($post['featured_image']);
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
     * Generate unique slug from title
     */
    private function generateUniqueSlug($title, $excludeId = null)
    {
        $baseSlug = $this->generateSlug($title);
        $slug = $baseSlug;
        $counter = 1;
        
        // Check if slug exists (excluding current post for updates)
        while (true) {
            $existingPost = $this->postService->findBySlug($slug);
            
            // If no post found, or if it's the current post being updated, slug is unique
            if (!$existingPost || ($excludeId && $existingPost['id'] == $excludeId)) {
                break;
            }
            
            // Append counter and try again
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
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
