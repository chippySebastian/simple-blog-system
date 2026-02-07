<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\CategoryService;
use App\Helpers\AuthHelper;

/**
 * AdminCategoryController
 * 
 * Admin category management
 */
class AdminCategoryController extends BaseController
{
    private $categoryService;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->categoryService = new CategoryService();
    }

    /**
     * List all categories
     */
    public function index()
    {
        $categories = $this->categoryService->getAll();
        
        foreach ($categories as &$category) {
            $category['post_count'] = $this->categoryService->getPostCount($category['id']);
        }

        echo $this->render('admin.categories.index', ['categories' => $categories]);
    }

    /**
     * Show create category form
     */
    public function create()
    {
        echo $this->render('admin.categories.create');
    }

    /**
     * Store new category
     */
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories/create');
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name)) {
            $this->setFlash('error', 'Category name is required');
            $this->redirect('/admin/categories/create');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $this->categoryService->create([
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ]);

        $this->setFlash('success', 'Category created successfully');
        $this->redirect('/admin/categories');
    }

    /**
     * Show edit category form
     */
    public function edit($id)
    {
        $category = $this->categoryService->find($id);

        if (!$category) {
            $this->setFlash('error', 'Category not found');
            $this->redirect('/admin/categories');
        }

        echo $this->render('admin.categories.edit', ['category' => $category]);
    }

    /**
     * Update category
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories/' . $id . '/edit');
        }

        $category = $this->categoryService->find($id);

        if (!$category) {
            $this->setFlash('error', 'Category not found');
            $this->redirect('/admin/categories');
        }

        $name = $_POST['name'] ?? '';
        $description = $_POST['description'] ?? '';

        if (empty($name)) {
            $this->setFlash('error', 'Category name is required');
            $this->redirect('/admin/categories/' . $id . '/edit');
        }

        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

        $this->categoryService->update($id, [
            'name' => $name,
            'slug' => $slug,
            'description' => $description
        ]);

        $this->setFlash('success', 'Category updated successfully');
        $this->redirect('/admin/categories');
    }

    /**
     * Delete category
     */
    public function delete($id)
    {
        $category = $this->categoryService->find($id);

        if (!$category) {
            $this->setFlash('error', 'Category not found');
            $this->redirect('/admin/categories');
        }

        $this->categoryService->delete($id);
        $this->setFlash('success', 'Category deleted successfully');
        $this->redirect('/admin/categories');
    }
}
