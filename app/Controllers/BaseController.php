<?php

namespace App\Controllers;

use App\Helpers\AuthHelper;

/**
 * BaseController
 * 
 * Base class for all controllers providing common functionality
 */
abstract class BaseController
{
    /**
     * Render a view
     */
    protected function render($view, $data = [], $layout = true)
    {
        extract($data);
        $viewPath = BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';
        if (file_exists($viewPath)) {
            ob_start();
            include $viewPath;
            $content = ob_get_clean();
            
            if ($layout) {
                $layoutPath = BASE_PATH . '/app/Views/layout.php';
                if (file_exists($layoutPath)) {
                    ob_start();
                    include $layoutPath;
                    return ob_get_clean();
                }
            }
            return $content;
        }
        throw new \Exception("View file not found: $viewPath");
    }
    
    /**
     * Redirect to a URL
     */
    protected function redirect($url)
    {
        header("Location: $url");
        exit;
    }
    
    /**
     * Return JSON response
     */
    protected function json($data, $statusCode = 200)
    {
        header('Content-Type: application/json');
        http_response_code($statusCode);
        echo json_encode($data);
        exit;
    }
    
    /**
     * Check if user is authenticated
     */
    protected function isAuthenticated()
    {
        return AuthHelper::isAuthenticated();
    }
    
    /**
     * Get current user ID
     */
    protected function getCurrentUserId()
    {
        return AuthHelper::userId();
    }
    
    /**
     * Get current user
     */
    protected function getCurrentUser()
    {
        return AuthHelper::user();
    }
    
    /**
     * Check if current user is admin
     */
    protected function isAdmin()
    {
        return AuthHelper::isAdmin();
    }

    /**
     * Get flash message
     */
    protected function getFlash()
    {
        return AuthHelper::getFlash();
    }

    /**
     * Set flash message
     */
    protected function setFlash($type, $message)
    {
        AuthHelper::setFlash($type, $message);
    }
}
