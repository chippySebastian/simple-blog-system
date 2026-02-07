<?php
/**
 * Database Migration Runner
 * 
 * Usage:
 *   php migrate.php up         - Run all pending migrations
 *   php migrate.php down       - Rollback last migration batch
 *   php migrate.php fresh      - Drop all tables and re-run migrations
 *   php migrate.php status     - Show migration status
 */

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';

// Load configuration
$dbConfig = require BASE_PATH . '/config/Database.php';

try {
    // Connect to database
    $pdo = new PDO(
        sprintf(
            'pgsql:host=%s;port=%d;dbname=%s',
            $dbConfig['host'],
            $dbConfig['port'],
            $dbConfig['database']
        ),
        $dbConfig['username'],
        $dbConfig['password'],
        $dbConfig['options']
    );

    // Create migrations table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS migrations (
            id SERIAL PRIMARY KEY,
            migration VARCHAR(255) UNIQUE NOT NULL,
            batch INTEGER NOT NULL,
            executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

} catch (PDOException $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    
    if (strpos($e->getMessage(), 'does not exist') !== false) {
        echo "\nHint: Create the database first with:\n";
        echo "  CREATE DATABASE {$dbConfig['database']};\n";
    }
    exit(1);
}

// Get command
$command = $argv[1] ?? 'up';

// Get migration files
function getMigrationFiles(): array
{
    $files = glob(BASE_PATH . '/database/migrations/*.php');
    sort($files);
    return $files;
}

// Get executed migrations
function getExecutedMigrations(PDO $pdo): array
{
    $stmt = $pdo->query("SELECT migration FROM migrations ORDER BY id");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

// Get current batch number
function getCurrentBatch(PDO $pdo): int
{
    $stmt = $pdo->query("SELECT COALESCE(MAX(batch), 0) FROM migrations");
    return (int) $stmt->fetchColumn();
}

// Run migrations
function runMigrations(PDO $pdo): void
{
    echo "=================================================\n";
    echo "Running Database Migrations\n";
    echo "=================================================\n\n";

    $files = getMigrationFiles();
    $executed = getExecutedMigrations($pdo);
    $batch = getCurrentBatch($pdo) + 1;
    $count = 0;

    foreach ($files as $file) {
        require_once $file;
        $className = basename($file, '.php');
        $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $className);
        $className = str_replace('_', '', ucwords($className, '_'));
        
        $migration = new $className($pdo);
        $name = $migration->getName();

        if (in_array($name, $executed)) {
            continue;
        }

        echo "Migrating: $name\n";
        try {
            $migration->up();
            
            // Record migration
            $stmt = $pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
            $stmt->execute([$name, $batch]);
            
            $count++;
        } catch (Exception $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n\n";
            exit(1);
        }
    }

    if ($count === 0) {
        echo "No new migrations to run.\n";
    } else {
        echo "\n✓ Migrated $count migration(s) successfully!\n";
    }
    
    echo "\n=================================================\n";
}

// Rollback migrations
function rollbackMigrations(PDO $pdo): void
{
    echo "=================================================\n";
    echo "Rolling Back Migrations\n";
    echo "=================================================\n\n";

    $batch = getCurrentBatch($pdo);
    
    if ($batch === 0) {
        echo "Nothing to rollback.\n";
        return;
    }

    $stmt = $pdo->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
    $stmt->execute([$batch]);
    $migrations = $stmt->fetchAll(PDO::FETCH_COLUMN);

    $files = getMigrationFiles();
    $count = 0;

    foreach ($migrations as $name) {
        // Find the file
        $file = null;
        foreach ($files as $f) {
            if (strpos(basename($f), $name) !== false || strpos($f, str_replace('.php', '', $name)) !== false) {
                $file = $f;
                break;
            }
        }

        if (!$file) {
            echo "  ✗ Migration file not found for: $name\n";
            continue;
        }

        require_once $file;
        $className = basename($file, '.php');
        $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $className);
        $className = str_replace('_', '', ucwords($className, '_'));
        
        $migration = new $className($pdo);

        echo "Rolling back: $name\n";
        try {
            $migration->down();
            
            // Remove from migrations table
            $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = ?");
            $stmt->execute([$name]);
            
            $count++;
        } catch (Exception $e) {
            echo "  ✗ Error: " . $e->getMessage() . "\n\n";
            exit(1);
        }
    }

    if ($count > 0) {
        echo "\n✓ Rolled back $count migration(s) successfully!\n";
    }
    
    echo "\n=================================================\n";
}

// Fresh migration (drop all and re-run)
function freshMigrations(PDO $pdo): void
{
    echo "=================================================\n";
    echo "Fresh Migration (Drop All Tables)\n";
    echo "=================================================\n\n";
    
    echo "WARNING: This will drop all tables!\n";
    echo "Press Ctrl+C to cancel or wait 3 seconds...\n";
    sleep(3);
    
    // Drop all tables
    echo "\nDropping all tables...\n";
    $tables = $pdo->query("
        SELECT tablename FROM pg_tables 
        WHERE schemaname = 'public' 
        AND tablename != 'migrations'
    ")->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($tables as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table CASCADE");
        echo "  ✓ Dropped $table\n";
    }
    
    // Clear migrations table
    $pdo->exec("TRUNCATE TABLE migrations");
    
    echo "\n";
    runMigrations($pdo);
}

// Show migration status
function showStatus(PDO $pdo): void
{
    echo "=================================================\n";
    echo "Migration Status\n";
    echo "=================================================\n\n";

    $files = getMigrationFiles();
    $executed = getExecutedMigrations($pdo);

    echo "Available migrations:\n\n";
    foreach ($files as $file) {
        require_once $file;
        $className = basename($file, '.php');
        $className = preg_replace('/^\d{4}_\d{2}_\d{2}_\d{6}_/', '', $className);
        $className = str_replace('_', '', ucwords($className, '_'));
        
        $migration = new $className($pdo);
        $name = $migration->getName();
        $status = in_array($name, $executed) ? '✓ Ran' : '✗ Pending';
        
        echo "  $status - $name\n";
    }
    
    echo "\n=================================================\n";
}

// Execute command
switch ($command) {
    case 'up':
        runMigrations($pdo);
        break;
        
    case 'down':
    case 'rollback':
        rollbackMigrations($pdo);
        break;
        
    case 'fresh':
        freshMigrations($pdo);
        break;
        
    case 'status':
        showStatus($pdo);
        break;
        
    default:
        echo "Usage: php migrate.php [command]\n\n";
        echo "Available commands:\n";
        echo "  up         Run all pending migrations\n";
        echo "  down       Rollback last migration batch\n";
        echo "  fresh      Drop all tables and re-run migrations\n";
        echo "  status     Show migration status\n";
        exit(1);
}
