<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="home-layout">
    <section class="overview-section">
        <h1 class="page-title">Point-of-Sale Database</h1>
        <p class="page-lead">
            A streamlined retail operations portal for managing customer relationships, store staff accounts, and daily checkout counter workflows backed by MySQL database models.
        </p>

        <div class="actions-grid">
            <article class="action-card">
                <h2 class="card-heading">Customer Accounts</h2>
                <p class="card-text">
                    Access verified customer profiles, contact information, and commercial billing details retrieved from MySQL storage.
                </p>
                <div class="card-action">
                    <a href="<?= site_url('customers') ?>" class="button button-primary">Open Customers</a>
                </div>
            </article>

            <article class="action-card">
                <h2 class="card-heading">User Accounts</h2>
                <p class="card-text">
                    Manage authorized store personnel, terminal credentials, and user account records retrieved via CodeIgniter Models.
                </p>
                <div class="card-action">
                    <a href="<?= site_url('users') ?>" class="button button-primary">Open Users</a>
                </div>
            </article>
        </div>
    </section>

    <section class="overview-section" aria-labelledby="details-heading">
        <h2 id="details-heading" class="section-title">Store Overview</h2>
        <div class="details-card">
            <ul class="details-list">
                <li>
                    <strong>Registered Customers:</strong>
                    <span><?= (int) ($customerCount ?? 0) ?> active retail and commercial accounts</span>
                </li>
                <li>
                    <strong>Authorized Staff:</strong>
                    <span><?= (int) ($userCount ?? 0) ?> active store operator and staff accounts</span>
                </li>
                <li>
                    <strong>System Framework:</strong>
                    <span>CodeIgniter 4 with active Model Query Builder</span>
                </li>
                <li>
                    <strong>Database:</strong>
                    <span>MySQL relational storage</span>
                </li>
            </ul>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
