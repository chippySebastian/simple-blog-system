<?php
/**
 * Database Configuration
 * 
 * Configuration for MySQL database connection
 */

return [
    'driver' => 'pgsql',
    'host' => 'localhost',
    'port' => 5432,
    'database' => 'simple_blog_db',
    'username' => 'postgres',
    'password' => 'root',
    'charset' => 'utf8',
    'schema' => 'public',
    'options' => [
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
        \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
        \PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
