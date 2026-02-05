<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Post</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="/posts/<?= $post['id'] ?>/update">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" 
                                   value="<?= htmlspecialchars($post['title']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea class="form-control" id="content" name="content" rows="15" required><?= htmlspecialchars($post['content']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3"><?= htmlspecialchars($post['excerpt']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="featured_image" class="form-label">Featured Image URL</label>
                            <input type="url" class="form-control" id="featured_image" name="featured_image"
                                   value="<?= htmlspecialchars($post['featured_image']) ?>">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Categories</label>
                            <div class="row">
                                <?php foreach ($categories as $category): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="categories[]" value="<?= $category['id'] ?>"
                                               id="cat-<?= $category['id'] ?>"
                                               <?= in_array($category['id'], $post['categories'] ?? []) ? 'checked' : '' ?>>
                                        <label class="form-check-label" for="cat-<?= $category['id'] ?>">
                                            <?= htmlspecialchars($category['name']) ?>
                                        </label>
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Status</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" 
                                       value="draft" id="status-draft" 
                                       <?= $post['status'] === 'draft' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status-draft">
                                    Draft
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" 
                                       value="published" id="status-published"
                                       <?= $post['status'] === 'published' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status-published">
                                    Published
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Post
                            </button>
                            <a href="/posts/<?= $post['id'] ?>" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
<script>
tinymce.init({
    selector: '#content',
    height: 400,
    menubar: false,
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic forecolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }'
});
</script>
