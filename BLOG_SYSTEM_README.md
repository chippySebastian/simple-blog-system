# Blog System - Complete Implementation

A comprehensive blog management system built with PHP, featuring user management, blog posts, categories, comments, search functionality, and an admin panel. Uses **mock data stored in sessions** (no database required).

## ✨ Features Implemented

### 1. **User Management** ✅
- ✅ User registration with email validation
- ✅ User login and logout functionality
- ✅ Password reset feature
- ✅ User profile page with update capabilities
- ✅ Change password functionality
- ✅ Avatar support using UI Avatars API
- ✅ Role-based access (Admin/User)

### 2. **Blog Post Management** ✅
- ✅ Create new blog posts with title, content, and featured image
- ✅ Rich text editor (TinyMCE) for post content
- ✅ Edit existing blog posts
- ✅ Delete blog posts with confirmation
- ✅ Draft saving functionality
- ✅ View counter for posts
- ✅ Post excerpts (auto-generated if not provided)
- ✅ Slug generation from titles

### 3. **Post Categories** ✅
- ✅ Create, edit, and delete categories
- ✅ Assign multiple categories to a post
- ✅ Filter posts by category
- ✅ Category pages with post listings
- ✅ Post count per category

### 4. **Comments System** ✅
- ✅ Allow registered users to comment on posts
- ✅ Comment moderation for admin (approve/reject comments)
- ✅ Reply to comments (nested comments)
- ✅ Edit or delete own comments
- ✅ Comment status management (pending/approved/rejected)

### 5. **Search Functionality** ✅
- ✅ Search posts by title, content, or excerpt
- ✅ Display search results with pagination (10 per page)
- ✅ Highlight search terms in results
- ✅ Relevance-based sorting

### 6. **Admin Panel** ✅
- ✅ Dashboard with overview statistics:
  - Total posts, published posts, draft posts
  - Total users, total comments, pending comments
  - Total categories
- ✅ Manage users (view, edit roles, delete)
- ✅ Manage all blog posts
- ✅ Manage categories (create, edit, delete)
- ✅ Moderate comments (approve/reject/delete)
- ✅ Recent activity displays
- ✅ Quick action buttons

### 7. **Frontend** ✅
- ✅ Responsive design using Bootstrap 5
- ✅ Homepage with recent posts and featured content
- ✅ Individual post pages with comments
- ✅ Category pages
- ✅ Author pages
- ✅ User profile pages
- ✅ Search results page
- ✅ Navigation with dropdown menus
- ✅ Flash message notifications
- ✅ Modern, clean UI with icons

## 🚀 Getting Started

### Prerequisites
- PHP 7.4 or higher
- Web server (Apache/Nginx) or PHP built-in server
- Composer (for dependencies)

### Installation

1. **Clone or navigate to the project directory**
   ```bash
   cd c:\Users\AJAY\Projects\simple-blog-system
   ```

2. **Install dependencies (if not already installed)**
   ```bash
   composer install
   ```

3. **Start the PHP development server**
   ```bash
   php -S localhost:8000 -t public
   ```

4. **Access the application**
   Open your browser and visit: `http://localhost:8000`

## 👤 Default Users (Mock Data)

### Admin Account
- **Email:** admin@blog.com
- **Password:** admin123
- **Role:** Administrator (full access)

### Regular Users
- **Email:** john@example.com
- **Password:** password
- **Role:** User

- **Email:** jane@example.com
- **Password:** password
- **Role:** User

## 📁 Project Structure

```
simple-blog-system/
├── app/
│   ├── Controllers/
│   │   ├── Admin/
│   │   │   ├── AdminCategoryController.php
│   │   │   ├── AdminCommentController.php
│   │   │   ├── AdminDashboardController.php
│   │   │   ├── AdminPostController.php
│   │   │   └── AdminUserController.php
│   │   ├── AuthController.php
│   │   ├── BaseController.php
│   │   ├── CategoryController.php
│   │   ├── CommentController.php
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── SearchController.php
│   │   └── UserController.php
│   ├── Core/
│   │   ├── App.php
│   │   ├── Database.php
│   │   └── Router.php
│   ├── Helpers/
│   │   └── AuthHelper.php
│   ├── Middleware/
│   │   ├── AdminMiddleware.php
│   │   └── AuthMiddleware.php
│   ├── Services/
│   │   ├── CategoryService.php
│   │   ├── CommentService.php
│   │   ├── MockDataService.php
│   │   ├── PostService.php
│   │   └── UserService.php
│   └── Views/
│       ├── admin/
│       │   ├── categories/
│       │   ├── comments/
│       │   ├── posts/
│       │   ├── users/
│       │   └── dashboard.php
│       ├── auth/
│       ├── categories/
│       ├── posts/
│       ├── search/
│       ├── user/
│       ├── home.php
│       └── layout.php
├── config/
├── public/
│   └── index.php
├── routes/
│   └── web.php
└── README.md
```

## 🛣️ Route Map

### Public Routes
- `GET /` - Homepage
- `GET /posts` - All posts
- `GET /posts/{id}` - View single post
- `GET /categories` - All categories
- `GET /categories/{id}` - Posts by category
- `GET /authors` - All authors
- `GET /authors/{id}` - Author profile
- `GET /search` - Search posts

### Authentication Routes
- `GET /login` - Login form
- `POST /login` - Login submission
- `GET /register` - Registration form
- `POST /register` - Registration submission
- `GET /logout` - Logout
- `GET /forgot-password` - Forgot password form
- `POST /forgot-password` - Forgot password submission
- `GET /reset-password` - Reset password form
- `POST /reset-password` - Reset password submission

