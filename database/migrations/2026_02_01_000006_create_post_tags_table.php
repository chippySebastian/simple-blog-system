<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Post Tags Pivot Table Migration
 */
class CreatePostTagsTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000006_create_post_tags_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS post_tags (
                post_id INTEGER NOT NULL REFERENCES posts(id) ON DELETE CASCADE,
                tag_id INTEGER NOT NULL REFERENCES tags(id) ON DELETE CASCADE,
                PRIMARY KEY (post_id, tag_id)
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created post_tags table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS post_tags CASCADE");
        echo "  ✓ Dropped post_tags table\n";
    }
}
