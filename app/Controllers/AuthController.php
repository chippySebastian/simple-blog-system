<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Helpers\AuthHelper;

/**
 * AuthController
 * 
 * Handles user authentication (login, register, logout)
 */
class AuthController extends BaseController
{
    private $userService;

    public function __construct()
    {
        $this->userService = new UserService();
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/');
        }
        echo $this->render('auth.login');
    }

    /**
     * Handle login
     */
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        if (empty($email) || empty($password)) {
            $this->setFlash('error', 'Please provide both email and password');
            $this->redirect('/login');
        }

        $user = $this->userService->findByEmail($email);

        if (!$user || !$this->userService->verifyPassword($user, $password)) {
            $this->setFlash('error', 'Invalid credentials');
            $this->redirect('/login');
        }

        if (!$user['verified']) {
            $this->setFlash('error', 'Please verify your email before logging in');
            $this->redirect('/login');
        }

        AuthHelper::login($user);
        $this->setFlash('success', 'Welcome back, ' . $user['name'] . '!');
        $this->redirect('/');
    }

    /**
     * Show registration form
     */
    public function showRegisterForm()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/');
        }
        echo $this->render('auth.register');
    }

    /**
     * Handle registration
     */
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Name is required';
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required';
        }

        if (empty($password) || strlen($password) < 6) {
            $errors[] = 'Password must be at least 6 characters';
        }

        if ($password !== $confirmPassword) {
            $errors[] = 'Passwords do not match';
        }

        // Check if email already exists
        if ($this->userService->findByEmail($email)) {
            $errors[] = 'Email already registered';
        }

        if (!empty($errors)) {
            $this->setFlash('error', implode('<br>', $errors));
            $this->redirect('/register');
        }

        // Create user
        $user = $this->userService->create([
            'name' => $name,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'user',
            'verified' => true, // Auto-verify for mock data (in real app, send email)
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random'
        ]);

        AuthHelper::login($user);
        $this->setFlash('success', 'Registration successful! Welcome, ' . $name . '!');
        $this->redirect('/');
    }

    /**
     * Logout
     */
    public function logout()
    {
        AuthHelper::logout();
        $this->setFlash('success', 'You have been logged out');
        $this->redirect('/');
    }

    /**
     * Show forgot password form
     */
    public function showForgotPasswordForm()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/');
        }
        echo $this->render('auth.forgot-password');
    }

    /**
     * Handle forgot password
     */
    public function forgotPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/forgot-password');
        }

        $email = $_POST['email'] ?? '';

        if (empty($email)) {
            $this->setFlash('error', 'Please provide your email');
            $this->redirect('/forgot-password');
        }

        $user = $this->userService->findByEmail($email);

        if ($user) {
            // In real app, send password reset email
            // For mock data, we'll just show a success message
            $this->setFlash('success', 'Password reset instructions sent to your email (mock - not actually sent)');
        } else {
            // Don't reveal if email exists or not
            $this->setFlash('success', 'If that email exists, password reset instructions have been sent');
        }

        $this->redirect('/login');
    }

    /**
     * Show reset password form
     */
    public function showResetPasswordForm()
    {
        if ($this->isAuthenticated()) {
            $this->redirect('/');
        }
        
        $token = $_GET['token'] ?? '';
        echo $this->render('auth.reset-password', ['token' => $token]);
    }

    /**
     * Handle password reset
     */
    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($password) || strlen($password) < 6) {
            $this->setFlash('error', 'Password must be at least 6 characters');
            $this->redirect('/reset-password?token=' . $token);
        }

        if ($password !== $confirmPassword) {
            $this->setFlash('error', 'Passwords do not match');
            $this->redirect('/reset-password?token=' . $token);
        }

        // In real app, verify token and update password
        // For mock data, just show success
        $this->setFlash('success', 'Password reset successful! Please login with your new password');
        $this->redirect('/login');
    }
}
