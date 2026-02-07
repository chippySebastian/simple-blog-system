<?php

namespace App\Helpers;

use App\Services\UserService;

/**
 * AuthHelper
 * 
 * Helper class for authentication and session management
 */
class AuthHelper
{
    /**
     * Start session if not already started
     */
    public static function init()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Login a user
     */
    public static function login($user)
    {
        self::init();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['full_name'] ?? $user['username'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['is_authenticated'] = true;
    }

    /**
     * Logout the current user
     */
    public static function logout()
    {
        self::init();
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['is_authenticated']);
    }

    /**
     * Check if user is authenticated
     */
    public static function isAuthenticated()
    {
        self::init();
        return isset($_SESSION['is_authenticated']) && $_SESSION['is_authenticated'] === true;
    }

    /**
     * Check if user is admin
     */
    public static function isAdmin()
    {
        self::init();
        return self::isAuthenticated() && 
               isset($_SESSION['user_role']) && 
               $_SESSION['user_role'] === 'admin';
    }

    /**
     * Get current user ID
     */
    public static function userId()
    {
        self::init();
        return $_SESSION['user_id'] ?? null;
    }

    /**
     * Get current user data
     */
    public static function user()
    {
        self::init();
        if (!self::isAuthenticated()) {
            return null;
        }

        $userService = new UserService();
        return $userService->find(self::userId());
    }

    /**
     * Require authentication
     */
    public static function requireAuth()
    {
        if (!self::isAuthenticated()) {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Require admin role
     */
    public static function requireAdmin()
    {
        self::requireAuth();
        if (!self::isAdmin()) {
            header('Location: /');
            exit;
        }
    }

    /**
     * Set flash message
     */
    public static function setFlash($type, $message)
    {
        self::init();
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }

    /**
     * Get and clear flash message
     */
    public static function getFlash()
    {
        self::init();
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }

    /**
     * Generate CSRF token
     */
    public static function generateCsrfToken()
    {
        self::init();
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verify CSRF token
     */
    public static function verifyCsrfToken($token)
    {
        self::init();
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
