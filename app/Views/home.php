<div class="container-fluid bg-light py-5 mb-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1 class="display-4 fw-bold">Welcome to BlogSystem</h1>
                <p class="lead">Discover amazing content from talented writers around the world</p>
                <div class="mt-4">
                    <a href="/posts" class="btn btn-primary btn-lg me-2">Browse Posts</a>
                    <?php if (!$isAuthenticated): ?>
                        <a href="/register" class="btn btn-outline-primary btn-lg">Join Now</a>
                    <?php else: ?>
                        <a href="/posts/create" class="btn btn-success btn-lg">Create Post</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <!-- Featured Categories -->
    <div class="row mb-5">
        <div class="col-12">
            <h2 class="mb-4"><i class="bi bi-tags"></i> Categories</h2>
            <div class="row">
                <?php foreach (array_slice($categories, 0, 4) as $category): ?>
                <div class="col-md-3 mb-3">
                    <a href="/categories/<?= $category['id'] ?>" class="text-decoration-none">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($category['name']) ?></h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars($category['description'] ?? '') ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Recent Posts -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2><i class="bi bi-newspaper"></i> Recent Posts</h2>
                <a href="/posts" class="btn btn-outline-primary">View All</a>
            </div>
            <div class="row">
                <?php if (empty($recentPosts)): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            No posts available yet. Be the first to create one!
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($recentPosts as $post): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card post-card h-100 border-0 shadow-sm">
                            <?php if (!empty($post['featured_image'])): ?>
                            <img src="/images/thumbnail/<?= htmlspecialchars($post['featured_image']) ?>" 
                                 class="card-img-top featured-image" 
                                 alt="<?= htmlspecialchars($post['title']) ?>">
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title">
                                    <a href="/posts/<?= $post['id'] ?>" class="text-decoration-none text-dark">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </h5>
                                <p class="card-text text-muted small">
                                    <?= htmlspecialchars(substr($post['excerpt'], 0, 120)) ?>...
                                </p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bi bi-person"></i>
                                        <a href="/authors/<?= $post['user_id'] ?>" class="text-decoration-none">
                                            <?= htmlspecialchars($post['author_name']) ?>
                                        </a>
                                    </small>
                                    <small class="text-muted">
                                        <i class="bi bi-eye"></i> <?= $post['views'] ?? 0 ?>
                                    </small>
                                </div>
                                <small class="text-muted">
                                    <i class="bi bi-calendar"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </small>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Call to Action -->
    <div class="row">
        <div class="col-12">
            <div class="card bg-primary text-white border-0 shadow">
                <div class="card-body text-center py-5">
                    <h3 class="mb-3">Start Your Writing Journey Today</h3>
                    <p class="mb-4">Share your thoughts, stories, and experiences with the world</p>
                    <?php if (!$isAuthenticated): ?>
                        <a href="/register" class="btn btn-light btn-lg">Get Started</a>
                    <?php else: ?>
                        <a href="/posts/create" class="btn btn-light btn-lg">Write a Post</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
