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

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(BASE_PATH);
$dotenv->load();

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

    $categoryIds = [];
    foreach ($categories as $category) {
        $stmt = $pdo->prepare("
            INSERT INTO categories (name, slug, description)
            VALUES (?, ?, ?)
            ON CONFLICT (slug) DO UPDATE SET name = EXCLUDED.name
            RETURNING id
        ");
        $stmt->execute([$category['name'], $category['slug'], $category['description']]);
        $categoryIds[$category['slug']] = $stmt->fetchColumn();
    }
    echo "  ✓ Sample categories created\n\n";

    // Create sample user
    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE username = 'john_doe'");
    $sampleUserId = null;
    if ($stmt->fetchColumn() == 0) {
        echo "Creating sample user...\n";
        $stmt = $pdo->prepare("
            INSERT INTO users (username, email, password, full_name, bio, role, email_verified)
            VALUES (?, ?, ?, ?, ?, ?, ?)
            RETURNING id
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
        $sampleUserId = $stmt->fetchColumn();
        echo "  ✓ Sample user created\n";
        echo "    Username: john_doe\n";
        echo "    Password: password123\n\n";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'john_doe'");
        $stmt->execute();
        $sampleUserId = $stmt->fetchColumn();
        echo "✓ Sample user already exists\n\n";
    }
    
    // Get admin user ID
    $stmt = $pdo->prepare("SELECT id FROM users WHERE username = 'admin'");
    $stmt->execute();
    $adminId = $stmt->fetchColumn();
    
    // Create sample posts
    echo "Creating sample posts...\n";
    $stmt = $pdo->query("SELECT COUNT(*) FROM posts");
    if ($stmt->fetchColumn() == 0) {
        $posts = [
            [
                'user_id' => $adminId,
                'category_id' => $categoryIds['programming'],
                'title' => 'Getting Started with PHP',
                'slug' => 'getting-started-with-php',
                'excerpt' => 'Learn the basics of PHP and start building dynamic websites today.',
                'content' => '<p>PHP is a popular general-purpose scripting language that is especially suited to web development. Fast, flexible and pragmatic, PHP powers everything from your blog to the most popular websites in the world.</p><p>In this post, we\'ll explore the basics of PHP and how you can get started building dynamic websites.</p><h3>Why PHP?</h3><p>PHP is easy to learn, widely supported, and powers millions of websites worldwide.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1599507593499-a3f7d7d97667?w=800&h=450&fit=crop',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-5 days')),
                'views' => 150
            ],
            [
                'user_id' => $sampleUserId,
                'category_id' => $categoryIds['web-development'],
                'title' => 'Understanding MVC Architecture',
                'slug' => 'understanding-mvc-architecture',
                'excerpt' => 'A comprehensive guide to understanding the MVC architectural pattern.',
                'content' => '<p>Model-View-Controller (MVC) is a software design pattern commonly used for developing user interfaces that divides an application into three interconnected components.</p><h3>Model</h3><p>The Model represents the data and business logic of the application.</p><h3>View</h3><p>The View is responsible for rendering the user interface.</p><h3>Controller</h3><p>The Controller handles user input and updates the Model and View accordingly.</p>',
                'featured_image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-3 days')),
                'views' => 230
            ],
            [
                'user_id' => $adminId,
                'category_id' => $categoryIds['programming'],
                'title' => '10 Tips for Writing Clean Code',
                'slug' => '10-tips-for-writing-clean-code',
                'excerpt' => 'Improve your code quality with these essential tips for writing clean code.',
                'content' => '<p>Clean code is code that is easy to understand, easy to change, and easy to maintain. Here are 10 tips to help you write cleaner code:</p><ol><li>Use meaningful variable names</li><li>Keep functions small and focused</li><li>Write comments for complex logic</li><li>Follow consistent coding standards</li><li>Avoid deep nesting</li><li>Write tests</li><li>Refactor regularly</li><li>Use design patterns</li><li>Keep it simple</li><li>Review your code</li></ol>',
                'featured_image' => 'https://images.unsplash.com/photo-1461749280684-dccba630e2f6?w=800&h=450&fit=crop',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-1 day')),
                'views' => 420
            ],
            [
                'user_id' => $sampleUserId,
                'category_id' => $categoryIds['technology'],
                'title' => 'Introduction to RESTful APIs',
                'slug' => 'introduction-to-restful-apis',
                'excerpt' => 'Learn the fundamentals of RESTful API design and implementation.',
                'content' => '<p>REST (Representational State Transfer) is an architectural style for designing networked applications. RESTful APIs use HTTP requests to perform CRUD operations.</p><h3>Key Principles</h3><ul><li>Stateless communication</li><li>Resource-based URLs</li><li>HTTP methods (GET, POST, PUT, DELETE)</li><li>JSON data format</li></ul>',
                'featured_image' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800&h=450&fit=crop',
                'status' => 'published',
                'published_at' => date('Y-m-d H:i:s', strtotime('-2 days')),
                'views' => 310
            ],
        ];
        
        $postIds = [];
        foreach ($posts as $post) {
            $stmt = $pdo->prepare("
                INSERT INTO posts (user_id, category_id, title, slug, excerpt, content, featured_image, status, published_at, views)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                RETURNING id
            ");
            $stmt->execute([
                $post['user_id'],
                $post['category_id'],
                $post['title'],
                $post['slug'],
                $post['excerpt'],
                $post['content'],
                $post['featured_image'],
                $post['status'],
                $post['published_at'],
                $post['views']
            ]);
            $postIds[] = $stmt->fetchColumn();
        }
        echo "  ✓ Sample posts created\n\n";
        
        // Create sample comments
        echo "Creating sample comments...\n";
        if (!empty($postIds)) {
            $comments = [
                [
                    'post_id' => $postIds[0],
                    'user_id' => $sampleUserId,
                    'content' => 'Great article! Very helpful for beginners.',
                    'status' => 'approved'
                ],
                [
                    'post_id' => $postIds[0],
                    'user_id' => $adminId,
                    'content' => 'I learned a lot from this. Thanks for sharing!',
                    'status' => 'approved'
                ],
                [
                    'post_id' => $postIds[1],
                    'user_id' => $adminId,
                    'content' => 'The MVC pattern really helps organize code better.',
                    'status' => 'approved'
                ],
                [
                    'post_id' => $postIds[2],
                    'user_id' => $sampleUserId,
                    'content' => 'These tips are gold! Implementing them right away.',
                    'status' => 'approved'
                ],
            ];
            
            foreach ($comments as $comment) {
                $stmt = $pdo->prepare("
                    INSERT INTO comments (post_id, user_id, content, status)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([
                    $comment['post_id'],
                    $comment['user_id'],
                    $comment['content'],
                    $comment['status']
                ]);
            }
            echo "  ✓ Sample comments created\n\n";
        }
    } else {
        echo "✓ Sample posts already exist\n\n";
    }

    echo "=================================================\n";
    echo "✓ Database seeded successfully!\n";
    echo "=================================================\n\n";

} catch (PDOException $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    exit(1);
}
