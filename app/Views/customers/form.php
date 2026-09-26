<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="form-page-container">
    <header class="section-header">
        <div class="tag-row">
            <span class="market-tag">Retail Directory &bull; <?= $mode === 'create' ? 'Registration' : 'Profile Edit' ?></span>
            <span class="status-indicator">
                <span class="status-pip" aria-hidden="true"></span>
                <span class="status-label"><?= $mode === 'create' ? 'New Record' : 'Record #' . esc($customer['id']) ?></span>
            </span>
        </div>
        <h1 class="page-title"><?= $mode === 'create' ? 'New Customer Account' : 'Edit Customer Account' ?></h1>
        <p class="section-lead">
            <?= $mode === 'create'
                ? 'Register a verified customer profile for checkout, receipts, and account communications.'
                : 'Modify customer contact details and account parameters.' ?>
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
            <span class="form-card-title"><?= $mode === 'create' ? 'Customer Profile Form' : 'Update Customer Profile' ?></span>
            <span class="form-card-docket">DOCKET &bull; PERSISTENCE</span>
        </div>

        <form action="<?= esc($action, 'attr') ?>" method="post" class="ledger-form" novalidate>
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
                    value="<?= esc(old('full_name', $customer['full_name'] ?? '')) ?>"
                    placeholder="e.g. Elena Rostova"
                    required
                    aria-describedby="<?= isset($errors['full_name']) ? 'full_name_error' : 'full_name_help' ?>"
                >
                <?php if (isset($errors['full_name'])): ?>
                    <p id="full_name_error" class="field-error-msg"><?= esc($errors['full_name']) ?></p>
                <?php else: ?>
                    <p id="full_name_help" class="field-help-text">Enter client full legal or trade name.</p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['email']) ? 'has-error' : '' ?>">
                <label for="email" class="form-label">
                    <span>Email Address</span>
                    <span class="req-star" aria-hidden="true">*</span>
                </label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    class="form-input"
                    value="<?= esc(old('email', $customer['email'] ?? '')) ?>"
                    placeholder="e.g. elena.rostova@example.com"
                    required
                    aria-describedby="<?= isset($errors['email']) ? 'email_error' : 'email_help' ?>"
                >
                <?php if (isset($errors['email'])): ?>
                    <p id="email_error" class="field-error-msg"><?= esc($errors['email']) ?></p>
                <?php else: ?>
                    <p id="email_help" class="field-help-text">Primary billing and electronic receipt delivery address.</p>
                <?php endif; ?>
            </div>

            <div class="form-group <?= isset($errors['phone']) ? 'has-error' : '' ?>">
                <label for="phone" class="form-label">
                    <span>Phone Number</span>
                    <span class="optional-tag">(Optional)</span>
                </label>
                <input
                    type="text"
                    id="phone"
                    name="phone"
                    class="form-input"
                    value="<?= esc(old('phone', $customer['phone'] ?? '')) ?>"
                    placeholder="e.g. +1 (555) 234-5678"
                    aria-describedby="<?= isset($errors['phone']) ? 'phone_error' : 'phone_help' ?>"
                >
                <?php if (isset($errors['phone'])): ?>
                    <p id="phone_error" class="field-error-msg"><?= esc($errors['phone']) ?></p>
                <?php else: ?>
                    <p id="phone_help" class="field-help-text">Contact telephone number for SMS notifications.</p>
                <?php endif; ?>
            </div>

            <?php if ($mode === 'edit' && ! empty($customer['created_at'])): ?>
                <div class="form-meta-note">
                    <span class="meta-label">Original Registration:</span>
                    <span class="meta-value"><?= esc($customer['created_at']) ?> (Preserved)</span>
                </div>
            <?php endif; ?>

            <div class="form-actions-row">
                <button type="submit" class="button button-primary">
                    <span><?= $mode === 'create' ? 'Save Customer' : 'Update Customer' ?></span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </button>
                <a href="<?= esc(site_url('customers'), 'attr') ?>" class="button button-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>