### User Routes (Authenticated)
- `GET /profile` - User profile
- `GET /profile/edit` - Edit profile form
- `POST /profile/update` - Update profile
- `GET /profile/change-password` - Change password form
- `POST /profile/change-password` - Change password submission
- `GET /posts/my-posts` - User's posts
- `GET /posts/create` - Create post form
- `POST /posts/store` - Store new post
- `GET /posts/{id}/edit` - Edit post form
- `POST /posts/{id}/update` - Update post
- `POST /posts/{id}/delete` - Delete post

### Comment Routes (Authenticated)
- `POST /comments/store` - Add comment
- `POST /comments/{id}/update` - Update comment
- `POST /comments/{id}/delete` - Delete comment

### Admin Routes (Admin Only)
- `GET /admin` - Admin dashboard
- `GET /admin/users` - Manage users
- `GET /admin/users/{id}/edit` - Edit user
- `POST /admin/users/{id}/update` - Update user
- `POST /admin/users/{id}/delete` - Delete user
- `GET /admin/posts` - Manage posts
- `POST /admin/posts/{id}/delete` - Delete post
- `GET /admin/categories` - Manage categories
- `GET /admin/categories/create` - Create category
- `POST /admin/categories/store` - Store category
- `GET /admin/categories/{id}/edit` - Edit category
- `POST /admin/categories/{id}/update` - Update category
- `POST /admin/categories/{id}/delete` - Delete category
- `GET /admin/comments` - Moderate comments
- `POST /admin/comments/{id}/approve` - Approve comment
- `POST /admin/comments/{id}/reject` - Reject comment
- `POST /admin/comments/{id}/delete` - Delete comment

## 🎨 Key Features

### Mock Data Service
All data is stored in PHP sessions, making this a true zero-database application:
- **Automatic seeding** of sample data on first load
- **Auto-increment IDs** for all entities
- **Full CRUD operations** through session storage
- **Search and filter** capabilities
- **Data persistence** throughout the session

### Rich Text Editor
- TinyMCE integration for post content
- Support for formatting, lists, links, and more
- HTML content support

### Responsive Design
- Bootstrap 5 framework
- Mobile-friendly layouts
- Clean, modern UI
- Bootstrap Icons

### Security Features
- Password hashing with PHP's `password_hash()`
- CSRF token support
- Role-based access control
- Session management
- Input sanitization

### User Experience
- Flash messages for user feedback
- Confirmation dialogs for destructive actions
- Pagination for search results
- View counters
- Highlighted search terms
- Nested comments with replies

## 📝 Usage Guide

### For Regular Users

1. **Register/Login**
   - Create an account or use existing credentials
   - Verify email is auto-completed for mock data

2. **Create Posts**
   - Navigate to "Create Post" from dropdown menu
   - Use rich text editor for formatting
   - Add featured image URL
   - Select categories
   - Save as draft or publish immediately

3. **Manage Posts**
   - View your posts in "My Posts"
   - Edit or delete your posts
   - View post statistics (views, comments)

4. **Interact**
   - Comment on posts
   - Reply to other comments
   - Edit/delete your own comments
   - Browse by categories
   - Search for posts

### For Administrators

1. **Access Admin Panel**
   - Login with admin credentials
   - Click "Admin" in navigation

2. **Dashboard**
   - View system statistics
   - Monitor recent activity
   - Quick action buttons

3. **Manage Users**
   - Edit user roles
   - Delete users
   - Toggle email verification

4. **Manage Posts**
   - View all posts (including drafts)
   - Delete any post

5. **Manage Categories**
   - Create new categories
   - Edit existing categories
   - Delete categories

6. **Moderate Comments**
   - Filter by status (pending/approved/rejected)
   - Approve or reject comments
   - Delete spam/inappropriate comments

## 🔧 Configuration

All configuration is in the `config/` directory:
- `App.php` - Application settings
- `Database.php` - Database config (not used for mock data)

## 🎯 Feature Highlights

### Smart Routing
- Dynamic route parameters
- RESTful URL structure
- Proper route ordering to avoid conflicts

### State Management
- Session-based authentication
- Flash messages for user feedback
- Persistent mock data across requests

### UI/UX
- Hover effects on cards
- Smooth transitions
- Clear visual hierarchy
- Intuitive navigation
- Consistent design language

## 📊 Mock Data Included

### Users
- 1 Admin user
- 2 Regular users

### Posts
- 4 Published posts
- 1 Draft post
- Various categories assigned
- Featured images from Unsplash

### Categories
- Programming
- Web Development
- Software Engineering
- Database

### Comments
- Sample comments on posts
- Nested replies
- Different statuses (approved/pending)

## 🚀 Next Steps (Future Enhancements)

While all required features are implemented, here are potential enhancements:

1. **Database Integration**
   - MySQL/PostgreSQL support
   - Migrations for schema
   - Persistent data storage

2. **Email Functionality**
   - Real email verification
   - Password reset emails
   - Comment notifications

3. **File Uploads**
   - Image upload for featured images
   - Avatar uploads
   - Media library

4. **Advanced Features**
   - Post tags
   - Social sharing
   - RSS feeds
   - API endpoints
   - Export/Import functionality

5. **Performance**
   - Caching layer
   - Image optimization
   - Lazy loading

## 📄 License

This project is open-source and available for educational and commercial use.

## 👨‍💻 Development

Built with:
- PHP 7.4+
- Bootstrap 5
- TinyMCE
- Bootstrap Icons
- Session-based storage (no database)

---

**Note:** This implementation uses mock data stored in PHP sessions. All data will be reset when the session expires or is cleared. For production use, integrate with a database backend.
