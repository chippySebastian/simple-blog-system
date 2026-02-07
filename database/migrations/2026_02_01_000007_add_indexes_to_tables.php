<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Add Indexes Migration
 */
class AddIndexesToTables extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000007_add_indexes_to_tables';
    }

    public function up(): void
    {
        $indexes = [
            "CREATE INDEX IF NOT EXISTS idx_posts_user_id ON posts(user_id)",
            "CREATE INDEX IF NOT EXISTS idx_posts_category_id ON posts(category_id)",
            "CREATE INDEX IF NOT EXISTS idx_posts_status ON posts(status)",
            "CREATE INDEX IF NOT EXISTS idx_posts_published_at ON posts(published_at)",
            "CREATE INDEX IF NOT EXISTS idx_comments_post_id ON comments(post_id)",
            "CREATE INDEX IF NOT EXISTS idx_comments_user_id ON comments(user_id)",
            "CREATE INDEX IF NOT EXISTS idx_users_email ON users(email)",
            "CREATE INDEX IF NOT EXISTS idx_users_username ON users(username)",
        ];
        
        foreach ($indexes as $sql) {
            $this->execute($sql);
        }
        
        echo "  ✓ Created indexes\n";
    }

    public function down(): void
    {
        $indexes = [
            "DROP INDEX IF EXISTS idx_posts_user_id",
            "DROP INDEX IF EXISTS idx_posts_category_id",
            "DROP INDEX IF EXISTS idx_posts_status",
            "DROP INDEX IF EXISTS idx_posts_published_at",
            "DROP INDEX IF EXISTS idx_comments_post_id",
            "DROP INDEX IF EXISTS idx_comments_user_id",
            "DROP INDEX IF EXISTS idx_users_email",
            "DROP INDEX IF EXISTS idx_users_username",
        ];
        
        foreach ($indexes as $sql) {
            $this->execute($sql);
        }
        
        echo "  ✓ Dropped indexes\n";
    }
}
