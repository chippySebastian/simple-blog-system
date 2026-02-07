<div class="container-fluid">
    <h1 class="mb-4"><i class="bi bi-speedometer2"></i> Admin Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Posts</h6>
                            <h2 class="mb-0"><?= $stats['total_posts'] ?></h2>
                        </div>
                        <i class="bi bi-file-text" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Published</h6>
                            <h2 class="mb-0"><?= $stats['published_posts'] ?></h2>
                        </div>
                        <i class="bi bi-check-circle" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Users</h6>
                            <h2 class="mb-0"><?= $stats['total_users'] ?></h2>
                        </div>
                        <i class="bi bi-people" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white shadow">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-uppercase mb-1">Total Comments</h6>
                            <h2 class="mb-0"><?= $stats['total_comments'] ?></h2>
                        </div>
                        <i class="bi bi-chat" style="font-size: 3rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-warning shadow-sm">
                <div class="card-body">
                    <h6>Pending Comments</h6>
                    <h3 class="text-warning"><?= $stats['pending_comments'] ?></h3>
                    <a href="/admin/comments?status=pending" class="btn btn-sm btn-warning">Review</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-secondary shadow-sm">
                <div class="card-body">
                    <h6>Draft Posts</h6>
                    <h3 class="text-secondary"><?= $stats['draft_posts'] ?></h3>
                    <a href="/admin/posts" class="btn btn-sm btn-secondary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-primary shadow-sm">
                <div class="card-body">
                    <h6>Categories</h6>
                    <h3 class="text-primary"><?= $stats['total_categories'] ?></h3>
                    <a href="/admin/categories" class="btn btn-sm btn-primary">Manage</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Links -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0"><i class="bi bi-lightning"></i> Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="/admin/posts" class="btn btn-outline-primary">
                            <i class="bi bi-file-text"></i> Manage Posts
                        </a>
                        <a href="/admin/users" class="btn btn-outline-info">
                            <i class="bi bi-people"></i> Manage Users
                        </a>
                        <a href="/admin/categories" class="btn btn-outline-success">
                            <i class="bi bi-tags"></i> Manage Categories
                        </a>
                        <a href="/admin/comments" class="btn btn-outline-warning">
                            <i class="bi bi-chat-left-text"></i> Moderate Comments
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-clock-history"></i> Recent Posts</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentPosts as $post): ?>
                                <tr>
                                    <td>
                                        <a href="/posts/<?= $post['id'] ?>">
                                            <?= htmlspecialchars(substr($post['title'], 0, 40)) ?>
                                        </a>
                                    </td>
                                    <td><?= htmlspecialchars($post['author']['name']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                            <?= $post['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-chat"></i> Recent Comments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>User</th>
                                    <th>Post</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentComments as $comment): ?>
                                <tr>
                                    <td><?= htmlspecialchars($comment['user']['name']) ?></td>
                                    <td>
                                        <a href="/posts/<?= $comment['post']['id'] ?>">
                                            <?= htmlspecialchars(substr($comment['post']['title'], 0, 30)) ?>
                                        </a>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?= $comment['status'] === 'approved' ? 'success' : 'warning' ?>">
                                            <?= $comment['status'] ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
