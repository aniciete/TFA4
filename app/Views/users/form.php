<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="form-page-container">
    <header class="section-header">
        <div class="tag-row">
            <span class="market-tag">Staff Directory &bull; <?= $mode === 'create' ? 'Personnel Setup' : 'Account Edit' ?></span>
            <span class="status-indicator">
                <span class="status-pip" aria-hidden="true"></span>
                <span class="status-label"><?= $mode === 'create' ? 'New Operator' : 'User #' . esc($user['id']) ?></span>
            </span>
        </div>
        <h1 class="page-title"><?= $mode === 'create' ? 'New User Account' : 'Edit User Account' ?></h1>
        <p class="section-lead">
            <?= $mode === 'create'
                ? 'Create authorized store personnel and operator credentials backed by relational storage.'
                : 'Modify operator account profile, username, and profile picture avatar.' ?>
        </p>
    </header>

    <?php if (! empty($errors)): ?>
        <div class="form-error-summary" role="alert" aria-live="assertive">
            <div class="summary-header">
                <span class="summary-icon" aria-hidden="true">&excl;</span>
                <strong>Please resolve the following <?= count($errors) === 1 ? 'error' : 'errors' ?>:</strong>
            </div>
            <ul class="summary-list">
                <?php foreach ($errors as $field => $error): ?>
                    <li><?= esc($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-card-ledger">
        <div class="form-card-header">
            <span class="form-card-title"><?= $mode === 'create' ? 'User Account Setup' : 'Edit Account Details' ?></span>
            <span class="form-card-docket">AUTH &bull; CREDENTIALS</span>
        </div>

        <form action="<?= esc($action, 'attr') ?>" method="post" enctype="multipart/form-data" class="ledger-form" novalidate>
            <?= csrf_field() ?>

            <div class="form-group <?= isset($errors['full_name']) ? 'has-error' : '' ?>">
                <label for="full_name" class="form-label">
                    <span>Full Name</span>
                    <span class="req-star" aria-hidden="true">*</span>
                </label>
                <input
                    type="text"
                    id="full_name"
                    name="full_name"
                    class="form-input"
                    value="<?= esc(old('full_name', $user['full_name'] ?? '')) ?>"
                    placeholder="e.g. Carlos Reyes"
                    required
                    aria-describedby="<?= isset($errors['full_name']) ? 'full_name_error' : 'full_name_help' ?>"
                >
                <?php if (isset($errors['full_name'])): ?>
                    <p id="full_name_error" class="field-error-msg"><?= esc($errors['full_name']) ?></p>
                <?php else: ?>
                    <p id="full_name_help" class="field-help-text">Staff member's legal or recognized name.</p>
                <?php endif; ?>
            </div>

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
                    value="<?= esc(old('username', $user['username'] ?? '')) ?>"
                    placeholder="e.g. admin.reyes"
                    required
                    aria-describedby="<?= isset($errors['username']) ? 'username_error' : 'username_help' ?>"
                >
                <?php if (isset($errors['username'])): ?>
                    <p id="username_error" class="field-error-msg"><?= esc($errors['username']) ?></p>
                <?php else: ?>
                    <p id="username_help" class="field-help-text">Unique alphanumeric identifier for terminal login.</p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['password']) ? 'has-error' : '' ?>">
                <label for="password" class="form-label">
                    <span><?= $mode === 'create' ? 'Password' : 'New Password' ?></span>
                    <?php if ($mode === 'create'): ?><span class="req-star" aria-hidden="true">*</span><?php else: ?><span class="optional-tag">(Optional)</span><?php endif; ?>
                </label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    class="form-input"
                    autocomplete="new-password"
                    <?= $mode === 'create' ? 'required' : '' ?>
                    aria-describedby="<?= isset($errors['password']) ? 'password_error' : 'password_help' ?>"
                >
                <?php if (isset($errors['password'])): ?>
                    <p id="password_error" class="field-error-msg"><?= esc($errors['password']) ?></p>
                <?php else: ?>
                    <p id="password_help" class="field-help-text">
                        <?= $mode === 'create' ? 'Use at least 8 characters for the staff login credential.' : 'Leave empty to keep the current password.' ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['password_confirm']) ? 'has-error' : '' ?>">
                <label for="password_confirm" class="form-label">
                    <span>Confirm Password</span>
                    <?php if ($mode === 'create'): ?><span class="req-star" aria-hidden="true">*</span><?php else: ?><span class="optional-tag">(Optional)</span><?php endif; ?>
                </label>
                <input
                    type="password"
                    id="password_confirm"
                    name="password_confirm"
                    class="form-input"
                    autocomplete="new-password"
                    <?= $mode === 'create' ? 'required' : '' ?>
                    aria-describedby="<?= isset($errors['password_confirm']) ? 'password_confirm_error' : 'password_confirm_help' ?>"
                >
                <?php if (isset($errors['password_confirm'])): ?>
                    <p id="password_confirm_error" class="field-error-msg"><?= esc($errors['password_confirm']) ?></p>
                <?php else: ?>
                    <p id="password_confirm_help" class="field-help-text">Repeat the password exactly.</p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['avatar']) ? 'has-error' : '' ?>">
                <label for="avatar" class="form-label">
                    <span>Profile Avatar</span>
                    <span class="optional-tag">(Optional &bull; JPG, PNG &bull; Max 2 MB)</span>
                </label>

                <?php if ($mode === 'edit'): ?>
                    <div class="avatar-preview-box">
                        <img src="<?= esc($user['avatar_url'], 'attr') ?>"
                             alt="<?= $user['has_avatar'] ? 'Current avatar' : 'Default avatar placeholder' ?>"
                             class="avatar-preview-img <?= $user['has_avatar'] ? '' : 'avatar-placeholder' ?>">
                        <div class="avatar-preview-details">
                            <?php if ($user['has_avatar']): ?>
                                <span class="avatar-preview-title">Current Avatar Assigned</span>
                                <span class="avatar-preview-subtext"><?= esc($user['avatar']) ?></span>
                            <?php else: ?>
                                <span class="avatar-preview-title">No Avatar Assigned</span>
                                <span class="avatar-preview-subtext">Using default market placeholder</span>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <input
                    type="file"
                    id="avatar"
                    name="avatar"
                    class="form-file-input"
                    accept="image/jpeg,image/png,image/jpg"
                    aria-describedby="<?= isset($errors['avatar']) ? 'avatar_error' : 'avatar_help' ?>"
                >
                <?php if (isset($errors['avatar'])): ?>
                    <p id="avatar_error" class="field-error-msg"><?= esc($errors['avatar']) ?></p>
                <?php else: ?>
                    <p id="avatar_help" class="field-help-text">
                        <?= $mode === 'edit' ? 'Select a new image to replace current avatar, or leave empty to keep existing.' : 'Upload an operator profile picture (automatically resized to 256&times;256).' ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($mode === 'edit' && ! empty($user['created_at'])): ?>
                <div class="form-meta-note">
                    <span class="meta-label">Account Created:</span>
                    <span class="meta-value"><?= esc($user['created_at']) ?> (Preserved)</span>
                </div>
            <?php endif; ?>

            <div class="form-actions-row">
                <button type="submit" class="button button-primary">
                    <span><?= $mode === 'create' ? 'Save User' : 'Update User' ?></span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </button>
                <a href="<?= esc(site_url('users'), 'attr') ?>" class="button button-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
