<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

/**
 * Add Email Verification Fields To Users Table Migration
 */
class AddEmailVerificationFieldsToUsersTable extends Migration
{
    public function getName(): string
    {
        return '2026_03_09_000008_add_email_verification_fields_to_users_table';
    }

    public function up(): void
    {
        $sql = "
            ALTER TABLE users
            ADD COLUMN IF NOT EXISTS email_verification_token VARCHAR(100),
            ADD COLUMN IF NOT EXISTS email_verification_expires TIMESTAMP
        ";

        $this->execute($sql);
        echo "  - Added email verification fields to users table\n";
    }

    public function down(): void
    {
        $sql = "
            ALTER TABLE users
            DROP COLUMN IF EXISTS email_verification_token,
            DROP COLUMN IF EXISTS email_verification_expires
        ";

        $this->execute($sql);
        echo "  - Removed email verification fields from users table\n";
    }
}
