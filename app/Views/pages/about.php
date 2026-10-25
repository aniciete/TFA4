<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="about-container">
    <header class="about-header">
        <h1 class="page-title">About POS Database</h1>
        <p class="page-lead">
            POS Database delivers front-counter retail management, customer directories, and user account administration backed by relational MySQL storage and CodeIgniter 4 Models.
        </p>
    </header>

    <div class="about-layout">
        <section class="content-section" aria-labelledby="flow-heading">
            <h2 id="flow-heading" class="section-title">Counter Service &amp; Transaction Flow</h2>
            <p class="section-lead">
                Standard counter operating procedure from customer greeting through receipt settlement:
            </p>

            <ol class="flow-list">
                <li class="flow-item">
                    <div class="flow-marker">1</div>
                    <div class="flow-content">
                        <h3 class="flow-step-title">1. Customer Identification</h3>
                        <p class="flow-step-text">Cashiers look up registered customer profiles retrieved from the MySQL database to verify account status, contact preferences, and special pricing.</p>
                    </div>
                </li>

                <li class="flow-item">
                    <div class="flow-marker">2</div>
                    <div class="flow-content">
                        <h3 class="flow-step-title">2. Item &amp; Order Verification</h3>
                        <p class="flow-step-text">The point-of-sale terminal catalogs selected retail items, applies relevant promotions, and calculates subtotal balances.</p>
                    </div>
                </li>

                <li class="flow-item">
                    <div class="flow-marker">3</div>
                    <div class="flow-content">
                        <h3 class="flow-step-title">3. Staff Processing</h3>
                        <p class="flow-step-text">Orders are verified and processed under active staff account records retrieved directly from the database via CodeIgniter Models.</p>
                    </div>
                </li>

                <li class="flow-item">
                    <div class="flow-marker">4</div>
                    <div class="flow-content">
                        <h3 class="flow-step-title">4. Sale Completion &amp; Receipt</h3>
                        <p class="flow-step-text">The terminal finalizes payment processing, updates customer account history, and issues itemized store receipts.</p>
                    </div>
                </li>
            </ol>
        </section>

        <section class="content-section" aria-labelledby="memo-heading">
            <h2 id="memo-heading" class="section-title">Relational Database &amp; Models</h2>
            <div class="details-card">
                <p class="details-text" style="margin-bottom: 1rem;">
                    POS Database replaces temporary static arrays with MySQL persistence. CodeIgniter 4 Models provide active record and Query Builder capabilities to retrieve structured store data cleanly without raw SQL.
                </p>
                <ul class="details-list">
                    <li>
                        <strong>CustomerModel:</strong>
                        <span><code>customers</code> table (<code>id</code>, <code>full_name</code>, <code>email</code>, <code>phone</code>, <code>created_at</code>)</span>
                    </li>
                    <li>
                        <strong>UserModel:</strong>
                        <span><code>users</code> table (<code>id</code>, <code>username</code>, <code>full_name</code>, <code>created_at</code>)</span>
                    </li>
                    <li>
                        <strong>Query Builder:</strong>
                        <span><code>orderBy('id', 'ASC')->findAll()</code></span>
                    </li>
                    <li>
                        <strong>Data Sanitization:</strong>
                        <span>All view variables safely rendered via <code>esc()</code></span>
                    </li>
                </ul>
            </div>
        </section>
    </div>
</div>
<?= $this->endSection() ?>
