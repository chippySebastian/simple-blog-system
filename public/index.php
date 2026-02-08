<?php
/**
 * Main Entry Point
 */

// Define base path
define('BASE_PATH', dirname(__DIR__));

// Security: Set secure HTTP headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Security: Harden session configuration
ini_set('session.cookie_httponly', '1');
ini_set('session.use_only_cookies', '1');
ini_set('session.cookie_samesite', 'Strict');
// Uncomment if using HTTPS:
// ini_set('session.cookie_secure', '1');

// Security: Session timeout (30 minutes of inactivity)
ini_set('session.gc_maxlifetime', '1800');
ini_set('session.cookie_lifetime', '1800');

// Start session
session_start();

// Security: Implement session timeout
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 1800)) {
    session_unset();
    session_destroy();
    session_start();
}
$_SESSION['LAST_ACTIVITY'] = time();

// Security: Validate session fingerprint
if (!isset($_SESSION['HTTP_USER_AGENT'])) {
    $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'] ?? '';
} elseif ($_SESSION['HTTP_USER_AGENT'] !== ($_SERVER['HTTP_USER_AGENT'] ?? '')) {
    // Session hijacking detected
    session_unset();
    session_destroy();
    session_start();
}

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

