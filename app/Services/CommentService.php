<?php

namespace App\Services;

/**
 * CommentService
 * 
 * Service for managing comments with mock data
 */
class CommentService extends MockDataService
{
    public function __construct()
    {
        $this->dataKey = 'comments';
        $this->autoIncrementKey = 'comments_id';
        parent::__construct();
        $this->seedDefaultComments();
    }

    private function seedDefaultComments()
    {
        if (empty($_SESSION[$this->dataKey])) {
            $this->create([
                'post_id' => 1,
                'user_id' => 2,
                'parent_id' => null,
                'content' => 'Great article! Very helpful for beginners.',
                'status' => 'approved'
            ]);

            $this->create([
                'post_id' => 1,
                'user_id' => 3,
                'parent_id' => null,
                'content' => 'I learned a lot from this. Thanks for sharing!',
                'status' => 'approved'
            ]);

            $this->create([
                'post_id' => 1,
                'user_id' => 1,
                'parent_id' => 1,
                'content' => 'Glad you found it helpful!',
                'status' => 'approved'
            ]);

            $this->create([
                'post_id' => 2,
                'user_id' => 3,
                'parent_id' => null,
                'content' => 'The MVC pattern really helps organize code better.',
                'status' => 'approved'
            ]);

            $this->create([
                'post_id' => 3,
                'user_id' => 2,
                'parent_id' => null,
                'content' => 'These tips are gold! Implementing them right away.',
                'status' => 'pending'
            ]);
        }
    }

    public function getByPost($postId, $status = 'approved')
    {
        return array_filter($_SESSION[$this->dataKey], function ($comment) use ($postId, $status) {
            return $comment['post_id'] == $postId && 
                   ($status === 'all' || $comment['status'] === $status);
        });
    }

    public function getReplies($parentId)
    {
        return $this->where('parent_id', $parentId);
    }

    public function getByStatus($status)
    {
        return $this->where('status', $status);
    }

    public function approve($id)
    {
        return $this->update($id, ['status' => 'approved']);
    }

    public function reject($id)
    {
        return $this->update($id, ['status' => 'rejected']);
    }

    public function pending($id)
    {
        return $this->update($id, ['status' => 'pending']);
    }
}
