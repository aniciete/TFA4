<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="about-container">
    <header class="section-header">
        <div class="tag-row">
            <span class="market-tag">Store Operations &bull; Overview</span>
            <span class="status-indicator">
                <span class="status-pip" aria-hidden="true"></span>
                <span class="status-label">Operational Guide</span>
            </span>
        </div>
        <h1 class="page-title">About POS Database</h1>
        <p class="section-lead">
            POS Database delivers front-counter retail management, customer directories, and user account administration backed by relational MySQL storage and CodeIgniter 4 Models.
        </p>
    </header>

    <div class="about-layout">
        <!-- Connected 4-Step Flow -->
        <section class="flow-section" aria-labelledby="flow-heading">
            <div class="flow-mast">
                <h2 id="flow-heading" class="section-heading">Counter Service &amp; Transaction Flow</h2>
                <p class="section-sublead">
                    Standard counter operating procedure from customer greeting through receipt settlement:
                </p>
            </div>

            <ol class="staggered-flow-rail" role="list">
                <li class="flow-node step-odd">
                    <div class="node-track" aria-hidden="true">
                        <span class="oversized-num">01</span>
                        <div class="track-line"></div>
                    </div>
                    <div class="node-card">
                        <div class="node-kicker">STEP 01 &bull; CHECK-IN</div>
                        <strong class="flow-step-title">1. Customer Identification</strong>
                        <p class="flow-step-text">Cashiers look up registered customer profiles retrieved from the MySQL database to verify account status, contact preferences, and special pricing.</p>
                    </div>
                </li>

                <li class="flow-node step-even">
                    <div class="node-track" aria-hidden="true">
                        <span class="oversized-num">02</span>
                        <div class="track-line"></div>
                    </div>
                    <div class="node-card">
                        <div class="node-kicker">STEP 02 &bull; ORDER ENTRY</div>
                        <strong class="flow-step-title">2. Item &amp; Order Verification</strong>
                        <p class="flow-step-text">The point-of-sale terminal catalogs selected retail items, applies relevant promotions, and calculates subtotal balances.</p>
                    </div>
                </li>

                <li class="flow-node step-odd">
                    <div class="node-track" aria-hidden="true">
                        <span class="oversized-num">03</span>
                        <div class="track-line"></div>
                    </div>
                    <div class="node-card">
                        <div class="node-kicker">STEP 03 &bull; STAFF PROCESSING</div>
                        <strong class="flow-step-title">3. Staff Processing</strong>
                        <p class="flow-step-text">Orders are verified and processed under active staff account records retrieved directly from the database via CodeIgniter Models.</p>
                    </div>
                </li>

                <li class="flow-node step-even">
                    <div class="node-track" aria-hidden="true">
                        <span class="oversized-num">04</span>
                        <div class="track-terminal"></div>
                    </div>
                    <div class="node-card">
                        <div class="node-kicker">STEP 04 &bull; RECEIPT ISSUANCE</div>
                        <strong class="flow-step-title">4. Sale Completion &amp; Receipt</strong>
                        <p class="flow-step-text">The terminal finalizes payment processing, updates customer account history, and issues itemized store receipts.</p>
                    </div>
                </li>
            </ol>
        </section>

        <!-- Database Architecture Memo -->
        <aside class="storage-dispatch-memo" aria-labelledby="memo-heading">
            <div class="memo-mast">
                <div class="memo-top-row">
                    <span class="memo-stamp">DATABASE ARCHITECTURE</span>
                    <span class="memo-ref">MODEL PERSISTENCE PROTOCOL</span>
                </div>
                <h2 id="memo-heading" class="memo-title">Relational Database &amp; Models</h2>
            </div>
            <div class="memo-content">
                <p class="memo-lead">
                    POS Database replaces temporary static arrays with MySQL persistence. CodeIgniter 4 Models provide active record and Query Builder capabilities to retrieve structured store data cleanly without raw SQL.
                </p>
                <div class="memo-meta-ledger">
                    <div class="ledger-row">
                        <div class="ledger-row-header">
                            <span class="ledger-term">CustomerModel:</span>
                            <span class="ledger-tag">customers table</span>
                        </div>
                        <span class="ledger-value"><code>id</code>, <code>full_name</code>, <code>email</code>, <code>phone</code>, <code>created_at</code></span>
                    </div>
                    <div class="ledger-row">
                        <div class="ledger-row-header">
                            <span class="ledger-term">UserModel:</span>
                            <span class="ledger-tag">users table</span>
                        </div>
                        <span class="ledger-value"><code>id</code>, <code>username</code>, <code>full_name</code>, <code>created_at</code></span>
                    </div>
                    <div class="ledger-row">
                        <div class="ledger-row-header">
                            <span class="ledger-term">Query Builder:</span>
                            <span class="ledger-tag">Active Record</span>
                        </div>
                        <span class="ledger-value"><code>orderBy('id', 'ASC')-&gt;findAll()</code></span>
                    </div>
                    <div class="ledger-row">
                        <div class="ledger-row-header">
                            <span class="ledger-term">Data Sanitization:</span>
                            <span class="ledger-tag">Security</span>
                        </div>
                        <span class="ledger-value">All view variables safely rendered via <code>esc()</code></span>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</div>
<?= $this->endSection() ?>
