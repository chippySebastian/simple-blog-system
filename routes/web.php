<?php

/**
 * Web Routes
 * 
 * Define all web application routes
 */

use App\Core\Router;
use App\Controllers\HomeController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\PostController;
use App\Controllers\CategoryController;
use App\Controllers\CommentController;
use App\Controllers\SearchController;
use App\Controllers\Admin\AdminDashboardController;
use App\Controllers\Admin\AdminUserController;
use App\Controllers\Admin\AdminPostController;
use App\Controllers\Admin\AdminCategoryController;
use App\Controllers\Admin\AdminCommentController;
use App\Controllers\ImageController;

$router = new Router();

// ============================================
// PUBLIC ROUTES
// ============================================

// Home
$router->get('/', function () {
    $controller = new HomeController();
    $controller->index();
});

// Image serving (must be before other routes to avoid conflicts)
$router->get('/images/{size}/{filename}', function ($size, $filename) {
    $controller = new ImageController();
    $controller->serve($size, $filename);
});

// ============================================
// AUTHENTICATION ROUTES
// ============================================

// Login
$router->get('/login', function () {
    $controller = new AuthController();
    $controller->showLoginForm();
});

$router->post('/login', function () {
    $controller = new AuthController();
    $controller->login();
});

// Register
$router->get('/register', function () {
    $controller = new AuthController();
    $controller->showRegisterForm();
});

$router->post('/register', function () {
    $controller = new AuthController();
    $controller->register();
});

// Logout
$router->get('/logout', function () {
    $controller = new AuthController();
    $controller->logout();
});

// Forgot Password
$router->get('/forgot-password', function () {
    $controller = new AuthController();
    $controller->showForgotPasswordForm();
});

$router->post('/forgot-password', function () {
    $controller = new AuthController();
    $controller->forgotPassword();
});

// Reset Password
$router->get('/reset-password', function () {
    $controller = new AuthController();
    $controller->showResetPasswordForm();
});

$router->post('/reset-password', function () {
    $controller = new AuthController();
    $controller->resetPassword();
});

// ============================================
// USER PROFILE ROUTES
// ============================================

$router->get('/profile', function () {
    $controller = new UserController();
    $controller->profile();
});

$router->get('/profile/edit', function () {
    $controller = new UserController();
    $controller->editProfile();
});

$router->post('/profile/update', function () {
    $controller = new UserController();
    $controller->updateProfile();
});

$router->get('/profile/change-password', function () {
    $controller = new UserController();
    $controller->showChangePasswordForm();
});

$router->post('/profile/change-password', function () {
    $controller = new UserController();
    $controller->changePassword();
});

// Authors
$router->get('/authors', function () {
    $controller = new UserController();
    $controller->authors();
});

$router->get('/authors/{id}', function ($id) {
    $controller = new UserController();
    $controller->profile($id);
});

// ============================================
// POST ROUTES
// ============================================

// My posts (must be before {id} route)
$router->get('/posts/my-posts', function () {
    $controller = new PostController();
    $controller->myPosts();
});

// Create post (must be before {id} route)
$router->get('/posts/create', function () {
    $controller = new PostController();
    $controller->create();
});

$router->post('/posts/store', function () {
    $controller = new PostController();
    $controller->store();
});

// List all posts
$router->get('/posts', function () {
    $controller = new PostController();
    $controller->index();
});

// Edit post (must be before show route)
$router->get('/posts/{id}/edit', function ($id) {
    $controller = new PostController();
    $controller->edit($id);
});

$router->post('/posts/{id}/update', function ($id) {
    $controller = new PostController();
    $controller->update($id);
});

// Delete post
$router->post('/posts/{id}/delete', function ($id) {
    $controller = new PostController();
    $controller->delete($id);
});

// Show single post (must be last to avoid conflicts)
$router->get('/posts/{id}', function ($id) {
    $controller = new PostController();
    $controller->show($id);
});

// ============================================
// CATEGORY ROUTES
// ============================================

$router->get('/categories', function () {
    $controller = new CategoryController();
    $controller->index();
});

$router->get('/categories/{id}', function ($id) {
    $controller = new CategoryController();
    $controller->show($id);
});

// ============================================
// COMMENT ROUTES
// ============================================

$router->post('/comments/store', function () {
    $controller = new CommentController();
    $controller->store();
});

$router->post('/comments/{id}/update', function ($id) {
    $controller = new CommentController();
    $controller->update($id);
});

$router->post('/comments/{id}/delete', function ($id) {
    $controller = new CommentController();
    $controller->delete($id);
});

// ============================================
// SEARCH ROUTES
// ============================================

$router->get('/search', function () {
    $controller = new SearchController();
    $controller->search();
});

// ============================================
// ADMIN ROUTES
// ============================================

// Admin Dashboard
$router->get('/admin', function () {
    $controller = new AdminDashboardController();
    $controller->index();
});

// Admin - Manage Categories
$router->get('/admin/categories', function () {
    $controller = new AdminCategoryController();
    $controller->index();
});

$router->get('/admin/categories/create', function () {
    $controller = new AdminCategoryController();
    $controller->create();
});

$router->post('/admin/categories/store', function () {
    $controller = new AdminCategoryController();
    $controller->store();
});

$router->get('/admin/categories/{id}/edit', function ($id) {
    $controller = new AdminCategoryController();
    $controller->edit($id);
});

$router->post('/admin/categories/{id}/update', function ($id) {
    $controller = new AdminCategoryController();
    $controller->update($id);
});

$router->post('/admin/categories/{id}/delete', function ($id) {
    $controller = new AdminCategoryController();
    $controller->delete($id);
});

// Admin - Moderate Comments
$router->get('/admin/comments', function () {
    $controller = new AdminCommentController();
    $controller->index();
});

$router->post('/admin/comments/{id}/approve', function ($id) {
    $controller = new AdminCommentController();
    $controller->approve($id);
});

$router->post('/admin/comments/{id}/reject', function ($id) {
    $controller = new AdminCommentController();
    $controller->reject($id);
});

$router->post('/admin/comments/{id}/delete', function ($id) {
    $controller = new AdminCommentController();
    $controller->delete($id);
});

// Admin - Manage Posts
$router->get('/admin/posts', function () {
    $controller = new AdminPostController();
    $controller->index();
});

$router->post('/admin/posts/{id}/delete', function ($id) {
    $controller = new AdminPostController();
    $controller->delete($id);
});

// Admin - Manage Users
$router->get('/admin/users', function () {
    $controller = new AdminUserController();
    $controller->index();
});

$router->get('/admin/users/{id}/edit', function ($id) {
    $controller = new AdminUserController();
    $controller->edit($id);
});

$router->post('/admin/users/{id}/update', function ($id) {
    $controller = new AdminUserController();
    $controller->update($id);
});

$router->post('/admin/users/{id}/delete', function ($id) {
    $controller = new AdminUserController();
    $controller->delete($id);
});

// ============================================
// HEALTH CHECK
// ============================================

$router->get('/health', function () {
    $controller = new HomeController();
    $controller->health();
});

return $router;

