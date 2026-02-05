<div class="container">
    <div class="row mb-4">
        <div class="col-12">
            <h1><i class="bi bi-file-text"></i> My Posts</h1>
            <p class="text-muted">Manage your blog posts</p>
            <a href="/posts/create" class="btn btn-success">
                <i class="bi bi-plus-circle"></i> Create New Post
            </a>
        </div>
    </div>

    <div class="row">
        <?php if (empty($posts)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    You haven't created any posts yet. 
                    <a href="/posts/create">Create your first post</a>
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Categories</th>
                                <th>Status</th>
                                <th>Views</th>
                                <th>Comments</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($posts as $post): ?>
                            <tr>
                                <td>
                                    <a href="/posts/<?= $post['id'] ?>">
                                        <?= htmlspecialchars($post['title']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php foreach ($post['category_names'] as $catName): ?>
                                        <span class="badge bg-secondary"><?= htmlspecialchars($catName) ?></span>
                                    <?php endforeach; ?>
                                </td>
                                <td>
                                    <?php if ($post['status'] === 'published'): ?>
                                        <span class="badge bg-success">Published</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= $post['views'] ?? 0 ?></td>
                                <td><?= $post['comment_count'] ?></td>
                                <td><?= date('M d, Y', strtotime($post['created_at'])) ?></td>
                                <td>
                                    <a href="/posts/<?= $post['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="/posts/<?= $post['id'] ?>/delete" 
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
        <?php endif; ?>
    </div>
</div>
