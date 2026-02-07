# Database Migrations

This directory contains database migration files for the Simple Blog System.

## Overview

Migrations are version control for your database. Each migration file contains SQL to create or modify database tables.

## Migration Files

All migrations are timestamped and run in order:

1. `2026_02_01_000001_create_users_table.php` - Creates users table
2. `2026_02_01_000002_create_categories_table.php` - Creates categories table
3. `2026_02_01_000003_create_posts_table.php` - Creates posts table
4. `2026_02_01_000004_create_comments_table.php` - Creates comments table
5. `2026_02_01_000005_create_tags_table.php` - Creates tags table
6. `2026_02_01_000006_create_post_tags_table.php` - Creates post_tags pivot table
7. `2026_02_01_000007_add_indexes_to_tables.php` - Adds database indexes

## Usage

### Run All Pending Migrations
```bash
php migrate.php up
# or
composer migrate
```

### Check Migration Status
```bash
php migrate.php status
# or
composer migrate:status
```

### Rollback Last Batch
```bash
php migrate.php down
# or
composer migrate:rollback
```

### Fresh Migration (Drop All & Recreate)
```bash
php migrate.php fresh
# or
composer migrate:fresh
```

### Run Migrations and Seed
```bash
composer db:setup
```

## Creating New Migrations

1. Create a new PHP file in `database/migrations/` following the naming convention:
   ```
   YYYY_MM_DD_HHMMSS_description.php
   ```

2. Extend the `Migration` class and implement required methods:

```php
<?php

require_once __DIR__ . '/../../app/Core/Migration.php';

use App\Core\Migration;

class CreateExampleTable extends Migration
{
    public function getName(): string
    {
        return '2026_02_07_120000_create_example_table';
    }

    public function up(): void
    {
        $sql = "
            CREATE TABLE IF NOT EXISTS examples (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ";
        
        $this->execute($sql);
        echo "  ✓ Created examples table\n";
    }

    public function down(): void
    {
        $this->execute("DROP TABLE IF EXISTS examples CASCADE");
        echo "  ✓ Dropped examples table\n";
    }
}
```

## Migration Tracking

Migrations are tracked in the `migrations` table, which contains:
- `id` - Auto-increment ID
- `migration` - Migration name
- `batch` - Batch number (for rollback grouping)
- `executed_at` - Timestamp of execution

## Notes

- Migrations run in alphabetical order by filename
- Each migration runs only once
- Rollbacks affect only the last batch
- Use `fresh` command with caution - it drops all tables!
