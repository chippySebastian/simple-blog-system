# Simple Blog System

A full-featured blog system built with PHP, following MVC architecture and OOP principles. Includes custom migration system, PostgreSQL database, and comprehensive admin panel.

## ✨ Highlights

- 🎯 **Modern MVC Architecture** - Clean separation of concerns
- 🗄️ **PostgreSQL Database** - Robust and scalable database backend
- 🔄 **Custom Migration System** - Version control for your database schema
- 🔐 **Enhanced Security** - CSRF protection, security headers, session hardening, password hashing
- 🖼️ **Image Upload System** - Automatic thumbnail generation, validation, secure storage
- 👥 **User Management** - Registration, profiles, roles (user/admin)
- 📝 **Full Blog Features** - Posts, categories, comments, search
- 🎨 **Admin Dashboard** - Complete admin panel with statistics
- 🧪 **Testing Ready** - PHPUnit integration with test structure

## 🎯 Features

### User Management
- User registration with email verification
- Login and logout functionality
- Password reset feature
- User profile management

### Blog Post Management
- Create, edit, and delete posts
- Rich text editor support
- Draft saving functionality
- Featured image uploads with automatic thumbnail generation
- Multiple image sizes (original, medium, thumbnail)
- Image validation (type, size, MIME checking)
- Secure image storage outside web root

### Categories & Tags
- Create and manage categories
- Assign multiple categories to posts
- Filter posts by category

### Comments System
- Comments on posts
- Comment moderation
- Reply to comments
- Edit or delete own comments

### Search & Discovery
- Search posts by title, content, or author
- Search results with pagination
- Highlight search terms

### Admin Panel
- Dashboard with statistics
- User management
- Content management
- Comment moderation

### Security Features
- **CSRF Protection** - Token-based validation on all forms and POST requests
- **Security Headers** - Protection against XSS, clickjacking, and MIME-sniffing
- **Session Security** - HTTPOnly cookies, SameSite attribute, session timeout, fingerprinting
- **Password Security** - Bcrypt hashing with strong passwords
- **SQL Injection Prevention** - Prepared statements with PDO
- **Input Sanitization** - HTML special characters escaping
- **Secure File Storage** - Images stored outside web root with validation
- **Environment Variables** - Sensitive credentials in .env file

## 📦 Tech Stack

- **Language:** PHP 7.4+ (PHP 8.5 compatible)
- **Database:** PostgreSQL 12+
- **Architecture:** MVC (Model-View-Controller)
- **Design:** OOP (Object-Oriented Programming)
- **Authentication:** Session-based with password hashing
- **Migrations:** Custom migration system
- **Image Processing:** Intervention Image v3 (GD driver)
- **Environment:** dotenv for configuration

## 🚀 Quick Start

### Prerequisites
- PHP 7.4 or higher (tested up to PHP 8.5) with required extensions:
  - pdo
  - pdo_pgsql
  - pgsql
  - mbstring
  - json
  - gd (for image processing)
- PostgreSQL 12 or higher
- Composer (for dependency management)

### Installation

1. **Install Dependencies**
   ```bash
   composer install
   ```

2. **Create Database**
   ```bash
   # Using psql command line
   psql -U postgres
   CREATE DATABASE simple_blog_db;
   \q
   ```

3. **Configure Environment**
   - Copy `.env.example` to `.env`
   - Update database credentials in `.env`:
     ```env
     DB_DRIVER=pgsql
     DB_HOST=localhost
     DB_PORT=5432
     DB_NAME=simple_blog_db
     DB_USER=postgres
     DB_PASSWORD=your_password
     ```

4. **Run Migrations**
   ```bash
   composer migrate
   # or
   php migrate.php up
   ```

5. **Seed Database (Optional)**
   ```bash
   composer db:seed
   ```
   This creates:
   - Admin user: `admin` / `admin123`
   - Sample user: `john_doe` / `password123`
   - Sample categories

6. **Start Development Server**
   ```bash
   composer serve
   ```

7. **Access Application**
   - Open: http://localhost:8000
   - Login with admin credentials: `admin` / `admin123`

## 📁 Project Structure

```
simple-blog-system/
├── public/              # Web root (entry points)
├── app/                 # Application code
│   ├── Core/           # Core classes (Database, Router, App, Migration)
│   ├── Controllers/    # Request handlers
│   ├── Models/         # Data models
│   ├── Services/       # Business logic
│   ├── Validators/     # Form validation
│   ├── Middleware/     # Request middleware
│   ├── Helpers/        # Utility helpers
│   └── Views/          # Template files
├── routes/             # Route definitions
├── config/             # Configuration files
├── database/           # Migrations and seeds
│   └── migrations/     # Database migration files
├── storage/            # Uploads, cache, logs
├── tests/              # Test files
├── migrate.php         # Migration runner
├── seed.php            # Database seeder
└── docs/              # Documentation
```

