<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Tags Table Migration
 */
class CreateTagsTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000005_create_tags_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS tags (
                id SERIAL PRIMARY KEY,
                name VARCHAR(50) UNIQUE NOT NULL,
                slug VARCHAR(50) UNIQUE NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created tags table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS tags CASCADE");
        echo "  ✓ Dropped tags table\n";
    }
}
