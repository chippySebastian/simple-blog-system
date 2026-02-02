<?php

namespace App\Core;

use PDO;
use PDOException;

/**
 * Database Connection Class
 * 
 * Handles database connection and query execution
 */
class Database
{
    private static $instance = null;
    private $connection;

    /**
     * Private constructor to prevent direct instantiation
     */
    private function __construct()
    {
        $this->connect();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Create database connection
     */
    private function connect()
    {
        try {
            $dbConfig = require BASE_PATH . '/config/Database.php';
            
            $dsn = sprintf(
                'mysql:host=%s;port=%d;dbname=%s;charset=%s',
                $dbConfig['host'],
                $dbConfig['port'],
                $dbConfig['database'],
                $dbConfig['charset']
            );

            $this->connection = new PDO(
                $dsn,
                $dbConfig['username'],
                $dbConfig['password'],
                $dbConfig['options']
            );

            error_log('Database connected successfully');
        } catch (PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            die('Database connection error. Check logs for details.');
        }
    }

    /**
     * Get PDO connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Execute a prepared query
     */
    public function execute($query, $params = [])
    {
        try {
            $stmt = $this->connection->prepare($query);
            $stmt->execute($params);
            return $stmt;
        } catch (PDOException $e) {
            error_log('Query execution failed: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Fetch a single row
     */
    public function fetch($query, $params = [])
    {
        $stmt = $this->execute($query, $params);
        return $stmt->fetch();
    }

    /**
     * Fetch all rows
     */
    public function fetchAll($query, $params = [])
    {
        $stmt = $this->execute($query, $params);
        return $stmt->fetchAll();
    }

    /**
     * Get last inserted ID
     */
    public function lastInsertId()
    {
        return $this->connection->lastInsertId();
    }
}
