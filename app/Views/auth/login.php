<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="form-page-container auth-page-container">
    <header class="section-header">
        <div class="tag-row">
            <span class="market-tag">Store Operations &bull; Access Control</span>
            <span class="status-indicator">
                <span class="status-pip" aria-hidden="true"></span>
                <span class="status-label">Staff Portal</span>
            </span>
        </div>
        <h1 class="page-title">Staff Login</h1>
        <p class="section-lead">Verify your staff credentials before opening customer and user account management.</p>
    </header>

    <?php if (! empty($errors)): ?>
        <div class="form-error-summary" role="alert" aria-live="assertive">
            <div class="summary-header">
                <span class="summary-icon" aria-hidden="true">&excl;</span>
                <strong>Please resolve the following <?= count($errors) === 1 ? 'error' : 'errors' ?>:</strong>
            </div>
            <ul class="summary-list">
                <?php foreach ($errors as $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-card-ledger">
        <div class="form-card-header">
            <span class="form-card-title">Credential Verification</span>
            <span class="form-card-docket">SESSION &bull; AUTH</span>
        </div>

        <form action="<?= esc(site_url('login'), 'attr') ?>" method="post" class="ledger-form" novalidate>
            <?= csrf_field() ?>

            <div class="form-group <?= isset($errors['username']) ? 'has-error' : '' ?>">
                <label for="username" class="form-label">
                    <span>Username</span>
                    <span class="req-star" aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    class="form-input"
                    value="<?= esc(old('username')) ?>"
                    autocomplete="username"
                    required
                    autofocus
                >
                <?php if (isset($errors['username'])): ?>
                    <p class="field-error-msg"><?= esc($errors['username']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                <label for="password" class="form-label">
                    <span>Password</span>
                    <span class="req-star" aria-hidden="true">*</span>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    autocomplete="current-password"
                    required
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="field-error-msg"><?= esc($errors['password']) ?></p>
                <?php endif; ?>
            </div>

            <div class="form-actions-row">
                <button type="submit" class="button button-primary">
                    <span>Log In</span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </button>
                <a href="<?= esc(site_url('/'), 'attr') ?>" class="button button-secondary">Return Home</a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
