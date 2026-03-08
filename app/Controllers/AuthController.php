<?php

namespace App\Controllers;

use App\Services\UserService;
use App\Services\MailService;
use App\Helpers\AuthHelper;

/**
 * AuthController
 * 
 * Handles user authentication (login, register, logout)
 */
class AuthController extends BaseController
{
    private $userService;
    private $mailService;

    public function __construct()
    {
        $this->userService = new UserService();
        $this->mailService = new MailService();
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
        \App\Helpers\CsrfHelper::validate();
        
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

        if (!$user['email_verified']) {
            $this->setFlash('error', 'Please verify your email before logging in');
            $this->redirect('/login');
        }

        AuthHelper::login($user);
        $this->setFlash('success', 'Welcome back, ' . $user['full_name'] . '!');
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
        \App\Helpers\CsrfHelper::validate();
        
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

        // Create unique username
        $baseUsername = strtolower(str_replace(' ', '_', $name));
        $username = $baseUsername;
        $counter = 1;
        
        // Keep checking until we find a unique username
        while ($this->userService->findByUsername($username)) {
            $username = $baseUsername . $counter;
            $counter++;
        }

        $verificationToken = bin2hex(random_bytes(32));
        $verificationExpiresAt = gmdate('Y-m-d H:i:s', time() + 86400); // 24 hours (UTC)

        // Create user (UserService will hash the password)
        $userId = $this->userService->create([
            'username' => $username,
            'full_name' => $name,
            'email' => $email,
            'password' => $password,  // Pass plain password - service will hash it
            'role' => 'user',
            'email_verified' => false,
            'email_verification_token' => $verificationToken,
            'email_verification_expires' => $verificationExpiresAt,
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=random'
        ]);

        $verificationUrl = $this->getAppUrl() . '/verify-email?token=' . urlencode($verificationToken);
        $emailSent = $this->mailService->sendEmailVerificationEmail($email, $name, $verificationUrl);

        if ($emailSent) {
            $this->setFlash('success', 'Registration successful. Please check your email to verify your account before login.');
        } else {
            $this->setFlash('error', 'Registration completed, but verification email could not be sent. Please contact support.');
        }

        $this->redirect('/login');
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
        \App\Helpers\CsrfHelper::validate();
        
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
            $token = bin2hex(random_bytes(32));
            $expiresAt = gmdate('Y-m-d H:i:s', time() + 3600); // 1 hour (UTC)

            $this->userService->setResetToken($user['id'], $token, $expiresAt);

            $resetUrl = $this->getAppUrl() . '/reset-password?token=' . urlencode($token);
            $toName = $user['full_name'] ?: $user['username'];

            $emailSent = $this->mailService->sendPasswordResetEmail($user['email'], $toName, $resetUrl);

            if ($emailSent) {
                $this->setFlash('success', 'Password reset instructions sent to your email');
            } else {
                $this->setFlash('error', 'Unable to send reset email right now. Please try again later.');
            }
        } else {
            // Don't reveal if email exists or not (security best practice)
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
        \App\Helpers\CsrfHelper::validate();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/reset-password');
        }

        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($token)) {
            $this->setFlash('error', 'Invalid or missing reset token');
            $this->redirect('/forgot-password');
        }

        if (empty($password) || strlen($password) < 6) {
            $this->setFlash('error', 'Password must be at least 6 characters');
            $this->redirect('/reset-password?token=' . $token);
        }

        if ($password !== $confirmPassword) {
            $this->setFlash('error', 'Passwords do not match');
            $this->redirect('/reset-password?token=' . $token);
        }

        $user = $this->userService->findByValidResetToken($token);

        if (!$user) {
            $this->setFlash('error', 'Reset token is invalid or has expired');
            $this->redirect('/forgot-password');
        }

        $this->userService->updatePassword($user['id'], $password);
        $this->userService->clearResetToken($user['id']);

        $this->setFlash('success', 'Password reset successful! Please login with your new password');
        $this->redirect('/login');
    }

    /**
     * Verify email by token.
     */
    public function verifyEmail()
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            $this->setFlash('error', 'Invalid verification token');
            $this->redirect('/login');
        }

        $user = $this->userService->findByValidEmailVerificationToken($token);

        if (!$user) {
            $this->setFlash('error', 'Verification token is invalid or has expired');
            $this->redirect('/login');
        }

        $this->userService->markEmailVerified($user['id']);
        $this->setFlash('success', 'Email verified successfully. You can now login.');
        $this->redirect('/login');
    }

    private function getAppUrl()
    {
        $url = trim($_ENV['APP_URL'] ?? (getenv('APP_URL') ?: 'http://localhost:8000'));

        if (!preg_match('#^https?://#i', $url)) {
            $url = 'http://' . ltrim($url, '/');
        }

        return rtrim($url, '/');
    }
}
