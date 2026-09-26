<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="home-grid">
    <!-- Asymmetric Top Section: Oversized Headline + Facts Panel -->
    <section class="home-hero" aria-labelledby="hero-title">
        <div class="hero-mast">
            <div class="tag-row">
                <span class="market-tag">Store Operations</span>
                <span class="status-indicator" aria-label="System status active">
                    <span class="status-pip" aria-hidden="true"></span>
                    <span class="status-label">Counter System Active</span>
                </span>
            </div>
            <h1 id="hero-title" class="page-title hero-title">Point-of-Sale Database</h1>
            <p class="hero-lead">
                A streamlined retail operations portal for managing customer relationships, store staff accounts, and daily checkout counter workflows backed by MySQL database models.
            </p>
        </div>
    </section>

    <aside class="facts-panel" aria-label="Store overview and statistics">
        <div class="panel-header">
            <span class="panel-kicker">STORE OVERVIEW</span>
            <span class="panel-badge">ACTIVE</span>
        </div>
        <ul class="facts-list" role="list">
            <li class="fact-item">
                <span class="fact-num">05</span>
                <div class="fact-details">
                    <strong class="fact-label">Registered Customers</strong>
                    <span class="fact-desc">Active retail and commercial accounts</span>
                </div>
            </li>
            <li class="fact-item">
                <span class="fact-num">05</span>
                <div class="fact-details">
                    <strong class="fact-label">Authorized Staff</strong>
                    <span class="fact-desc">Active store operator and staff accounts</span>
                </div>
            </li>
            <li class="fact-item">
                <span class="fact-num">04</span>
                <div class="fact-details">
                    <strong class="fact-label">Store Modules</strong>
                    <span class="fact-desc">Core portals for accounts and operations</span>
                </div>
            </li>
        </ul>
    </aside>

    <!-- Two Prominent Directory Modules -->
    <section class="directory-modules" aria-label="Application Directories">
        <article class="directory-card card-customers">
            <div class="card-meta">
                <span class="module-number">Retail Directory</span>
                <span class="record-pill">5 Records</span>
            </div>
            <div class="card-header">
                <div class="card-symbol" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/>
                        <circle cx="9" cy="7" r="4"/>
                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"/>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
                    </svg>
                </div>
                <h2 class="card-title">Customer Accounts</h2>
            </div>
            <p class="card-description">
                Access verified customer profiles, contact information, and commercial billing details retrieved from MySQL storage.
            </p>
            <div class="card-footer">
                <a href="<?= site_url('customers') ?>" class="button button-primary">
                    <span>Open Customers</span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>

        <article class="directory-card card-users">
            <div class="card-meta">
                <span class="module-number">Staff Directory</span>
                <span class="record-pill">5 Users</span>
            </div>
            <div class="card-header">
                <div class="card-symbol" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="4" width="18" height="16" rx="2"/>
                        <circle cx="9" cy="10" r="2"/>
                        <path d="M15 8h2"/>
                        <path d="M15 12h2"/>
                        <path d="M7 16h10"/>
                    </svg>
                </div>
                <h2 class="card-title">User Accounts</h2>
            </div>
            <p class="card-description">
                Manage authorized store personnel, terminal credentials, and user account records retrieved via CodeIgniter Models.
            </p>
            <div class="card-footer">
                <a href="<?= site_url('users') ?>" class="button button-primary">
                    <span>Open Users</span>
                    <span class="btn-arrow" aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </article>
    </section>

    <!-- Horizontal Architecture Feature -->
    <section class="architecture-feature" aria-labelledby="arch-title">
        <div class="feature-left">
            <span class="market-tag tag-sage">OPERATIONAL BLUEPRINT</span>
            <h2 id="arch-title" class="card-title feature-heading">Store Service &amp; Counter Flow</h2>
            <p class="card-description">
                An integrated checkout workflow connecting counter customer service, database query models, and store administration.
            </p>
        </div>
        <div class="feature-pipeline" aria-label="Service lifecycle pipeline">
            <div class="pipe-node">
                <span class="pipe-index">01</span>
                <span class="pipe-name">Customer Check-In</span>
            </div>
            <span class="pipe-connector" aria-hidden="true">&rarr;</span>
            <div class="pipe-node">
                <span class="pipe-index">02</span>
                <span class="pipe-name">Order Entry</span>
            </div>
            <span class="pipe-connector" aria-hidden="true">&rarr;</span>
            <div class="pipe-node">
                <span class="pipe-index">03</span>
                <span class="pipe-name">Payment Processing</span>
            </div>
            <span class="pipe-connector" aria-hidden="true">&rarr;</span>
            <div class="pipe-node">
                <span class="pipe-index">04</span>
                <span class="pipe-name">Account Receipt</span>
            </div>
        </div>
        <div class="feature-actions">
            <a href="<?= site_url('about') ?>" class="button button-secondary">About the Platform</a>
        </div>
    </section>
</div>
<?= $this->endSection() ?>
