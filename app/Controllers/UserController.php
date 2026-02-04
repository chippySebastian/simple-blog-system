<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Services\PostService;
use App\Helpers\AuthHelper;

/**
 * UserController
 * 
 * Handles user profile and account management
 */
class UserController extends BaseController
{
    private $userService;
    private $postService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->postService = new PostService();
    }

    /**
     * Show user profile
     */
    public function profile($userId = null)
    {
        if ($userId === null) {
            AuthHelper::requireAuth();
            $userId = $this->getCurrentUserId();
        }

        $user = $this->userService->find($userId);
        
        if (!$user) {
            $this->setFlash('error', 'User not found');
            $this->redirect('/');
        }

        $posts = $this->postService->getByAuthor($userId);
        $isOwnProfile = $this->isAuthenticated() && $this->getCurrentUserId() == $userId;

        echo $this->render('user.profile', [
            'user' => $user,
            'posts' => $posts,
            'isOwnProfile' => $isOwnProfile
        ]);
    }

    /**
     * Show edit profile form
     */
    public function editProfile()
    {
        AuthHelper::requireAuth();
        $user = $this->getCurrentUser();
        echo $this->render('user.edit', ['user' => $user]);
    }

    /**
     * Update profile
     */
    public function updateProfile()
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/edit');
        }

        $userId = $this->getCurrentUserId();
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $bio = $_POST['bio'] ?? '';
        $avatar = $_POST['avatar'] ?? '';

        $errors = [];

        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        // Check if email is taken by another user
        $existingUser = $this->userService->findByEmail($email);
        if ($existingUser && $existingUser['id'] != $userId) {
            $errors[] = 'Email already in use';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/profile/edit');
        }

        $this->userService->update($userId, [
            'name' => $name,
            'email' => $email,
            'bio' => $bio,
            'avatar' => $avatar ?: 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random'
        ]);

        // Update session
        $_SESSION['user_name'] = $name;
        $_SESSION['user_email'] = $email;

        $this->setFlash('success', 'Profile updated successfully');
        $this->redirect('/profile');
    }

    /**
     * Show change password form
     */
    public function showChangePasswordForm()
    {
        AuthHelper::requireAuth();
        echo $this->render('user.change-password');
    }

    /**
     * Change password
     */
    public function changePassword()
    {
        AuthHelper::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/profile/change-password');
        }

        $userId = $this->getCurrentUserId();
        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = $this->userService->find($userId);

        if (!$this->userService->verifyPassword($user, $currentPassword)) {
            $this->setFlash('error', 'Current password is incorrect');
            $this->redirect('/profile/change-password');
        }

        if (strlen($newPassword) < 6) {
            $this->setFlash('error', 'New password must be at least 6 characters');
            $this->redirect('/profile/change-password');
        }

        if ($newPassword !== $confirmPassword) {
            $this->setFlash('error', 'New passwords do not match');
            $this->redirect('/profile/change-password');
        }

        $this->userService->updatePassword($userId, $newPassword);
        $this->setFlash('success', 'Password changed successfully');
        $this->redirect('/profile');
    }

    /**
     * Show all authors
     */
    public function authors()
    {
        $users = $this->userService->all();
        echo $this->render('user.authors', ['users' => $users]);
    }
}
