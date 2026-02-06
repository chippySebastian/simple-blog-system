<?php

namespace App\Services;

/**
 * CategoryService
 * 
 * Service for managing categories with mock data
 */
class CategoryService extends MockDataService
{
    public function __construct()
    {
        $this->dataKey = 'categories';
        $this->autoIncrementKey = 'categories_id';
        parent::__construct();
        $this->seedDefaultCategories();
    }

    private function seedDefaultCategories()
    {
        if (empty($_SESSION[$this->dataKey])) {
            $this->create([
                'name' => 'Programming',
                'slug' => 'programming',
                'description' => 'Articles about programming languages and concepts'
            ]);

            $this->create([
                'name' => 'Web Development',
                'slug' => 'web-development',
                'description' => 'Web development tutorials and best practices'
            ]);

            $this->create([
                'name' => 'Software Engineering',
                'slug' => 'software-engineering',
                'description' => 'Software engineering principles and methodologies'
            ]);

            $this->create([
                'name' => 'Database',
                'slug' => 'database',
                'description' => 'Database design and management'
            ]);
        }
    }

    public function getBySlug($slug)
    {
        $categories = $this->where('slug', $slug);
        return !empty($categories) ? reset($categories) : null;
    }

    public function getPostCount($categoryId)
    {
        $postService = new PostService();
        $posts = $postService->getByCategory($categoryId);
        return count($posts);
    }
}
