<?php
/**
 * Database Seeder
 * Seeds the database with initial/sample data
 * 
 * Usage:
 *   php seed.php
 */

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/vendor/autoload.php';

// Load configuration
$dbConfig = require BASE_PATH . '/config/Database.php';

try {
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

    echo "=================================================\n";
    echo "Seeding Database\n";
    echo "=================================================\n\n";

    // Check if admin exists
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $adminExists = $stmt->fetchColumn() > 0;

    if (!$adminExists) {
        echo "Creating admin user...\n";
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, full_name, role, email_verified)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'admin',
            'admin@blog.com',
            password_hash('admin123', PASSWORD_DEFAULT),
            'Admin User',
            'admin',
            true
        ]);
        echo "  ✓ Admin user created\n";
        echo "    Username: admin\n";
        echo "    Password: admin123\n\n";
    } else {
        echo "✓ Admin user already exists\n\n";
    }

    // Create sample categories
    echo "Creating sample categories...\n";
    $categories = [
        ['name' => 'Technology', 'slug' => 'technology', 'description' => 'Technology news and tutorials'],
        ['name' => 'Programming', 'slug' => 'programming', 'description' => 'Programming tips and tricks'],
        ['name' => 'Web Development', 'slug' => 'web-development', 'description' => 'Web development resources'],
        ['name' => 'Lifestyle', 'slug' => 'lifestyle', 'description' => 'Lifestyle and personal development'],
    ];

    foreach ($categories as $category) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, description)
            VALUES (?, ?, ?)
            ON CONFLICT (slug) DO NOTHING
        ");
        $stmt->execute([$category['name'], $category['slug'], $category['description']]);
    }
    echo "  ✓ Sample categories created\n\n";

    // Create sample user
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'john_doe'");
    if ($stmt->fetchColumn() == 0) {
        echo "Creating sample user...\n";
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, full_name, bio, role, email_verified)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'john_doe',
            'john@example.com',
            password_hash('password123', PASSWORD_DEFAULT),
            'John Doe',
            'A passionate writer and developer',
            'user',
            true
        ]);
        echo "  ✓ Sample user created\n";
        echo "    Username: john_doe\n";
        echo "    Password: password123\n\n";
    }

    echo "=================================================\n";
    echo "✓ Database seeded successfully!\n";
    echo "=================================================\n\n";

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
