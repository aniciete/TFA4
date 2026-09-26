<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="directory-container">
    <header class="directory-header">
        <div class="header-main">
            <div class="tag-row">
                <span class="market-tag">Retail Directory</span>
                <span class="status-indicator">
                    <span class="status-pip" aria-hidden="true"></span>
                    <span class="status-label">Active Directory</span>
                </span>
            </div>
            <h1 class="page-title">Customer Accounts</h1>
            <p class="section-lead">Registered retail and commercial client profiles.</p>
            <div class="directory-actions-bar">
                <a href="<?= esc(site_url('customers/new'), 'attr') ?>" class="button button-primary">
                    <span>+ New Customer</span>
                </a>
            </div>
        </div>
        <div class="overlapping-stat" aria-label="Customer summary count">
            <span class="stat-giant" aria-hidden="true"><?= sprintf('%02d', count($customers)) ?></span>
            <div class="stat-meta">
                <span class="stat-label">Total Records</span>
                <span class="stat-value"><?= count($customers) ?></span>
            </div>
        </div>
    </header>

    <div class="table-container open-ledger" tabindex="0" role="region" aria-label="Customer accounts directory">
        <table class="data-table">
            <caption class="visually-hidden">Customer accounts directory</caption>
            <thead>
                <tr>
                    <th scope="col" class="th-num">#</th>
                    <th scope="col">Full Name</th>
                    <th scope="col">Email Address</th>
                    <th scope="col">Phone Number</th>
                    <th scope="col" class="th-actions">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($customers as $index => $customer): ?>
                    <tr class="ledger-row-item">
                        <td class="td-num" data-label="#"><?= sprintf('%02d', $index + 1) ?></td>
                        <td class="cell-primary" data-label="Full Name"><?= esc($customer['full_name']) ?></td>
                        <td class="cell-email" data-label="Email Address"><a href="mailto:<?= esc($customer['email'], 'attr') ?>" class="table-link"><?= esc($customer['email']) ?></a></td>
                        <td class="cell-mono tabular-num" data-label="Phone Number"><?= esc($customer['phone'] ?: '—') ?></td>
                        <td class="cell-actions" data-label="Actions">
                            <a href="<?= esc(site_url('customers/edit/' . $customer['id']), 'attr') ?>" class="table-action-link">Edit</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="data-notice">
        <span class="notice-icon" aria-hidden="true">&bull;</span>
        <p>Active client directory verified for store checkout and billing communications.</p>
    </div>
</div>
<?= $this->endSection() ?>
