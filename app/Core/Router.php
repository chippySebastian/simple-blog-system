<?php

namespace App\Core;

/**
 * Router Class
 * 
 * Simple router for handling HTTP requests
 */
class Router
{
    private $routes = [];
    private $currentMethod = '';
    private $currentPath = '';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->currentMethod = $_SERVER['REQUEST_METHOD'];
        $this->currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Handle XAMPP base path (remove /simple-blog-system from path)
        if (strpos($this->currentPath, '/simple-blog-system') === 0) {
            $this->currentPath = substr($this->currentPath, strlen('/simple-blog-system'));
        }
        
        // Remove base path from current path for other setups
        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($basePath !== '/' && strpos($this->currentPath, $basePath) === 0) {
            $this->currentPath = substr($this->currentPath, strlen($basePath));
        }
        
        if (empty($this->currentPath)) {
            $this->currentPath = '/';
        }
    }

    /**
     * Register a GET route
     */
    public function get($path, $callback)
    {
        $this->addRoute('GET', $path, $callback);
    }

    /**
     * Register a POST route
     */
    public function post($path, $callback)
    {
        $this->addRoute('POST', $path, $callback);
    }

    /**
     * Register a PUT route
     */
    public function put($path, $callback)
    {
        $this->addRoute('PUT', $path, $callback);
    }

    /**
     * Register a DELETE route
     */
    public function delete($path, $callback)
    {
        $this->addRoute('DELETE', $path, $callback);
    }

    /**
     * Add a route
     */
    private function addRoute($method, $path, $callback)
    {
        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'callback' => $callback
        ];
    }

    /**
     * Dispatch the request to the appropriate route
     */
    public function dispatch()
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $this->currentMethod) {
                $params = [];
                if ($this->matchPath($route['path'], $params)) {
                    if (is_callable($route['callback'])) {
                        call_user_func_array($route['callback'], $params);
                    } else {
                        // Handle Controller@method format
                        list($controller, $method) = explode('@', $route['callback']);
                        $controller = "App\\Controllers\\" . $controller;
                        $controllerInstance = new $controller();
                        call_user_func_array([$controllerInstance, $method], $params);
                    }
                    return;
                }
            }
        }

        // Route not found
        http_response_code(404);
        echo "404 - Page Not Found";
    }

    /**
     * Match path with route pattern
     */
    private function matchPath($routePath, &$params = [])
    {
        $routePattern = preg_replace('/\{[a-z_]+\}/', '([a-zA-Z0-9_-]+)', $routePath);
        $routePattern = '#^' . $routePattern . '$#';
        
        if (preg_match($routePattern, $this->currentPath, $matches)) {
            array_shift($matches); // Remove full match
            $params = $matches;
            return true;
        }
        
        return false;
    }
}
