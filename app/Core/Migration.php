<?php

namespace App\Core;

use PDO;

/**
 * Base Migration Class
 * 
 * Provides structure and utilities for database migrations
 */
abstract class Migration
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Run the migration
     */
    abstract public function up(): void;

    /**
     * Reverse the migration
     */
    abstract public function down(): void;

    /**
     * Get migration name
     */
    abstract public function getName(): string;

    /**
     * Execute a SQL statement
     */
    protected function execute(string $sql): void
    {
        $this->pdo->exec($sql);
    }

    /**
     * Check if a table exists
     */
    protected function tableExists(string $table): bool
    {
        $stmt = $this->pdo->prepare(
            "SELECT 1 FROM information_schema.tables 
             WHERE table_schema = 'public' AND table_name = ?"
        );
        $stmt->execute([$table]);
        return (bool) $stmt->fetchColumn();
    }
}
