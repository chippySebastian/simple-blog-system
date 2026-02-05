<?php

namespace App\Services;

/**
 * PostService
 * 
 * Service for managing blog posts with mock data
 */
class PostService extends MockDataService
{
    public function __construct()
    {
        $this->dataKey = 'posts';
        $this->autoIncrementKey = 'posts_id';
        parent::__construct();
        $this->seedDefaultPosts();
    }

    private function seedDefaultPosts()
    {
        if (empty($_SESSION[$this->dataKey])) {
            $this->create([
                'title' => 'Getting Started with PHP',
                'slug' => 'getting-started-with-php',
                'content' => '<p>PHP is a popular general-purpose scripting language that is especially suited to web development. Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.</p><p>In this post, we\'ll explore the basics of PHP and how you can get started building dynamic websites.</p>',
                'excerpt' => 'Learn the basics of PHP and start building dynamic websites today.',
                'featured_image' => 'https://source.unsplash.com/800x400/?programming,php',
                'author_id' => 1,
                'status' => 'published',
                'categories' => [1, 2],
                'views' => 150
            ]);

            $this->create([
                'title' => 'Understanding MVC Architecture',
                'slug' => 'understanding-mvc-architecture',
                'content' => '<p>Model-View-Controller (MVC) is a software design pattern commonly used for developing user interfaces that divides an application into three interconnected components.</p><h3>Model</h3><p>The Model represents the data and business logic of the application.</p><h3>View</h3><p>The View is responsible for rendering the user interface.</p><h3>Controller</h3><p>The Controller handles user input and updates the Model and View accordingly.</p>',
                'excerpt' => 'A comprehensive guide to understanding the MVC architectural pattern.',
                'featured_image' => 'https://source.unsplash.com/800x400/?architecture,code',
                'author_id' => 2,
                'status' => 'published',
                'categories' => [2],
                'views' => 230
            ]);

            $this->create([
                'title' => '10 Tips for Writing Clean Code',
                'slug' => '10-tips-for-writing-clean-code',
                'content' => '<p>Clean code is code that is easy to understand, easy to change, and easy to maintain. Here are 10 tips to help you write cleaner code:</p><ol><li>Use meaningful variable names</li><li>Keep functions small and focused</li><li>Write comments for complex logic</li><li>Follow consistent coding standards</li><li>Avoid deep nesting</li></ol>',
                'excerpt' => 'Improve your code quality with these essential tips for writing clean code.',
                'featured_image' => 'https://source.unsplash.com/800x400/?coding,clean',
                'author_id' => 1,
                'status' => 'published',
                'categories' => [1, 3],
                'views' => 420
            ]);

            $this->create([
                'title' => 'Introduction to RESTful APIs',
                'slug' => 'introduction-to-restful-apis',
                'content' => '<p>REST (Representational State Transfer) is an architectural style for designing networked applications. RESTful APIs use HTTP requests to perform CRUD operations.</p>',
                'excerpt' => 'Learn the fundamentals of RESTful API design and implementation.',
                'featured_image' => 'https://source.unsplash.com/800x400/?api,rest',
                'author_id' => 3,
                'status' => 'published',
                'categories' => [2, 3],
                'views' => 310
            ]);

            $this->create([
                'title' => 'My Draft Post',
                'slug' => 'my-draft-post',
                'content' => '<p>This is a draft post that is not yet published.</p>',
                'excerpt' => 'A work in progress...',
                'featured_image' => '',
                'author_id' => 2,
                'status' => 'draft',
                'categories' => [1],
                'views' => 0
            ]);
        }
    }

    public function getPublished($limit = null)
    {
        $posts = $this->where('status', 'published');
        // Sort by created_at desc
        usort($posts, function ($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });
        
        return $limit ? array_slice($posts, 0, $limit) : $posts;
    }

    public function getByAuthor($authorId)
    {
        return $this->where('author_id', $authorId);
    }

    public function getByCategory($categoryId)
    {
        return array_filter($_SESSION[$this->dataKey], function ($post) use ($categoryId) {
            return isset($post['categories']) && in_array($categoryId, $post['categories']);
        });
    }

    public function getBySlug($slug)
    {
        $posts = $this->where('slug', $slug);
        return !empty($posts) ? reset($posts) : null;
    }

    public function incrementViews($id)
    {
        $post = $this->find($id);
        if ($post) {
            $post['views'] = ($post['views'] ?? 0) + 1;
            $this->update($id, ['views' => $post['views']]);
        }
    }
}
