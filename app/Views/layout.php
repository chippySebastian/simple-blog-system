<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Blog System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
        .navbar-brand {
            font-weight: bold;
            font-size: 1.5rem;
        }
        .post-card {
            transition: transform 0.2s;
        }
        .post-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .featured-image {
            height: 200px;
            object-fit: cover;
        }
        .comment-box {
            background: #f8f9fa;
            border-left: 3px solid #0d6efd;
            padding: 15px;
            margin: 10px 0;
        }
        .reply-box {
            margin-left: 40px;
            border-left: 2px solid #6c757d;
        }
        mark {
            background-color: #ffeb3b;
            padding: 2px 4px;
        }
    </style>
</head>
<body>
    <?php 
    use App\Helpers\AuthHelper;
    AuthHelper::init();
    $currentUser = AuthHelper::user();
    $isAuthenticated = AuthHelper::isAuthenticated();
    $isAdmin = AuthHelper::isAdmin();
    ?>
    
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-journal-text"></i> BlogSystem
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/posts">Posts</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/categories">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/authors">Authors</a>
                    </li>
                    <?php if ($isAdmin): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/admin">Admin</a>
                    </li>
                    <?php endif; ?>
                </ul>
                
                <!-- Search Form -->
                <form class="d-flex me-3" method="GET" action="/search">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search posts..." 
                           value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                    <button class="btn btn-outline-light" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </form>

                <ul class="navbar-nav">
                    <?php if ($isAuthenticated): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" 
                               data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($currentUser['full_name'] ?? 'User') ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="/profile">My Profile</a></li>
                                <li><a class="dropdown-item" href="/posts/my-posts">My Posts</a></li>
                                <li><a class="dropdown-item" href="/posts/create">Create Post</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="/logout">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/login">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/register">Register</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php 
    $flash = AuthHelper::getFlash();
    if ($flash): 
    ?>
    <div class="container mt-3">
        <div class="alert alert-<?= $flash['type'] === 'error' ? 'danger' : $flash['type'] ?> alert-dismissible fade show">
            <?= $flash['message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
    <?php endif; ?>

    <!-- Main Content -->
    <main class="py-4">
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white mt-5 py-4">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>BlogSystem</h5>
                    <p>A modern blog platform built with PHP</p>
                </div>
                <div class="col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="list-unstyled">
                        <li><a href="/" class="text-white-50">Home</a></li>
                        <li><a href="/posts" class="text-white-50">Posts</a></li>
                        <li><a href="/categories" class="text-white-50">Categories</a></li>
                    </ul>
                </div>
                <div class="col-md-3">
                    <h6>Account</h6>
                    <ul class="list-unstyled">
                        <?php if ($isAuthenticated): ?>
                            <li><a href="/profile" class="text-white-50">Profile</a></li>
                            <li><a href="/logout" class="text-white-50">Logout</a></li>
                        <?php else: ?>
                            <li><a href="/login" class="text-white-50">Login</a></li>
                            <li><a href="/register" class="text-white-50">Register</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
            <hr class="bg-secondary">
            <div class="text-center">
                <p class="mb-0">&copy; <?= date('Y') ?> BlogSystem. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
