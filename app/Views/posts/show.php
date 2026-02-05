<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Post Header -->
            <article class="mb-4">
                <?php if ($post['status'] === 'draft'): ?>
                    <div class="alert alert-warning">
                        <i class="bi bi-exclamation-triangle"></i> This is a draft post
                    </div>
                <?php endif; ?>

                <?php if (!empty($post['featured_image'])): ?>
                <img src="<?= htmlspecialchars($post['featured_image']) ?>" 
                     class="img-fluid rounded mb-4 w-100" 
                     style="max-height: 400px; object-fit: cover;"
                     alt="<?= htmlspecialchars($post['title']) ?>">
                <?php endif; ?>

                <h1 class="mb-3"><?= htmlspecialchars($post['title']) ?></h1>

                <div class="d-flex align-items-center mb-3">
                    <img src="<?= htmlspecialchars($post['author']['avatar'] ?? '') ?>" 
                         class="rounded-circle me-2" width="40" height="40"
                         alt="<?= htmlspecialchars($post['author']['name']) ?>">
                    <div>
                        <div>
                            <a href="/authors/<?= $post['author']['id'] ?>" class="text-decoration-none fw-bold">
                                <?= htmlspecialchars($post['author']['name']) ?>
                            </a>
                        </div>
                        <small class="text-muted">
                            <?= date('M d, Y', strtotime($post['created_at'])) ?> · 
                            <i class="bi bi-eye"></i> <?= $post['views'] ?? 0 ?> views
                        </small>
                    </div>
                </div>

                <?php if (!empty($post['category_names'])): ?>
                <div class="mb-3">
                    <?php foreach ($post['category_names'] as $catName): ?>
                        <span class="badge bg-primary"><?= htmlspecialchars($catName) ?></span>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if ($isAuthenticated && ($getCurrentUserId() == $post['author_id'] || $isAdmin)): ?>
                <div class="mb-3">
                    <a href="/posts/<?= $post['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                    <form method="POST" action="/posts/<?= $post['id'] ?>/delete" class="d-inline" 
                          onsubmit="return confirm('Are you sure you want to delete this post?')">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash"></i> Delete
                        </button>
                    </form>
                </div>
                <?php endif; ?>

                <hr>

                <!-- Post Content -->
                <div class="post-content mb-5">
                    <?= $post['content'] ?>
                </div>
            </article>

            <!-- Comments Section -->
            <div class="comments-section">
                <h3 class="mb-4"><i class="bi bi-chat-left-text"></i> Comments (<?= count($comments) ?>)</h3>

                <?php if ($isAuthenticated): ?>
                <!-- Comment Form -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Leave a Comment</h5>
                        <form method="POST" action="/comments/store">
                            <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                            <div class="mb-3">
                                <textarea class="form-control" name="content" rows="3" 
                                          placeholder="Write your comment..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-send"></i> Post Comment
                            </button>
                        </form>
                    </div>
                </div>
                <?php else: ?>
                <div class="alert alert-info">
                    Please <a href="/login">login</a> to leave a comment.
                </div>
                <?php endif; ?>

                <!-- Comments List -->
                <?php if (empty($comments)): ?>
                    <p class="text-muted">No comments yet. Be the first to comment!</p>
                <?php else: ?>
                    <?php foreach ($comments as $comment): ?>
                    <div class="comment-box">
                        <div class="d-flex align-items-start mb-2">
                            <img src="<?= htmlspecialchars($comment['user']['avatar'] ?? '') ?>" 
                                 class="rounded-circle me-2" width="40" height="40"
                                 alt="<?= htmlspecialchars($comment['user']['name']) ?>">
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between">
                                    <strong><?= htmlspecialchars($comment['user']['name']) ?></strong>
                                    <small class="text-muted">
                                        <?= date('M d, Y H:i', strtotime($comment['created_at'])) ?>
                                    </small>
                                </div>
                                <p class="mb-2"><?= nl2br($comment['content']) ?></p>
                                
                                <?php if ($isAuthenticated): ?>
                                <div class="btn-group btn-group-sm">
                                    <?php if ($getCurrentUserId() == $comment['user_id'] || $isAdmin): ?>
                                    <form method="POST" action="/comments/<?= $comment['id'] ?>/delete" class="d-inline"
                                          onsubmit="return confirm('Delete this comment?')">
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>

                                <!-- Replies -->
                                <?php if (!empty($comment['replies'])): ?>
                                    <?php foreach ($comment['replies'] as $reply): ?>
                                    <div class="reply-box comment-box mt-2">
                                        <div class="d-flex align-items-start">
                                            <img src="<?= htmlspecialchars($reply['user']['avatar'] ?? '') ?>" 
                                                 class="rounded-circle me-2" width="30" height="30"
                                                 alt="<?= htmlspecialchars($reply['user']['name']) ?>">
                                            <div class="flex-grow-1">
                                                <div class="d-flex justify-content-between">
                                                    <strong><?= htmlspecialchars($reply['user']['name']) ?></strong>
                                                    <small class="text-muted">
                                                        <?= date('M d, Y H:i', strtotime($reply['created_at'])) ?>
                                                    </small>
                                                </div>
                                                <p class="mb-0"><?= nl2br($reply['content']) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>

                                <!-- Reply Form -->
                                <?php if ($isAuthenticated): ?>
                                <button class="btn btn-sm btn-link" onclick="toggleReplyForm(<?= $comment['id'] ?>)">
                                    <i class="bi bi-reply"></i> Reply
                                </button>
                                <div id="reply-form-<?= $comment['id'] ?>" style="display: none;" class="mt-2">
                                    <form method="POST" action="/comments/store">
                                        <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                        <input type="hidden" name="parent_id" value="<?= $comment['id'] ?>">
                                        <div class="input-group">
                                            <textarea class="form-control" name="content" rows="2" 
                                                      placeholder="Write a reply..." required></textarea>
                                            <button type="submit" class="btn btn-primary">Reply</button>
                                        </div>
                                    </form>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
function toggleReplyForm(commentId) {
    const form = document.getElementById('reply-form-' + commentId);
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}
</script>
