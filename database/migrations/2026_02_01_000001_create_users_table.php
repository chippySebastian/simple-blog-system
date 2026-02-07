<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Create Users Table Migration
 */
class CreateUsersTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_01_000001_create_users_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS users (
                id SERIAL PRIMARY KEY,
                username VARCHAR(50) UNIQUE NOT NULL,
                email VARCHAR(100) UNIQUE NOT NULL,
                password VARCHAR(255) NOT NULL,
                full_name VARCHAR(100),
                bio TEXT,
                avatar VARCHAR(255),
                role VARCHAR(20) DEFAULT 'user',
                status VARCHAR(20) DEFAULT 'active',
                email_verified BOOLEAN DEFAULT FALSE,
                remember_token VARCHAR(100),
                reset_token VARCHAR(100),
                reset_token_expires TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created users table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS users CASCADE");
        echo "  ✓ Dropped users table\n";
    }
}
