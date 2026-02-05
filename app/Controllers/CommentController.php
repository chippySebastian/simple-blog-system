<?php

namespace App\Controllers;

use App\Services\CommentService;
use App\Services\PostService;
use App\Helpers\AuthHelper;

/**
 * CommentController
 * 
 * Handles comment operations
 */
class CommentController extends BaseController
{
    private $commentService;
    private $postService;

    public function __construct()
    {
        $this->commentService = new CommentService();
        $this->postService = new PostService();
    }

    /**
     * Store new comment
     */
    public function store()
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $postId = $_POST['post_id'] ?? null;
        $parentId = $_POST['parent_id'] ?? null;
        $content = $_POST['content'] ?? '';

        if (!$postId || empty($content)) {
            $this->setFlash('error', 'Comment content is required');
            $this->redirect('/posts/' . $postId);
        }

        $post = $this->postService->find($postId);
        if (!$post) {
            $this->setFlash('error', 'Post not found');
            $this->redirect('/posts');
        }

        $this->commentService->create([
            'post_id' => $postId,
            'user_id' => $this->getCurrentUserId(),
            'parent_id' => $parentId,
            'content' => htmlspecialchars($content),
            'status' => 'approved' // Auto-approve for simplicity, can be 'pending' for moderation
        ]);

        $this->setFlash('success', 'Comment posted successfully');
        $this->redirect('/posts/' . $postId);
    }

    /**
     * Update comment
     */
    public function update($id)
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        $comment = $this->commentService->find($id);

        if (!$comment) {
            $this->setFlash('error', 'Comment not found');
            $this->redirect('/');
        }

        // Check if user can edit this comment
        if ($comment['user_id'] != $this->getCurrentUserId() && !$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to edit this comment');
            $this->redirect('/posts/' . $comment['post_id']);
        }

        $content = $_POST['content'] ?? '';

        if (empty($content)) {
            $this->setFlash('error', 'Comment content is required');
            $this->redirect('/posts/' . $comment['post_id']);
        }

        $this->commentService->update($id, [
            'content' => htmlspecialchars($content)
        ]);

        $this->setFlash('success', 'Comment updated successfully');
        $this->redirect('/posts/' . $comment['post_id']);
    }

    /**
     * Delete comment
     */
    public function delete($id)
    {
        AuthHelper::requireAuth();
        
        $comment = $this->commentService->find($id);

        if (!$comment) {
            $this->setFlash('error', 'Comment not found');
            $this->redirect('/');
        }

        // Check if user can delete this comment
        if ($comment['user_id'] != $this->getCurrentUserId() && !$this->isAdmin()) {
            $this->setFlash('error', 'You do not have permission to delete this comment');
            $this->redirect('/posts/' . $comment['post_id']);
        }

        $postId = $comment['post_id'];
        $this->commentService->delete($id);

        $this->setFlash('success', 'Comment deleted successfully');
        $this->redirect('/posts/' . $postId);
    }
}