## 🗄️ Database Migrations

The project uses a custom migration system for database version control:

### Migration Features
- **Version Control**: Track and manage database schema changes
- **Rollback Support**: Undo migrations in batches
- **Fresh Migrations**: Drop all tables and rebuild from scratch
- **Automatic Tracking**: Migrations table tracks execution history

### Migration Files
All migrations are located in `database/migrations/`:
1. Create users table
2. Create categories table
3. Create posts table
4. Create comments table
5. Create tags table
6. Create post_tags pivot table
7. Add database indexes

### Usage Examples
```bash
# Check which migrations have run
composer migrate:status

# Run all pending migrations
composer migrate

# Rollback the last batch
composer migrate:rollback

# Fresh start (WARNING: drops all data!)
composer migrate:fresh

# Setup complete database with sample data
composer db:setup
```

For detailed migration documentation, see [database/migrations/README.md](database/migrations/README.md).

## �️ Database Schema

The migration system creates the following tables:

### Tables Overview

**users** - User accounts and authentication
- id, username, email, password, full_name, bio, avatar
- role (user/admin), status, email verification
- created_at, updated_at

**categories** - Blog post categories
- id, name, slug, description
- created_at, updated_at

**posts** - Blog posts
- id, user_id, category_id, title, slug, excerpt, content
- featured_image, status (draft/published), views
- published_at, created_at, updated_at

**comments** - Post comments and replies
- id, post_id, user_id, parent_id (for replies)
- content, status (approved/pending/rejected)
- created_at, updated_at

**tags** - Content tags
- id, name, slug, created_at

**post_tags** - Many-to-many relationship between posts and tags
- post_id, tag_id

**migrations** - Migration tracking (auto-created)
- id, migration, batch, executed_at

### Relationships
- Users have many posts and comments
- Posts belong to users and categories
- Posts have many comments and tags
- Comments belong to posts and users
- Comments can have child comments (replies)

## �📖 Documentation

- **Migration System** - See [database/migrations/README.md](database/migrations/README.md)
- **Configuration** - Check `.env.example` for available settings
- **Database Setup** - Run `composer db:setup` for fresh installation
- **Default Credentials** - admin/admin123 (created via seeding)

## 🔧 Available Commands

```bash
# Server
composer serve             # Start development server

# Database Migrations
composer migrate           # Run pending migrations
composer migrate:status    # Check migration status
composer migrate:rollback  # Rollback last batch
composer migrate:fresh     # Drop all tables and re-run migrations
composer db:seed           # Seed database with sample data
composer db:setup          # Fresh migration + seed (complete setup)

# Manual migration commands
php migrate.php up         # Run migrations
php migrate.php down       # Rollback migrations
php migrate.php fresh      # Fresh migration
php migrate.php status     # Migration status
php seed.php               # Seed database

# Testing
composer test              # Run all tests
composer test:unit         # Run unit tests
composer test:integration  # Run integration tests

# Code Quality
composer lint              # Check code style
composer lint:fix          # Fix code style
composer analyze           # Static analysis with PHPStan

# Utilities
php check.php              # System health check
php status.php             # Application status
composer install           # Install dependencies
composer update            # Update dependencies
```

## 🔐 Security Features

- ✓ Password hashing with bcrypt
- ✓ CSRF token protection
- ✓ Input sanitization (XSS prevention)
- ✓ Prepared statements (SQL injection prevention)
- ✓ Session-based authentication
- ✓ Error logging
- ✓ Secure HTTP headers ready

## 🐛 Troubleshooting

**"composer: command not found"**
- Install Composer: https://getcomposer.org/download/

**"Cannot connect to database"**
- Ensure PostgreSQL is running
- Create database: `CREATE DATABASE simple_blog_db;`
- Update `.env` with correct credentials
- Check if pdo_pgsql extension is enabled: `php -m | grep pgsql`

**"vendor/autoload.php not found"**
- Run: `composer install`

**"Port 8000 already in use"**
- Use: `php -S localhost:8001 -t public/`

**"pdo_pgsql extension not found"**
- Windows: Edit php.ini, uncomment `extension=pdo_pgsql` and `extension=pgsql`
- Linux: Install `php-pgsql` package
- Restart web server/PHP

**"Database 'simple_blog_db' does not exist"**
- Create database first, then run migrations
- Or use `composer db:setup` to create everything

For more issues, run: `php check.php`



## 📝 License

MIT License - feel free to use this project as you wish.

## 👤 Author

**Chippy Sebastian**

---

**Ready to get started?** Follow the [Quick Start](#-quick-start) guide above or run `composer db:setup` for instant setup!
