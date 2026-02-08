<div class="container">
    <h1 class="mb-4"><i class="bi bi-tags"></i> Categories</h1>
    
    <div class="row">
        <?php foreach ($categories as $category): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h4 class="card-title">
                        <a href="/categories/<?= $category['id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($category['name']) ?>
                        </a>
                    </h4>
                    <p class="card-text text-muted">
                        <?= htmlspecialchars($category['description'] ?? '') ?>
                    </p>
                    <span class="badge bg-primary"><?= $category['post_count'] ?> posts</span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
