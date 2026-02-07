<?php

namespace App\Controllers;

use App\Services\PostService;
use App\Services\UserService;
use App\Services\CategoryService;
use App\Services\CommentService;

/**
 * SearchController
 * 
 * Handles search functionality
 */
class SearchController extends BaseController
{
    private $postService;
    private $userService;
    private $categoryService;
    private $commentService;

    public function __construct()
    {
        $this->postService = new PostService();
        $this->userService = new UserService();
        $this->categoryService = new CategoryService();
        $this->commentService = new CommentService();
    }

    /**
     * Search posts
     */
    public function search()
    {
        $query = $_GET['q'] ?? '';
        $page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
        $perPage = 10;

        if (empty($query)) {
            echo $this->render('search.results', [
                'query' => '',
                'results' => [],
                'total' => 0,
                'page' => 1,
                'totalPages' => 0
            ]);
            return;
        }

        // Search in title, content
        $results = $this->postService->search($query);
        
        // Filter only published posts for non-admin  
        if (!$this->isAdmin()) {
            $results = array_filter($results, fn($p) => $p['status'] === 'published');
        }

        // Add additional info (author info already included from JOIN)
        foreach ($results as &$result) {
            $result['comment_count'] = $this->commentService->countByPost($result['id']);
            
            // Highlight search terms in title and excerpt
            $result['highlighted_title'] = $this->highlight($result['title'], $query);
            $result['highlighted_excerpt'] = $this->highlight($result['excerpt'], $query);
        }

        // Sort by relevance (simple: count occurrences of search term)
        usort($results, function($a, $b) use ($query) {
            $aScore = substr_count(strtolower($a['title'] . ' ' . $a['content']), strtolower($query));
            $bScore = substr_count(strtolower($b['title'] . ' ' . $b['content']), strtolower($query));
            return $bScore - $aScore;
        });

        // Pagination
        $total = count($results);
        $totalPages = ceil($total / $perPage);
        $offset = ($page - 1) * $perPage;
        $results = array_slice($results, $offset, $perPage);

        echo $this->render('search.results', [
            'query' => $query,
            'results' => $results,
            'total' => $total,
            'page' => $page,
            'totalPages' => $totalPages,
            'perPage' => $perPage
        ]);
    }

    /**
     * Highlight search terms
     */
    private function highlight($text, $query)
    {
        $words = explode(' ', $query);
        foreach ($words as $word) {
            if (strlen($word) > 2) {
                $text = preg_replace('/(' . preg_quote($word, '/') . ')/i', '<mark>$1</mark>', $text);
            }
        }
        return $text;
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
