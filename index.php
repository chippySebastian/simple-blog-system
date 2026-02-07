<?php
// Root entry point for XAMPP
define('BASE_PATH', __DIR__);

session_start();

// Check if Composer is installed
if (!file_exists(BASE_PATH . '/vendor/autoload.php')) {
    echo "<h1>Error: Composer not installed</h1>";
    echo "<p>Run: <code>composer install</code></p>";
    exit;
}

require_once BASE_PATH . '/vendor/autoload.php';
require_once BASE_PATH . '/app/Helpers.php';

use App\Core\App;
App::bootstrap();

$router = require_once BASE_PATH . '/routes/web.php';
$router->dispatch();
