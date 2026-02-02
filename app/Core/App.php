<?php

namespace App\Core;

use Dotenv\Dotenv;

/**
 * Application Bootstrap
 * 
 * Initialize and configure the application
 */
class App
{
    private static $instance = null;

    /**
     * Private constructor
     */
    private function __construct()
    {
    }

    /**
     * Bootstrap the application
     */
    public static function bootstrap()
    {
        if (self::$instance === null) {
            self::$instance = new self();
            self::$instance->init();
        }
        return self::$instance;
    }

    /**
     * Initialize application
     */
    private function init()
    {
        // Load environment variables
        $this->loadEnvironment();

        // Set error reporting
        if (getenv('APP_DEBUG') === 'true') {
            ini_set('display_errors', 1);
            error_reporting(E_ALL);
        } else {
            ini_set('display_errors', 0);
            error_reporting(E_ALL);
        }

        // Set timezone
        date_default_timezone_set(getenv('TIMEZONE') ?: 'UTC');

        // Set up error/exception handlers
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);
    }

    /**
     * Load environment variables
     */
    private function loadEnvironment()
    {
        $envFile = BASE_PATH . '/.env';
        $envExampleFile = BASE_PATH . '/.env.example';

        if (!file_exists($envFile)) {
            if (file_exists($envExampleFile)) {
                copy($envExampleFile, $envFile);
            } else {
                die('.env file not found and .env.example not available');
            }
        }

        try {
            $dotenv = Dotenv::createImmutable(BASE_PATH);
            $dotenv->load();
        } catch (\Exception $e) {
            die('Failed to load environment variables: ' . $e->getMessage());
        }
    }

    /**
     * Handle PHP errors
     */
    public function handleError($errno, $errstr, $errfile, $errline)
    {
        $errorMessage = "Error [$errno]: $errstr in $errfile on line $errline";
        error_log($errorMessage);

        if (getenv('APP_DEBUG') === 'true') {
            echo $errorMessage . "\n";
        }

        return false;
    }

    /**
     * Handle exceptions
     */
    public function handleException(\Throwable $exception)
    {
        $errorMessage = $exception->getMessage() . " in " . $exception->getFile() . " on line " . $exception->getLine();
        error_log($errorMessage);

        if (getenv('APP_DEBUG') === 'true') {
            echo $errorMessage . "\n";
            echo $exception->getTraceAsString();
        } else {
            echo "An error occurred. Please try again later.";
        }
    }

    /**
     * Get configuration value
     */
    public static function config($key, $default = null)
    {
        return getenv($key) ?: $default;
    }
}
