<?php

namespace App\Helpers;

/**
 * CsrfHelper
 * 
 * Provides CSRF token generation and validation
 */
class CsrfHelper
{
    private const TOKEN_NAME = 'csrf_token';
    
    /**
     * Generate and store CSRF token in session
     * 
     * @return string
     */
    public static function generateToken()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        if (!isset($_SESSION[self::TOKEN_NAME])) {
            $_SESSION[self::TOKEN_NAME] = bin2hex(random_bytes(32));
        }
        
        return $_SESSION[self::TOKEN_NAME];
    }
    
    /**
     * Get current CSRF token
     * 
     * @return string|null
     */
    public static function getToken()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        return $_SESSION[self::TOKEN_NAME] ?? null;
    }
    
    /**
     * Validate CSRF token from request
     * 
     * @param string|null $token Token from request
     * @return bool
     */
    public static function validateToken($token)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $sessionToken = $_SESSION[self::TOKEN_NAME] ?? null;
        
        if (!$sessionToken || !$token) {
            return false;
        }
        
        return hash_equals($sessionToken, $token);
    }
    
    /**
     * Validate CSRF token from POST request and die if invalid
     */
    public static function validate()
    {
        $token = $_POST[self::TOKEN_NAME] ?? $_SERVER['HTTP_X_CSRF_TOKEN'] ?? null;
        
        if (!self::validateToken($token)) {
            http_response_code(403);
            die('CSRF token validation failed. Please refresh the page and try again.');
        }
    }
    
    /**
     * Generate HTML hidden input for CSRF token
     * 
     * @return string
     */
    public static function field()
    {
        $token = self::generateToken();
        return '<input type="hidden" name="' . self::TOKEN_NAME . '" value="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Get token meta tag for AJAX requests
     * 
     * @return string
     */
    public static function metaTag()
    {
        $token = self::generateToken();
        return '<meta name="csrf-token" content="' . htmlspecialchars($token) . '">';
    }
    
    /**
     * Regenerate CSRF token (e.g., after login)
     */
    public static function regenerateToken()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
        
        $_SESSION[self::TOKEN_NAME] = bin2hex(random_bytes(32));
    }
}
