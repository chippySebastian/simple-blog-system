<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-tags"></i> Manage Categories</h1>
        <a href="/admin/categories/create" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add Category
        </a>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Posts</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?= $category['id'] ?></td>
                            <td><?= htmlspecialchars($category['name']) ?></td>
                            <td><code><?= htmlspecialchars($category['slug']) ?></code></td>
                            <td><?= htmlspecialchars(substr($category['description'], 0, 50)) ?>...</td>
                            <td><span class="badge bg-primary"><?= $category['post_count'] ?></span></td>
                            <td>
                                <a href="/admin/categories/<?= $category['id'] ?>/edit" 
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form method="POST" action="/admin/categories/<?= $category['id'] ?>/delete" 
                                      class="d-inline" onsubmit="return confirm('Delete this category?')">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
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
