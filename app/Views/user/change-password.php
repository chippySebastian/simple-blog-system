<div class="container">
    <div class="row">
        <div class="col-lg-6 mx-auto">
            <h1 class="mb-4"><i class="bi bi-key"></i> Change Password</h1>
            
            <div class="card shadow">
                <div class="card-body">
                    <form method="POST" action="/profile/change-password">
                        <?= \App\Helpers\CsrfHelper::field() ?>
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password *</label>
                            <input type="password" class="form-control" id="current_password" 
                                   name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password *</label>
                            <input type="password" class="form-control" id="new_password" 
                                   name="new_password" minlength="6" required>
                            <div class="form-text">Password must be at least 6 characters</div>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password *</label>
                            <input type="password" class="form-control" id="confirm_password" 
                                   name="confirm_password" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-save"></i> Change Password
                            </button>
                            <a href="/profile" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
