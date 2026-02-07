<div class="container">
    <h1 class="mb-4">
        <i class="bi bi-search"></i> 
        <?php if (!empty($query)): ?>
            Search Results for "<?= htmlspecialchars($query) ?>"
        <?php else: ?>
            Search Posts
        <?php endif; ?>
    </h1>

    <?php if (!empty($query)): ?>
        <p class="text-muted">Found <?= $total ?> result(s)</p>
    <?php endif; ?>

    <div class="row">
        <?php if (empty($query)): ?>
            <div class="col-12">
                <div class="alert alert-info">
                    Enter a search term in the navigation bar to find posts.
                </div>
            </div>
        <?php elseif (empty($results)): ?>
            <div class="col-12">
                <div class="alert alert-warning">
                    No posts found matching your search. Try different keywords.
                </div>
            </div>
        <?php else: ?>
            <div class="col-12">
                <?php foreach ($results as $post): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h4 class="card-title">
                            <a href="/posts/<?= $post['id'] ?>">
                                <?= $post['highlighted_title'] ?>
                            </a>
                        </h4>
                        <p class="card-text"><?= $post['highlighted_excerpt'] ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">
                                    <i class="bi bi-person"></i> 
                                    <a href="/authors/<?= $post['user_id'] ?>">
                                        <?= htmlspecialchars($post['author_name'] ?? 'Unknown Author') ?>
                                    </a>
                                </small>
                                <small class="text-muted ms-2">
                                    <i class="bi bi-calendar"></i> <?= date('M d, Y', strtotime($post['created_at'])) ?>
                                </small>
                                <small class="text-muted ms-2">
                                    <i class="bi bi-chat"></i> <?= $post['comment_count'] ?? 0 ?> comments
                                </small>
                            </div>
                            <div>
                                <?php if (!empty($post['category_name'])): ?>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($post['category_name']) ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

                <!-- Pagination -->
                <?php if ($totalPages > 1): ?>
                <nav>
                    <ul class="pagination justify-content-center">
                        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                <a class="page-link" href="/search?q=<?= urlencode($query) ?>&page=<?= $i ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
