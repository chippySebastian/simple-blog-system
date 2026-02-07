<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1><i class="bi bi-newspaper"></i> All Posts</h1>
            <p class="text-muted">Browse our collection of articles</p>
        </div>
    </div>

    <div class="row">
        <?php if (empty($posts)): ?>
            <div class="col-12">
                <div class="alert alert-info">No posts found.</div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card post-card h-100 border-0 shadow-sm">
                    <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                         class="card-img-top featured-image" 
                         alt="<?= htmlspecialchars($post['title']) ?>">
                    <?php endif; ?>
                    <div class="card-body">
                        <?php if ($post['status'] === 'draft'): ?>
                            <span class="badge bg-warning mb-2">Draft</span>
                        <?php endif; ?>
                        <h5 class="card-title">
                            <a href="/posts/<?= $post['id'] ?>" class="text-decoration-none text-dark">
                                <?= htmlspecialchars($post['title']) ?>
                            </a>
                        </h5>
                        <p class="card-text text-muted small">
                            <?= htmlspecialchars(substr($post['excerpt'], 0, 150)) ?>...
                        </p>
                        <?php if (!empty($post['category_names'])): ?>
                        <div class="mb-2">
                            <?php foreach ($post['category_names'] as $catName): ?>
                                <span class="badge bg-secondary"><?= htmlspecialchars($catName) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="card-footer bg-transparent border-top-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                <i class="bi bi-person"></i> <?= htmlspecialchars($post['author_name']) ?>
                            </small>
                            <small class="text-muted">
                                <i class="bi bi-chat"></i> <?= $post['comment_count'] ?>
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
