<?php

/**
 * Helper Functions
 * 
 * Global utility functions for the application
 */

/**
 * Log a message
 */
function log_message($message, $level = 'info')
{
    $logFile = BASE_PATH . '/storage/logs/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $logEntry = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $logEntry, FILE_APPEND);
}

/**
 * Sanitize input to prevent XSS
 */
function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

/**
 * Validate email
 */
function validate_email($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Hash password
 */
function hash_password($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

/**
 * Verify password
 */
function verify_password($password, $hash)
{
    return password_verify($password, $hash);
}

/**
 * Redirect to a URL
 */
function redirect($url, $statusCode = 302)
{
    header("Location: $url", true, $statusCode);
    exit;
}

/**
 * Get current timestamp
 */
function now()
{
    return date('Y-m-d H:i:s');
}

/**
 * Check if user is logged in
 */
function is_logged_in()
{
    return isset($_SESSION['user_id']);
}

/**
 * Get current user ID
 */
function current_user_id()
{
    return $_SESSION['user_id'] ?? null;
}

/**
 * Check if current user is admin
 */
function is_admin()
{
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
}

/**
 * Generate CSRF token
 */
function csrf_token()
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF token
 */
function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Display success message
 */
function success_message($message)
{
    $_SESSION['success_message'] = $message;
}

/**
 * Display error message
 */
function error_message($message)
{
    $_SESSION['error_message'] = $message;
}

/**
 * Get and clear success message
 */
function get_success_message()
{
    $message = $_SESSION['success_message'] ?? null;
    unset($_SESSION['success_message']);
    return $message;
}

/**
 * Get and clear error message
 */
function get_error_message()
{
    $message = $_SESSION['error_message'] ?? null;
    unset($_SESSION['error_message']);
    return $message;
}

/**
 * Escape HTML
 */
function escape($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format date
 */
function format_date($date, $format = 'Y-m-d H:i:s')
{
    try {
        $dateTime = new DateTime($date);
        return $dateTime->format($format);
    } catch (Exception $e) {
        return $date;
    }
}
