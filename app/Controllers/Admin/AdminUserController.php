<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\UserService;
use App\Helpers\AuthHelper;

/**
 * AdminUserController
 * 
 * Admin user management
 */
class AdminUserController extends BaseController
{
    private $userService;

    public function __construct()
    {
        AuthHelper::requireAdmin();
        $this->userService = new UserService();
    }

    /**
     * List all users
     */
    public function index()
    {
        $users = $this->userService->getAll();
        echo $this->render('admin.users.index', ['users' => $users]);
    }

    /**
     * Show edit user form
     */
    public function edit($id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/admin/users');
        }

        echo $this->render('admin.users.edit', ['user' => $user]);
    }

    /**
     * Update user
     */
    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users/' . $id . '/edit');
        }

        $user = $this->userService->find($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/admin/users');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $role = $_POST['role'] ?? 'user';
        $verified = isset($_POST['verified']);

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        // Check if email is taken by another user
        $existingUser = $this->userService->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $id) {
            $errors[] = 'Email already in use';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/admin/users/' . $id . '/edit');
        }

        $this->userService->update($id, [
            'name' => $name,
            'email' => $email,
            'role' => $role,
            'verified' => $verified
        ]);

        $this->setFlash('success', 'User updated successfully');
        $this->redirect('/admin/users');
    }

    /**
     * Delete user
     */
    public function delete($id)
    {
        $user = $this->userService->find($id);

        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/admin/users');
        }

        // Prevent deleting self
        if ($id == $this->getCurrentUserId()) {
            $this->setFlash('error', 'You cannot delete yourself');
            $this->redirect('/admin/users');
        }

        $this->userService->delete($id);
        $this->setFlash('success', 'User deleted successfully');
        $this->redirect('/admin/users');
    }
}
