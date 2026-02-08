<div class="container">
    <h1 class="mb-4"><i class="bi bi-people"></i> Authors</h1>
    
    <div class="row">
        <?php foreach ($users as $user): ?>
        <div class="col-md-4 col-lg-3 mb-4">
            <div class="card text-center shadow-sm">
                <div class="card-body">
                    <img src="<?= htmlspecialchars($user['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($user['full_name'])) ?>" 
                         class="rounded-circle mb-2" width="80" height="80"
                         alt="<?= htmlspecialchars($user['full_name']) ?>">
                    <h5 class="card-title">
                        <a href="/authors/<?= $user['id'] ?>" class="text-decoration-none">
                            <?= htmlspecialchars($user['full_name']) ?>
                        </a>
                    </h5>
                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                        <?= ucfirst($user['role']) ?>
                    </span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
