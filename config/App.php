<?php
/**
 * Application Configuration
 * 
 * General application settings
 */

return [
    'app_name' => 'Simple Blog System',
    'app_env' => 'development', // development, production
    'debug' => true,
    'timezone' => 'UTC',
    'url' => 'http://localhost:8000',
    
    // File upload settings
    'upload' => [
        'max_size' => 5242880, // 5MB in bytes
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'upload_dir' => 'storage/uploads/images/'
    ],
    
    // Pagination
    'pagination' => [
        'per_page' => 10,
    ],
    
    // Session
    'session_lifetime' => 3600, // 1 hour in seconds
];
