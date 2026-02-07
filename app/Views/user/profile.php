<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                         class="rounded-circle mb-3" width="120" height="120"
                         alt="<?= htmlspecialchars($user['full_name']) ?>">
                    <h2><?= htmlspecialchars($user['full_name']) ?></h2>
                    <p class="text-muted"><?= htmlspecialchars($user['email']) ?></p>
                    <?php if (isset($user['bio'])): ?>
                        <p><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                    <?php endif; ?>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                        <?= ucfirst($user['role']) ?>
                    </span>
                    <?php if ($isOwnProfile): ?>
                        <hr>
                        <div class="d-flex gap-2 justify-content-center">
                            <a href="/profile/edit" class="btn btn-primary">
                                <i class="bi bi-pencil"></i> Edit Profile
                            </a>
                            <a href="/profile/change-password" class="btn btn-outline-primary">
                                <i class="bi bi-key"></i> Change Password
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <h3 class="mb-3">Posts by <?= htmlspecialchars($user['full_name']) ?></h3>
            <?php if (empty($posts)): ?>
                <div class="alert alert-info">No posts yet.</div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <?php if ($post['status'] === 'published' || $isOwnProfile): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="/posts/<?= $post['id'] ?>"><?= htmlspecialchars($post['title']) ?></a>
                                <?php if ($post['status'] === 'draft'): ?>
                                    <span class="badge bg-warning">Draft</span>
                                <?php endif; ?>
                            </h5>
                            <p class="card-text text-muted"><?= htmlspecialchars(substr($post['excerpt'], 0, 150)) ?>...</p>
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?> · 
                                <i class="bi bi-eye"></i> <?= $post['views'] ?? 0 ?> views
                            </small>
                        </div>
                    </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
