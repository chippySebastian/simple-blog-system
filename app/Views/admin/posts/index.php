<div class="container-fluid">
    <h1 class="mb-4"><i class="bi bi-file-text"></i> Manage Posts</h1>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Author</th>
                            <th>Status</th>
                            <th>Views</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                        <tr>
                            <td><?= $post['id'] ?></td>
                            <td>
                                <a href="/posts/<?= $post['id'] ?>">
                                    <?= htmlspecialchars(substr($post['title'], 0, 50)) ?>
                                </a>
                            </td>
                            <td><?= htmlspecialchars($post['author_name']) ?></td>
                            <td>
                                <span class="badge bg-<?= $post['status'] === 'published' ? 'success' : 'warning' ?>">
                                    <?= $post['status'] ?>
                                </span>
                            </td>
                            <td><?= $post['views'] ?? 0 ?></td>
                            <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                            <td>
                                <a href="/posts/<?= $post['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form method="POST" action="/admin/posts/<?= $post['id'] ?>/delete" 
                                      class="d-inline" onsubmit="return confirm('Delete this post?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
