<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Posts Table Migration
 */
class CreatePostsTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000003_create_posts_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS posts (
                id SERIAL PRIMARY KEY,
                user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
                category_id INTEGER REFERENCES categories(id) ON DELETE SET NULL,
                title VARCHAR(255) NOT NULL,
                slug VARCHAR(255) UNIQUE NOT NULL,
                excerpt TEXT,
                content TEXT NOT NULL,
                featured_image VARCHAR(255),
                status VARCHAR(20) DEFAULT 'draft',
                views INTEGER DEFAULT 0,
                published_at TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created posts table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS posts CASCADE");
        echo "  ✓ Dropped posts table\n";
    }
}
