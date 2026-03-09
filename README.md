# Simple Blog System

Simple Blog System is a PHP application for publishing and managing blog content. It follows an MVC structure, uses PostgreSQL, and includes a custom migration workflow for database changes.

## What this project includes

- User registration, login, profile management, and role support (user/admin)
- Full post workflow: create, edit, delete, publish, and draft handling
- Categories and tags for content organization
- Comment system with moderation and reply support
- Search by title, content, and author
- Admin dashboard for users, posts, and moderation
- Image upload support with validation and thumbnail generation
- Security protections such as CSRF tokens, prepared statements, and secure sessions

## Tech stack

- PHP 7.4+ (compatible with newer versions)
- PostgreSQL 12+
- MVC + OOP architecture
- Session-based authentication
- Custom migration system
- Intervention Image v3 (GD)
- dotenv for environment configuration

## Prerequisites

- PHP 7.4 or higher with:
  - `pdo`
  - `pdo_pgsql`
  - `pgsql`
  - `mbstring`
  - `json`
  - `gd`
- PostgreSQL 12 or higher
- Composer

## Installation

1. Install dependencies:

```bash
composer install
```

2. Create the database:

```bash
psql -U postgres
CREATE DATABASE simple_blog_db;
\q
```

3. Configure environment variables:

- Copy `.env.example` to `.env`
- Update database values:

```env
DB_DRIVER=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_NAME=simple_blog_db
DB_USER=postgres
DB_PASSWORD=your_password
```

- Configure Mailtrap for password reset emails:

```env
MAILTRAP_API_KEY=your_mailtrap_api_key
MAILTRAP_USE_SANDBOX=true
MAILTRAP_INBOX_ID=your_mailtrap_inbox_id
MAIL_FROM=noreply@simpleblog.com
MAIL_FROM_NAME="Simple Blog System"
```

4. Run migrations:

```bash
composer migrate
# or
php migrate.php up
```

5. (Optional) Seed sample data:

```bash
composer db:seed
```

This creates:

- Admin user: `admin` / `admin123`
- Sample user: `john_doe` / `password123`
- Sample categories

6. Start the development server:

```bash
composer serve
```

7. Open the application at `http://localhost:8000`.

## Project structure

```text
simple-blog-system/
|-- public/               # Web root and entry points
|-- app/
|   |-- Core/             # Core classes (Database, Router, App, Migration)
|   |-- Controllers/      # Request handlers
|   |-- Models/           # Data models
|   |-- Services/         # Business logic
|   |-- Validators/       # Validation rules
|   |-- Middleware/       # Request middleware
|   |-- Helpers/          # Utility helpers
|   '-- Views/            # Templates
|-- routes/               # Route definitions
|-- config/               # App configuration
|-- database/
|   '-- migrations/       # Migration files
|-- storage/              # Uploads, cache, logs
|-- tests/                # Automated tests
|-- migrate.php           # Migration runner
|-- seed.php              # Database seeder
'-- docs/                 # Project docs
```

## Migrations

Migrations are stored in `database/migrations/` and tracked by a `migrations` table.

Useful commands:

```bash
composer migrate            # Run pending migrations
composer migrate:status     # Show migration state
composer migrate:rollback   # Roll back last batch
composer migrate:fresh      # Drop all tables and migrate again
composer db:setup           # Fresh migration + seed
```

Manual alternatives:

```bash
php migrate.php up
php migrate.php down
php migrate.php fresh
php migrate.php status
php seed.php
```

Detailed guide: [database/migrations/README.md](database/migrations/README.md)

## Database schema (summary)

Core tables:

- `users`: account and profile data
- `categories`: post categories
- `posts`: blog posts
- `comments`: comments and threaded replies
- `tags`: post tags
- `post_tags`: post/tag pivot table
- `migrations`: migration history

Relationship summary:

- Users have many posts and comments
- Posts belong to users and categories
- Posts have many comments and tags
- Comments belong to posts and users
- Comments support parent-child replies

## Available commands

```bash
# Development server
composer serve

# Migrations and seed
composer migrate
composer migrate:status
composer migrate:rollback
composer migrate:fresh
composer db:seed
composer db:setup

# Tests
composer test
composer test:unit
composer test:integration

# Quality tools
composer lint
composer lint:fix
composer analyze
```

## Security notes

- Password hashing with bcrypt
- CSRF protection on form submissions
- Input sanitization for XSS mitigation
- Prepared statements for SQL injection prevention
- Session hardening and secure cookie settings
- Security headers support

## Troubleshooting

If `composer` is not found:

- Install Composer: https://getcomposer.org/download/

If the app cannot connect to PostgreSQL:

- Confirm PostgreSQL is running
- Confirm the database exists: `CREATE DATABASE simple_blog_db;`
- Check `.env` credentials
- Verify extensions: `php -m | grep pgsql`

If `vendor/autoload.php` is missing:

- Run `composer install`

If port `8000` is busy:

- Run `php -S localhost:8001 -t public/`

If `pdo_pgsql` is missing:

- Windows: enable `extension=pdo_pgsql` and `extension=pgsql` in `php.ini`
- Linux: install the `php-pgsql` package
- Restart PHP/web server

## License

MIT

## Author

Chippy Sebastian
