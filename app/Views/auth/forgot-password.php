<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning">
                    <h4 class="mb-0">Forgot Password</h4>
                </div>
                <div class="card-body">
                    <p>Enter your email address and we'll send you instructions to reset your password.</p>
                    <form method="POST" action="/forgot-password">
                        <?= \App\Helpers\CsrfHelper::field() ?>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" class="btn btn-warning w-100">Send Reset Link</button>
                    </form>
                    <div class="mt-3 text-center">
                        <p><a href="/login">Back to Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
