<?php
/**
 * Application Health Check
 * 
 * Validates that the application is properly configured
 */

echo "\n";
echo "=================================================\n";
echo "Simple Blog System - Health Check\n";
echo "=================================================\n\n";

$checks = [];
$allPassed = true;

// Check PHP version
echo "Checking PHP version... ";
$phpVersion = phpversion();
if (version_compare($phpVersion, '7.4', '>=')) {
    echo "✓ PHP $phpVersion\n";
    $checks['php'] = true;
} else {
    echo "✗ PHP $phpVersion (requires 7.4+)\n";
    $checks['php'] = false;
    $allPassed = false;
}

// Check required extensions
$requiredExtensions = ['pdo', 'pdo_mysql', 'mbstring', 'json'];
echo "\nChecking required PHP extensions:\n";
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "  ✓ $ext\n";
        $checks["ext_$ext"] = true;
    } else {
        echo "  ✗ $ext (missing)\n";
        $checks["ext_$ext"] = false;
        $allPassed = false;
    }
}

// Check file permissions
echo "\nChecking directory permissions:\n";
$dirs = [
    'storage' => 'storage/',
    'storage/uploads' => 'storage/uploads/',
    'storage/cache' => 'storage/cache/',
    'storage/logs' => 'storage/logs/',
    'public' => 'public/'
];

foreach ($dirs as $name => $dir) {
    $fullPath = __DIR__ . '/' . $dir;
    if (is_dir($fullPath) && is_writable($fullPath)) {
        echo "  ✓ $dir (writable)\n";
        $checks["dir_$name"] = true;
    } elseif (is_dir($fullPath)) {
        echo "  ✓ $dir (exists, but not writable - this may be OK on some systems)\n";
        $checks["dir_$name"] = true;
    } else {
        echo "  ✗ $dir (missing)\n";
        $checks["dir_$name"] = false;
        $allPassed = false;
    }
}

// Check configuration files
echo "\nChecking configuration files:\n";
$configFiles = [
    '.env' => '.env',
    'composer.json' => 'composer.json',
    'config/Database.php' => 'config/Database.php',
    'config/App.php' => 'config/App.php'
];

foreach ($configFiles as $name => $file) {
    $fullPath = __DIR__ . '/' . $file;
    if (file_exists($fullPath)) {
        echo "  ✓ $name\n";
        $checks["file_$name"] = true;
    } else {
        echo "  ✗ $name (missing)\n";
        $checks["file_$name"] = false;
        $allPassed = false;
    }
}

// Check Composer autoloader
echo "\nChecking Composer autoloader:\n";
$autoloadPath = __DIR__ . '/vendor/autoload.php';
if (file_exists($autoloadPath)) {
    echo "  ✓ vendor/autoload.php\n";
    $checks['autoload'] = true;
    
    // Try to load it
    require_once $autoloadPath;
    if (class_exists('App\\Core\\App')) {
        echo "  ✓ App\\Core\\App class found\n";
        $checks['app_class'] = true;
    } else {
        echo "  ✗ App\\Core\\App class not found\n";
        $checks['app_class'] = false;
        $allPassed = false;
    }
} else {
    echo "  ✗ vendor/autoload.php (missing - run: composer install)\n";
    $checks['autoload'] = false;
    $allPassed = false;
}

// Check environment variables
echo "\nChecking environment configuration:\n";
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envContent = file_get_contents($envFile);
    if (strpos($envContent, 'DB_HOST') !== false) {
        echo "  ✓ Database configuration found in .env\n";
        $checks['env_db'] = true;
    } else {
        echo "  ✗ Database configuration missing from .env\n";
        $checks['env_db'] = false;
        $allPassed = false;
    }
} else {
    echo "  ✗ .env file not found\n";
    $checks['env_db'] = false;
    $allPassed = false;
}

// Summary
echo "\n=================================================\n";
if ($allPassed) {
    echo "✓ All checks passed! Application is ready.\n";
    echo "\nTo start the development server:\n";
    echo "  composer serve\n";
    echo "\nOr:\n";
    echo "  php -S localhost:8000 -t public/\n";
    echo "\nThen visit: http://localhost:8000\n";
} else {
    echo "✗ Some checks failed. See above for details.\n";
    echo "\nCommon solutions:\n";
    echo "  - Run: composer install\n";
    echo "  - Check PHP version: php -v\n";
    echo "  - Enable required extensions in php.ini\n";
    echo "  - Copy .env.example to .env\n";
}
echo "=================================================\n\n";

exit($allPassed ? 0 : 1);
