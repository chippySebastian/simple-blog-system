# 🔐 Test Credentials

## Quick Access Credentials for Testing

### 👨‍💼 Administrator Account
```
Email:    admin@blog.com
Password: admin123
Role:     Admin (Full Access)
```

**What you can do:**
- Access Admin Dashboard (/admin)
- Manage all users
- Manage all posts (including others' posts)
- Create/Edit/Delete categories
- Moderate all comments (approve/reject/delete)
- View system statistics
- Delete any content

---

### 👤 Regular User Account #1
```
Email:    john@example.com
Password: password
Role:     User
```

**What you can do:**
- Create and manage your own posts
- Comment on any post
- Reply to comments
- Edit your profile
- Change your password
- View all published content

---

### 👤 Regular User Account #2
```
Email:    jane@example.com
Password: password
Role:     User
```

**What you can do:**
- Same as Regular User #1
- Test multi-user interactions
- Comment and reply to others

---

## 🚀 Getting Started

### Option 1: Test Admin Features
1. Go to: http://localhost:8000/login
2. Use: `admin@blog.com` / `admin123`
3. Click "Admin" in navigation
4. Explore dashboard and management tools

### Option 2: Test User Features
1. Go to: http://localhost:8000/login  
2. Use: `john@example.com` / `password`
3. Click "Create Post" in dropdown
4. Write a blog post and publish

### Option 3: Register New Account
1. Go to: http://localhost:8000/register
2. Fill in the form with your details
3. You'll be auto-logged in as a regular user

---

## 📊 Pre-loaded Mock Data

### Posts (5 total)
- 4 Published posts with different authors
- 1 Draft post
- Various categories assigned
- Featured images included

### Categories (4 total)
- Programming
- Web Development  
- Software Engineering
- Database

### Comments
- Sample comments on posts
- Nested reply examples
- Mix of approved and pending comments

### Users (3 total)
- 1 Admin
- 2 Regular users

---

## 🎯 Test Scenarios

### Scenario 1: Complete User Journey
1. Register new account
2. Create a blog post
3. Comment on another post
4. Edit your profile
5. View other authors

### Scenario 2: Admin Management
1. Login as admin
2. View dashboard statistics
3. Create a new category
4. Approve pending comments
5. Edit user roles

### Scenario 3: Content Interaction
1. Browse posts
2. Search for content
3. Filter by category
4. Read and comment
5. Reply to comments

---

## ⚠️ Important Notes

- **Session-Based**: All data is stored in PHP sessions
- **Auto-Reset**: Data persists during your browser session
- **Mock Data**: Sample data loads automatically on first visit
- **No Database**: This system uses session storage (no DB needed)

---

## 🔄 Reset Data

To reset all data to default:
1. Clear browser cookies/cache
2. Or restart the PHP server
3. Visit the site again - mock data will reload

---

## 📞 Quick Links

- **Homepage**: http://localhost:8000
- **Login**: http://localhost:8000/login
- **Register**: http://localhost:8000/register
- **Admin Panel**: http://localhost:8000/admin
- **All Posts**: http://localhost:8000/posts
- **Categories**: http://localhost:8000/categories

---

**Happy Testing! 🎉**
