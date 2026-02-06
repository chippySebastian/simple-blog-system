<div class="container">
    <h1 class="mb-4">
        <i class="bi bi-tag"></i> <?= htmlspecialchars($category['name']) ?>
    </h1>
    <p class="lead text-muted"><?= htmlspecialchars($category['description']) ?></p>
    <hr>

    <div class="row">
        <?php if (empty($posts)): ?>
            <div class="col-12">
                <div class="alert alert-info">No posts in this category yet.</div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card post-card h-100 shadow-sm">
                    <?php if (!empty($post['featured_image'])): ?>
                    <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
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
                    <div class="card-footer bg-transparent">
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
