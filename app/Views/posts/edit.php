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
                            <div id="editor" style="height: 400px; background: white;"></div>
                            <textarea name="content" id="content" style="display:none;" required><?= htmlspecialchars($post['content']) ?></textarea>
                            <small class="form-text text-muted">Use the toolbar to format your content</small>
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

<!-- Quill Editor CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- Quill Editor JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
<script>
// Initialize Quill Editor with existing content
var quill = new Quill('#editor', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            ['blockquote', 'code-block'],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image'],
            ['clean']
        ]
    },
    placeholder: 'Write your post content here...'
});

// Load existing content
var existingContent = document.querySelector('#content').value;
if (existingContent) {
    quill.root.innerHTML = existingContent;
}

// Update hidden textarea when form is submitted
document.querySelector('form').onsubmit = function() {
    var content = document.querySelector('#content');
    content.value = quill.root.innerHTML;
};
</script>
