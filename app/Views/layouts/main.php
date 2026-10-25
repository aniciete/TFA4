<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'POS Database') ?></title>
    <link rel="icon" type="image/svg+xml" href="<?= base_url('favicon.svg') ?>">
    <link rel="alternate icon" href="<?= base_url('favicon.ico') ?>">
    <?php
        $cssUrl = base_url('assets/css/style.css');
        if (ENVIRONMENT === 'development' && ! empty($_SERVER['HTTP_HOST'])) {
            $scheme = (! empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https' : 'http';
            $cssUrl = $scheme . '://' . $_SERVER['HTTP_HOST'] . '/assets/css/style.css';
        }
    ?>
    <link rel="stylesheet" href="<?= esc($cssUrl) ?>">
</head>
<body class="market-body page-<?= esc($activePage ?? 'home') ?>">
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="site-header" role="banner">
        <div class="container header-container">
            <div class="brand-group">
                <a href="<?= site_url('/') ?>" class="brand-link" aria-label="POS Database Home">
                    <span class="brand-mark" aria-hidden="true">
                        <svg width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <rect x="2" y="2" width="24" height="24" rx="3" fill="#173c2a"/>
                            <rect x="4" y="4" width="20" height="20" rx="2" stroke="#fff8e8" stroke-width="1.2" stroke-dasharray="2 1.5"/>
                            <path d="M9 8H15C17.2091 8 19 9.79086 19 12C19 14.2091 17.2091 16 15 16H12V20H9V8Z" fill="#fff8e8"/>
                            <circle cx="19.5" cy="19.5" r="2.5" fill="#c94c38"/>
                        </svg>
                    </span>
                    <span class="brand-text">
                        <span class="brand-badge">POS</span>
                        <span class="brand-title">Database</span>
                    </span>
                </a>
            </div>

            <nav class="site-nav" aria-label="Main Navigation">
                <ul class="nav-list" role="list">
                    <li class="nav-item">
                        <a href="<?= site_url('/') ?>" class="nav-link <?= ($activePage ?? '') === 'home' ? 'active' : '' ?>" <?= ($activePage ?? '') === 'home' ? 'aria-current="page"' : '' ?>>Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('about') ?>" class="nav-link <?= ($activePage ?? '') === 'about' ? 'active' : '' ?>" <?= ($activePage ?? '') === 'about' ? 'aria-current="page"' : '' ?>>About</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('customers') ?>" class="nav-link <?= ($activePage ?? '') === 'customers' ? 'active' : '' ?>" <?= ($activePage ?? '') === 'customers' ? 'aria-current="page"' : '' ?>>Customer Accounts</a>
                    </li>
                    <li class="nav-item">
                        <a href="<?= site_url('users') ?>" class="nav-link <?= ($activePage ?? '') === 'users' ? 'active' : '' ?>" <?= ($activePage ?? '') === 'users' ? 'aria-current="page"' : '' ?>>User Accounts</a>
                    </li>
                    <?php if (session()->get('user_id') !== null): ?>
                        <li class="nav-item">
                            <span class="nav-link nav-user" aria-label="Signed in user"><?= esc(session()->get('username')) ?></span>
                        </li>
                        <li class="nav-item">
                            <form action="<?= esc(site_url('logout'), 'attr') ?>" method="post">
                                <?= csrf_field() ?>
                                <button type="submit" class="nav-link nav-button">Log Out</button>
                            </form>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a href="<?= site_url('login') ?>" class="nav-link <?= ($activePage ?? '') === 'login' ? 'active' : '' ?>">Staff Login</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </header>

    <main id="main-content" class="main-content" role="main" tabindex="-1">
        <div class="container content-container">
            <?php if (session()->getFlashdata('message')): ?>
                <div class="flash-alert flash-success" role="status">
                    <span class="flash-icon" aria-hidden="true">&check;</span>
                    <span class="flash-text"><?= esc(session()->getFlashdata('message')) ?></span>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('error')): ?>
                <div class="flash-alert flash-danger" role="alert">
                    <span class="flash-icon" aria-hidden="true">&excl;</span>
                    <span class="flash-text"><?= esc(session()->getFlashdata('error')) ?></span>
                </div>
            <?php endif; ?>
            <?= $this->renderSection('content') ?>
        </div>
    </main>

    <footer class="site-footer" role="contentinfo">
        <div class="receipt-tear" aria-hidden="true"></div>
        <div class="container footer-container">
            <div class="receipt-meta" aria-hidden="true">
                <span class="receipt-tag">STORE DIRECTORY &bull; REGISTER SYSTEM</span>
                <span class="receipt-divider">&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;&mdash;</span>
                <span class="receipt-tag">CENTRAL MARKET</span>
            </div>
            <p class="footer-course">POS Database &bull; Retail Point of Sale &amp; Account Management</p>
            <p class="footer-subtext">Store Operations Portal &bull; Customer &amp; Staff Administration</p>
        </div>
    </footer>
</body>
</html>
