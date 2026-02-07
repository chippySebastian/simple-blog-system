<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Categories Table Migration
 */
class CreateCategoriesTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000002_create_categories_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS categories (
                id SERIAL PRIMARY KEY,
                name VARCHAR(100) UNIQUE NOT NULL,
                slug VARCHAR(100) UNIQUE NOT NULL,
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created categories table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS categories CASCADE");
        echo "  ✓ Dropped categories table\n";
    }
}
