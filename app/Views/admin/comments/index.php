<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1><i class="bi bi-chat-left-text"></i> Moderate Comments</h1>
        <div class="btn-group">
            <a href="/admin/comments?status=all" 
               class="btn btn-<?= $currentStatus === 'all' ? 'primary' : 'outline-primary' ?>">
                All
            </a>
            <a href="/admin/comments?status=pending" 
               class="btn btn-<?= $currentStatus === 'pending' ? 'warning' : 'outline-warning' ?>">
                Pending
            </a>
            <a href="/admin/comments?status=approved" 
               class="btn btn-<?= $currentStatus === 'approved' ? 'success' : 'outline-success' ?>">
                Approved
            </a>
            <a href="/admin/comments?status=rejected" 
               class="btn btn-<?= $currentStatus === 'rejected' ? 'danger' : 'outline-danger' ?>">
                Rejected
            </a>
        </div>
    </div>

    <div class="card shadow">
        <div class="card-body">
            <?php if (empty($comments)): ?>
                <div class="alert alert-info">No comments found.</div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Post</th>
                                <th>Comment</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($comments as $comment): ?>
                            <tr>
                                <td><?= $comment['id'] ?></td>
                                <td><?= htmlspecialchars($comment['user']['full_name'] ?? 'Unknown User') ?></td>
                                <td>
                                    <a href="/posts/<?= $comment['post']['id'] ?>">
                                        <?= htmlspecialchars(substr($comment['post']['title'] ?? 'Unknown', 0, 30)) ?>...
                                    </a>
                                </td>
                                <td><?= htmlspecialchars(substr($comment['content'], 0, 50)) ?>...</td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $comment['status'] === 'approved' ? 'success' : 
                                        ($comment['status'] === 'rejected' ? 'danger' : 'warning') 
                                    ?>">
                                        <?= $comment['status'] ?>
                                    </span>
                                </td>
                                <td><?= date('M d, Y', strtotime($comment['created_at'])) ?></td>
                                <td>
                                    <?php if ($comment['status'] !== 'approved'): ?>
                                    <form method="POST" action="/admin/comments/<?= $comment['id'] ?>/approve" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-success" title="Approve">
                                            <i class="bi bi-check-circle"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <?php if ($comment['status'] !== 'rejected'): ?>
                                    <form method="POST" action="/admin/comments/<?= $comment['id'] ?>/reject" class="d-inline">
                                        <button type="submit" class="btn btn-sm btn-warning" title="Reject">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                    
                                    <form method="POST" action="/admin/comments/<?= $comment['id'] ?>/delete" 
                                          class="d-inline" onsubmit="return confirm('Delete this comment?')">
                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
