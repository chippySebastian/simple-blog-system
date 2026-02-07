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
                            <textarea name="content" id="content" class="form-control" rows="15" required><?= htmlspecialchars($post['content'] ?? '') ?></textarea>
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
                            <label class="form-label">Category</label>
                            <div class="row">
                                <?php foreach ($categories as $category): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="categories[]" value="<?= $category['id'] ?>"
                                               id="cat-<?= $category['id'] ?>"
                                               <?= ($post['category_id'] == $category['id']) ? 'checked' : '' ?>>
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

<!-- Trumbowyg CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/ui/trumbowyg.min.css">

<!-- Trumbowyg JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.27.3/trumbowyg.min.js"></script>
<script>
$(document).ready(function() {
    console.log('Initializing Trumbowyg...');
    console.log('jQuery version:', $.fn.jquery);
    console.log('Content element:', $('#content').length);
    
    if (typeof $.fn.trumbowyg === 'undefined') {
        console.error('Trumbowyg plugin not loaded!');
        return;
    }
    
    $('#content').trumbowyg({
        btns: [
            ['viewHTML'],
            ['formatting'],
            ['strong', 'em', 'del'],
            ['superscript', 'subscript'],
            ['link'],
            ['insertImage'],
            ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
            ['unorderedList', 'orderedList'],
            ['horizontalRule'],
            ['removeformat'],
            ['fullscreen']
        ]
    });
    
    console.log('Trumbowyg initialized successfully');
});
</script>


