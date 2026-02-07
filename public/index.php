<?php
/**
 * Main Entry Point
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Start session
session_start();

// Load Composer autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Include global helpers (if exists)
if (file_exists(BASE_PATH . '/app/Helpers.php')) {
    require_once BASE_PATH . '/app/Helpers.php';
}

// Bootstrap application
use App\Core\App;
App::bootstrap();

// Load routes and dispatch
$router = require_once BASE_PATH . '/routes/web.php';
$router->dispatch();

