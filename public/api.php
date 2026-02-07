<?php
/**
 * API Entry Point
 * 
 * This is the entry point for RESTful API requests
 */

header('Content-Type: application/json');

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Start session
session_start();

// Load Composer autoloader
require_once BASE_PATH . '/vendor/autoload.php';

// Include global helpers
require_once BASE_PATH . '/app/Helpers.php';

// Bootstrap application
use App\Core\App;
App::bootstrap();

// TODO: Load API routes and dispatch
// For now, display a welcome message
http_response_code(200);
echo json_encode([
    'status' => 'success',
    'message' => 'Simple Blog System API - Ready for development',
    'version' => '1.0.0',
    'timestamp' => date('Y-m-d H:i:s')
]);
