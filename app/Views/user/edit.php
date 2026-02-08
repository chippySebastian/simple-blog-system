<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h1 class="mb-4"><i class="bi bi-pencil-square"></i> Edit Profile</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="/profile/update">
                        <?= \App\Helpers\CsrfHelper::field() ?>
                        <div class="mb-3 text-center">
                            <img src="<?= htmlspecialchars($user['avatar']) ?>" 
                                 class="rounded-circle mb-2" width="100" height="100"
                                 alt="<?= htmlspecialchars($user['full_name']) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Name *</label>
                            <input type="text" class="form-control" id="name" name="name" 
                                   value="<?= htmlspecialchars($user['full_name']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="bio" class="form-label">Bio</label>
                            <textarea class="form-control" id="bio" name="bio" rows="4"><?= htmlspecialchars($user['bio'] ?? '') ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="avatar" class="form-label">Avatar URL</label>
                            <input type="url" class="form-control" id="avatar" name="avatar" 
                                   value="<?= htmlspecialchars($user['avatar']) ?>">
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Update Profile
                            </button>
                            <a href="/profile" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
