<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\CommentService;
use App\Services\PostService;
use App\Services\UserService;
use App\Helpers\AuthHelper;

/**
 * AdminCommentController
 * 
 * Admin comment moderation
 */
class AdminCommentController extends BaseController
{
    private $commentService;
    private $postService;
    private $userService;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->commentService = new CommentService();
        $this->postService = new PostService();
        $this->userService = new UserService();
    }

    /**
     * List all comments
     */
    public function index()
    {
        $status = $_GET['status'] ?? 'all';
        
        if ($status === 'all') {
            $comments = $this->commentService->getAll();
        } else {
            $comments = $this->commentService->getByStatus($status);
        }

        foreach ($comments as &$comment) {
            $comment['user'] = $this->userService->find($comment['user_id']);
            $comment['post'] = $this->postService->find($comment['post_id']);
        }

        echo $this->render('admin.comments.index', [
            'comments' => $comments,
            'currentStatus' => $status
        ]);
    }

    /**
     * Approve comment
     */
    public function approve($id)
    {
        $comment = $this->commentService->find($id);

        if (!$comment) {
            $this->setFlash('error', 'Comment not found');
            $this->redirect('/admin/comments');
        }

        $this->commentService->approve($id);
        $this->setFlash('success', 'Comment approved');
        $this->redirect('/admin/comments');
    }

    /**
     * Reject comment
     */
    public function reject($id)
    {
        $comment = $this->commentService->find($id);

        if (!$comment) {
            $this->setFlash('error', 'Comment not found');
            $this->redirect('/admin/comments');
        }

        $this->commentService->reject($id);
        $this->setFlash('success', 'Comment rejected');
        $this->redirect('/admin/comments');
    }

    /**
     * Delete comment
     */
    public function delete($id)
    {
        $comment = $this->commentService->find($id);

        if (!$comment) {
            $this->setFlash('error', 'Comment not found');
            $this->redirect('/admin/comments');
        }

        $this->commentService->delete($id);
        $this->setFlash('success', 'Comment deleted');
        $this->redirect('/admin/comments');
    }
}
