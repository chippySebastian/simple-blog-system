<div class="container">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <h1 class="mb-4"><i class="bi bi-pencil-square"></i> Create New Post</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="/posts/store" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Content *</label>
                            <textarea name="content" id="content" class="form-control" rows="15" required></textarea>
                            <small class="form-text text-muted">Use the toolbar to format your content</small>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt</label>
                            <textarea class="form-control" id="excerpt" name="excerpt" rows="3" 
                                      placeholder="Brief summary (optional, auto-generated if empty)"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="featured_image_file" class="form-label">Featured Image</label>
                            <input type="file" class="form-control" id="featured_image_file" name="featured_image_file"
                                   accept="image/jpeg,image/jpg,image/png,image/gif,image/webp">
                            <small class="form-text text-muted">Upload an image (max 5MB, formats: JPG, PNG, GIF, WebP)</small>
                            <div id="image-preview" class="mt-2" style="display: none;">
                                <img id="preview-img" src="" alt="Image preview" class="img-thumbnail" style="max-width: 300px;">
                                <button type="button" class="btn btn-sm btn-danger mt-2" id="remove-image">
                                    <i class="bi bi-trash"></i> Remove
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Category</label>
                            <div class="row">
                                <?php foreach ($categories as $category): ?>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" 
                                               name="categories[]" value="<?= $category['id'] ?>"
                                               id="cat-<?= $category['id'] ?>">
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
                                       value="draft" id="status-draft" checked>
                                <label class="form-check-label" for="status-draft">
                                    Save as Draft
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="status" 
                                       value="published" id="status-published">
                                <label class="form-check-label" for="status-published">
                                    Publish Now
                                </label>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Save Post
                            </button>
                            <a href="/posts" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Jodit Editor CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.css"/>

<!-- Jodit Editor JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jodit/3.24.5/jodit.min.js"></script>
<script>
    const editor = Jodit.make('#content', {
        height: 400,
        buttons: 'bold,italic,underline,strikethrough,|,ul,ol,|,align,|,link,image,|,undo,redo,|,source'
    });
    console.log('Jodit editor initialized');
    
    // Image upload preview
    const fileInput = document.getElementById('featured_image_file');
    const imagePreview = document.getElementById('image-preview');
    const previewImg = document.getElementById('preview-img');
    const removeBtn = document.getElementById('remove-image');
    
    // Preview uploaded file
    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (5MB)
            if (file.size > 5 * 1024 * 1024) {
                alert('File size exceeds 5MB limit');
                fileInput.value = '';
                return;
            }
            
            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
            if (!validTypes.includes(file.type)) {
                alert('Invalid file type. Please upload JPG, PNG, GIF, or WebP');
                fileInput.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        }
    });
    
    // Remove image preview
    removeBtn.addEventListener('click', function() {
        fileInput.value = '';
        previewImg.src = '';
        imagePreview.style.display = 'none';
    });
</script>


