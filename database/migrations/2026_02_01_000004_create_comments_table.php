<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Comments Table Migration
 */
class CreateCommentsTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000004_create_comments_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS comments (
                id SERIAL PRIMARY KEY,
                post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                parent_id INTEGER REFERENCES comments(id) ON DELETE CASCADE,
                content TEXT NOT NULL,
                status VARCHAR(20) DEFAULT 'approved',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created comments table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS comments CASCADE");
        echo "  ✓ Dropped comments table\n";
    }
}
