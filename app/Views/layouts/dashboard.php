<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&display=swap');

        :root {
            --bg1: #060612;
            --bg2: #0c0c22;
            --glass: rgba(255, 255, 255, 0.05);
            --glass2: rgba(255, 255, 255, 0.08);
            --border: rgba(255, 255, 255, 0.1);
            --text1: #f0f4ff;
            --text2: rgba(240, 240, 255, 0.65);
            --text3: rgba(240, 240, 255, 0.35);
            --cyan: #00e5ff;
            --violet: #7c6fff;
            --green: #10b981;
            --amber: #f59e0b;
            --red: #ef4444;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg1);
            color: var(--text1);
            min-height: 100vh;
            overflow-x: hidden;
        }

        .btn-info {
            border: none;
            background: linear-gradient(135deg, #7c6fff, #00e5ff);
            color: #fff;
            font-weight: 700;
        }

        .btn-info:hover,
        .btn-info:focus {
            color: #fff;
            transform: translateY(-1px);
            box-shadow: 0 8px 24px rgba(124, 111, 255, 0.35);
        }

        .btn-outline-light {
            color: var(--text1);
            border-color: var(--border);
            background: rgba(255, 255, 255, 0.02);
        }

        .btn-outline-light:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
        }

        .form-control,
        .form-select,
        textarea.form-control {
            background: rgba(255, 255, 255, 0.06);
            border-color: rgba(255, 255, 255, 0.14);
            color: var(--text1);
        }

        .form-control::placeholder,
        textarea.form-control::placeholder {
            color: rgba(240, 240, 255, 0.45);
        }

        .form-select option,
        .form-control option {
            background: #141a2f;
            color: #f0f4ff;
        }

        .form-select option:checked,
        .form-control option:checked {
            background: linear-gradient(135deg, #7c6fff, #00e5ff);
            color: #ffffff;
        }

        .form-control:focus,
        .form-select:focus,
        textarea.form-control:focus {
            background: rgba(124, 111, 255, 0.08);
            border-color: rgba(124, 111, 255, 0.45);
            color: var(--text1);
            box-shadow: 0 0 0 0.2rem rgba(124, 111, 255, 0.16);
        }

        .form-label {
            color: var(--text2);
            font-size: 0.86rem;
            font-weight: 600;
            margin-bottom: 0.45rem;
        }

        .table {
            --bs-table-color: var(--text1);
            --bs-table-border-color: rgba(255, 255, 255, 0.08);
        }

        .badge {
            border-radius: 999px;
            padding: 0.42rem 0.62rem;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .bg-orbs {
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: 0;
        }

        .orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(80px);
            opacity: 0.2;
        }

        .orb1 {
            width: 600px;
            height: 600px;
            background: radial-gradient(circle, #3b0ecc, transparent);
            top: -150px;
            left: -150px;
        }

        .orb2 {
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, #0891b2, transparent);
            bottom: -100px;
            right: -100px;
        }

        .layout {
            position: relative;
            z-index: 1;
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 240px;
            min-width: 240px;
            background: var(--glass);
            border-right: 1px solid var(--border);
            backdrop-filter: blur(20px);
            display: flex;
            flex-direction: column;
        }

        .sidebar-logo {
            padding: 24px 20px;
            border-bottom: 1px solid var(--border);
        }

        .logo-badge {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 700;
            color: #fff;
            font-size: 16px;
            flex-shrink: 0;
        }

        .logo-text {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.2px;
        }

        .logo-sub {
            font-size: 12px;
            color: var(--text3);
            margin-top: 2px;
        }

        .user-chip {
            margin: 14px;
            padding: 12px;
            border-radius: 12px;
            background: var(--glass2);
            border: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: linear-gradient(135deg, var(--violet), #ec4899);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 600;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 13px;
            font-weight: 500;
        }

        .user-role {
            font-size: 10px;
            color: #fff;
            background: linear-gradient(90deg, #00a8cc, var(--violet));
            padding: 2px 7px;
            border-radius: 20px;
            display: inline-block;
            margin-top: 2px;
        }

        .nav-wrap {
            flex: 1;
            padding: 8px 12px;
        }

        .nav-section {
            font-size: 10px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 10px 8px 6px;
            font-weight: 500;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 10px;
            margin-bottom: 3px;
            color: var(--text2);
            text-decoration: none;
            font-size: 13px;
            border: 1px solid transparent;
        }

        .nav-item:hover {
            background: var(--glass2);
            color: var(--text1);
        }

        .nav-item.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(124, 58, 237, 0.15));
            border-color: rgba(0, 212, 255, 0.25);
            color: var(--cyan);
        }

        .sidebar-bottom {
            padding: 14px;
        }

        .logout-btn {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border-radius: 10px;
            text-decoration: none;
            color: var(--red);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .logout-btn:hover {
            background: rgba(239, 68, 68, 0.1);
        }

        .main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .topbar {
            min-height: 62px;
            background: var(--glass);
            border-bottom: 1px solid var(--border);
            backdrop-filter: blur(20px);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 22px;
            gap: 16px;
        }

        .topbar-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 16px;
            font-weight: 600;
        }

        .topbar-sub {
            font-size: 12px;
            color: var(--text3);
        }

        .live-pill {
            display: flex;
            align-items: center;
            gap: 7px;
            color: var(--text2);
            font-size: 12px;
            background: var(--glass2);
            border: 1px solid var(--border);
            border-radius: 20px;
            padding: 6px 12px;
        }

        .live-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: var(--green);
            animation: pulse 2s infinite;
        }

        .content {
            padding: 22px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .stat-card {
            border-radius: 16px;
            padding: 16px;
            background: var(--glass);
            border: 1px solid var(--border);
            backdrop-filter: blur(20px);
        }

        .stat-label {
            font-size: 11px;
            color: var(--text3);
            text-transform: uppercase;
            letter-spacing: 0.8px;
        }

        .stat-value {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 26px;
            font-weight: 700;
            margin: 4px 0;
        }

        .stat-foot {
            font-size: 12px;
            color: var(--text2);
        }

        .panel {
            border-radius: 18px;
            padding: 20px;
            background: var(--glass);
            border: 1px solid var(--border);
            backdrop-filter: blur(20px);
        }

        .panel-title {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 15px;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .panel-muted {
            color: var(--text2);
            font-size: 13px;
            margin: 0;
        }

        .prediksi-theme {
            color: var(--text1);
        }

        .prediksi-theme h1,
        .prediksi-theme h2,
        .prediksi-theme h3,
        .prediksi-theme h4,
        .prediksi-theme h5,
        .prediksi-theme h6,
        .prediksi-theme .card-title,
        .prediksi-theme .panel-title {
            color: #edf2ff;
            font-weight: 600;
            letter-spacing: 0.1px;
        }

        .prediksi-theme p,
        .prediksi-theme li,
        .prediksi-theme td,
        .prediksi-theme th,
        .prediksi-theme small,
        .prediksi-theme .small,
        .prediksi-theme .form-label,
        .prediksi-theme .form-check-label,
        .prediksi-theme .text-muted,
        .prediksi-theme .text-secondary {
            color: rgba(230, 237, 255, 0.8) !important;
        }

        .prediksi-theme .card,
        .prediksi-theme .alert,
        .prediksi-theme .example-box,
        .prediksi-theme .accordion-item,
        .prediksi-theme .accordion-button,
        .prediksi-theme .accordion-body {
            background: rgba(255, 255, 255, 0.045) !important;
            border: 1px solid rgba(255, 255, 255, 0.1) !important;
            color: #eaf0ff;
            backdrop-filter: blur(16px);
        }

        .prediksi-theme .card-header,
        .prediksi-theme .card-footer,
        .prediksi-theme .bg-light,
        .prediksi-theme .table-light,
        .prediksi-theme .alert-light {
            background: rgba(255, 255, 255, 0.04) !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
            color: #eaf0ff !important;
        }

        .prediksi-theme .prediksi-soft-block {
            background: rgba(255, 255, 255, 0.04) !important;
            border: 1px solid rgba(255, 255, 255, 0.12) !important;
            color: #eaf0ff !important;
        }

        .prediksi-theme .table > :not(caption) > .table-light > *,
        .prediksi-theme .table-light > tr > th,
        .prediksi-theme .table-light > tr > td {
            background: rgba(255, 255, 255, 0.04) !important;
            color: #eaf0ff !important;
            border-color: rgba(255, 255, 255, 0.1) !important;
        }

        .prediksi-theme .table > :not(caption) > * > * {
            color: rgba(234, 240, 255, 0.9);
        }

        .prediksi-theme .table {
            --bs-table-bg: transparent;
            --bs-table-hover-bg: rgba(255, 255, 255, 0.05);
            --bs-table-color: #eaf0ff;
            --bs-table-border-color: rgba(255, 255, 255, 0.1);
        }

        .prediksi-theme .btn-outline-primary,
        .prediksi-theme .btn-outline-success,
        .prediksi-theme .btn-outline-warning,
        .prediksi-theme .btn-outline-danger,
        .prediksi-theme .btn-outline-info {
            border-color: rgba(255, 255, 255, 0.2);
            color: #eaf0ff;
            background: rgba(255, 255, 255, 0.03);
        }

        .prediksi-theme .btn-outline-primary:hover,
        .prediksi-theme .btn-outline-success:hover,
        .prediksi-theme .btn-outline-warning:hover,
        .prediksi-theme .btn-outline-danger:hover,
        .prediksi-theme .btn-outline-info:hover {
            background: rgba(124, 111, 255, 0.14);
            border-color: rgba(124, 111, 255, 0.34);
            color: #f4f7ff;
        }

        .prediksi-theme .badge.bg-warning,
        .prediksi-theme .text-bg-warning {
            background: rgba(245, 158, 11, 0.2) !important;
            color: #ffe4b0 !important;
        }

        .prediksi-theme .badge.bg-danger,
        .prediksi-theme .text-bg-danger {
            background: rgba(239, 68, 68, 0.2) !important;
            color: #ffc0c0 !important;
        }

        .prediksi-theme .badge.bg-success,
        .prediksi-theme .text-bg-success {
            background: rgba(16, 185, 129, 0.22) !important;
            color: #b8f6de !important;
        }

        .prediksi-theme .badge.bg-info,
        .prediksi-theme .text-bg-info,
        .prediksi-theme .bg-info {
            background: rgba(0, 229, 255, 0.18) !important;
            color: #d5f9ff !important;
        }

        .prediksi-theme .text-primary,
        .prediksi-theme .text-info,
        .prediksi-theme .text-success,
        .prediksi-theme .text-warning,
        .prediksi-theme .text-danger {
            color: #eaf0ff !important;
        }

        .magic-bento-card {
            position: relative;
            overflow: hidden;
            transform-style: preserve-3d;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
            --glow-x: 50%;
            --glow-y: 50%;
            --glow-intensity: 0;
            --glow-radius: 260px;
        }

        .magic-bento-card::after {
            content: '';
            position: absolute;
            inset: 0;
            padding: 4px;
            border-radius: inherit;
            background: radial-gradient(
                var(--glow-radius) circle at var(--glow-x) var(--glow-y),
                rgba(124, 111, 255, calc(var(--glow-intensity) * 0.85)) 0%,
                rgba(0, 229, 255, calc(var(--glow-intensity) * 0.38)) 32%,
                transparent 64%
            );
            -webkit-mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            -webkit-mask-composite: xor;
            mask:
                linear-gradient(#fff 0 0) content-box,
                linear-gradient(#fff 0 0);
            mask-composite: exclude;
            pointer-events: none;
            opacity: 1;
            z-index: 1;
        }

        .magic-bento-card:hover {
            box-shadow: 0 10px 28px rgba(18, 22, 52, 0.48);
        }

        .magic-bento-card > * {
            position: relative;
            z-index: 2;
        }

        .magic-bento-particle {
            position: absolute;
            width: 4px;
            height: 4px;
            border-radius: 50%;
            background: rgba(124, 111, 255, 1);
            box-shadow: 0 0 8px rgba(0, 229, 255, 0.65);
            pointer-events: none;
            z-index: 3;
        }

        .global-bento-spotlight {
            position: fixed;
            width: 700px;
            height: 700px;
            border-radius: 50%;
            pointer-events: none;
            transform: translate(-50%, -50%);
            background: radial-gradient(
                circle,
                rgba(124, 111, 255, 0.16) 0%,
                rgba(0, 229, 255, 0.1) 18%,
                rgba(0, 229, 255, 0.04) 36%,
                transparent 70%
            );
            opacity: 0;
            z-index: 25;
            mix-blend-mode: screen;
            will-change: transform, opacity;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.45;
            }
        }

        @media (max-width: 991.98px) {
            .layout {
                flex-direction: column;
            }

            .sidebar {
                width: 100%;
                min-width: 100%;
                border-right: none;
                border-bottom: 1px solid var(--border);
            }

            .stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 575.98px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }

            .topbar {
                padding: 10px 14px;
                align-items: flex-start;
                flex-direction: column;
            }

            .content {
                padding: 14px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-orbs">
        <div class="orb orb1"></div>
        <div class="orb orb2"></div>
    </div>

    <?php
    $isAdmin = (($level ?? '') === 'admin');
    $displayName = (string) ($username ?? 'User');
    $initial = strtoupper(substr($displayName, 0, 1));
    $activeMenu = (string) ($activeMenu ?? 'dashboard');
    $enableDashboardMotion = ($activeMenu === 'dashboard');
    $lowStockCount = isset($lowStockCount) ? (int) $lowStockCount : 0;

    if (! isset($lowStockCount) && session()->get('logged_in') === true) {
        try {
            $db = \Config\Database::connect();
            if ($db->tableExists('tb_products')) {
                $fields = $db->getFieldNames('tb_products');
                if (in_array('stock', $fields, true) && in_array('min_stock', $fields, true)) {
                    $lowStockCount = (int) $db->table('tb_products')
                        ->where('stock <= min_stock')
                        ->countAllResults();
                }
            }
        } catch (\Throwable $e) {
            $lowStockCount = 0;
        }
    }
    ?>

    <div class="layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <div class="logo-badge">
                    <div class="logo-icon">LEJ</div>
                    <div>
                        <div class="logo-text">Toko LEJ</div>
                        <div class="logo-sub">Sales & Prediction System</div>
                    </div>
                </div>
            </div>

            <div class="user-chip">
                <div class="avatar"><?= esc($initial) ?></div>
                <div>
                    <div class="user-name"><?= esc($displayName) ?></div>
                    <div class="user-role"><?= esc(strtoupper((string) ($level ?? 'user'))) ?></div>
                </div>
            </div>

            <div class="nav-wrap">
                <div class="nav-section">Main</div>
                <a class="nav-item <?= $activeMenu === 'dashboard' ? 'active' : '' ?>" href="<?= base_url('/dashboard') ?>">Dashboard</a>
                <a class="nav-item <?= $activeMenu === 'produk' ? 'active' : '' ?>" href="<?= base_url('/produk') ?>">Produk</a>
                <a class="nav-item <?= $activeMenu === 'stok-masuk' ? 'active' : '' ?>" href="<?= base_url('/stok-masuk') ?>">
                    <span>Stok Masuk</span>
                    <?php if ($lowStockCount > 0): ?>
                        <span class="badge text-bg-warning ms-auto"><?= esc((string) $lowStockCount) ?></span>
                    <?php endif; ?>
                </a>

                <div class="nav-section">Operasional</div>
                <a class="nav-item <?= $activeMenu === 'transaksi' ? 'active' : '' ?>" href="<?= base_url('/transaksi') ?>">Transaksi</a>
                <?php if ($isAdmin): ?>
                    <a class="nav-item <?= $activeMenu === 'prediksi' ? 'active' : '' ?>" href="<?= base_url('/prediksi') ?>">Prediksi Penjualan</a>
                <?php endif; ?>

                <div class="nav-section">Master Data</div>
                <a class="nav-item <?= $activeMenu === 'pelanggan' ? 'active' : '' ?>" href="<?= base_url('/pelanggan') ?>">Pelanggan</a>
                <a class="nav-item <?= $activeMenu === 'suplier' ? 'active' : '' ?>" href="<?= base_url('/suplier') ?>">Suplier</a>
                <?php if ($isAdmin): ?>
                    <a class="nav-item <?= $activeMenu === 'pengguna' ? 'active' : '' ?>" href="<?= base_url('/pengguna') ?>">Pengguna</a>
                <?php endif; ?>
            </div>

            <div class="sidebar-bottom">
                <a class="logout-btn" href="<?= base_url('/logout') ?>">Logout</a>
            </div>
        </aside>

        <main class="main">
            <header class="topbar">
                <div>
                    <div class="topbar-title"><?= esc($title ?? 'Dashboard') ?></div>
                    <div class="topbar-sub">Sistem Informasi Penjualan dan Prediksi Stok</div>
                </div>
                <div class="live-pill">
                    <span class="live-dot"></span>
                    Live Session
                </div>
            </header>

            <div class="content">
                <?php if ($lowStockCount > 0): ?>
                    <div class="alert alert-warning mb-0" role="alert">
                        Peringatan stok menipis: ada <?= esc((string) $lowStockCount) ?> produk perlu segera restok.
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success" role="alert">
                        <?= esc((string) session()->getFlashdata('success')) ?>
                    </div>
                <?php endif; ?>

                <?php if (session()->getFlashdata('error')): ?>
                    <div class="alert alert-danger" role="alert">
                        <?= esc((string) session()->getFlashdata('error')) ?>
                    </div>
                <?php endif; ?>

                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script>
        (() => {
            if (typeof gsap === 'undefined') {
                return;
            }

            const enableCardMotion = <?= $enableDashboardMotion ? 'true' : 'false' ?>;

            const content = document.querySelector('.content');
            if (!content) {
                return;
            }

            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            const disableAnimations = prefersReducedMotion;
            const spotlightRadius = 300;
            const particleCount = 12;

            const cards = Array.from(content.querySelectorAll('.stat-card, .panel, .card'))
                .filter((el) => !el.classList.contains('no-magic-bento'));

            cards.forEach((card) => {
                card.classList.add('magic-bento-card');
            });

            if (!cards.length || disableAnimations) {
                return;
            }

            const spotlight = document.createElement('div');
            spotlight.className = 'global-bento-spotlight';
            document.body.appendChild(spotlight);

            const updateGlowForCard = (card, mouseX, mouseY, intensity) => {
                const rect = card.getBoundingClientRect();
                const relativeX = ((mouseX - rect.left) / rect.width) * 100;
                const relativeY = ((mouseY - rect.top) / rect.height) * 100;
                card.style.setProperty('--glow-x', `${relativeX}%`);
                card.style.setProperty('--glow-y', `${relativeY}%`);
                card.style.setProperty('--glow-intensity', String(intensity));
                card.style.setProperty('--glow-radius', `${spotlightRadius}px`);
            };

            const animateParticles = (card) => {
                const rect = card.getBoundingClientRect();
                for (let i = 0; i < particleCount; i++) {
                    const p = document.createElement('span');
                    p.className = 'magic-bento-particle';
                    p.style.left = `${Math.random() * rect.width}px`;
                    p.style.top = `${Math.random() * rect.height}px`;
                    card.appendChild(p);

                    gsap.fromTo(
                        p,
                        { scale: 0, opacity: 0 },
                        {
                            scale: 1,
                            opacity: 0.9,
                            duration: 0.28,
                            ease: 'back.out(1.7)',
                        }
                    );

                    gsap.to(p, {
                        x: (Math.random() - 0.5) * 90,
                        y: (Math.random() - 0.5) * 90,
                        opacity: 0,
                        duration: 1.2 + Math.random() * 0.8,
                        ease: 'power2.out',
                        onComplete: () => p.remove(),
                    });
                }
            };

            cards.forEach((card) => {
                card.addEventListener('mouseenter', () => {
                    if (!enableCardMotion) {
                        return;
                    }

                    animateParticles(card);
                    gsap.to(card, {
                        rotateX: 4,
                        rotateY: 4,
                        duration: 0.25,
                        ease: 'power2.out',
                        transformPerspective: 900,
                    });
                });

                card.addEventListener('mousemove', (e) => {
                    if (!enableCardMotion) {
                        return;
                    }

                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;

                    const rotateX = ((y - centerY) / centerY) * -8;
                    const rotateY = ((x - centerX) / centerX) * 8;
                    const magnetX = (x - centerX) * 0.03;
                    const magnetY = (y - centerY) * 0.03;

                    gsap.to(card, {
                        rotateX,
                        rotateY,
                        x: magnetX,
                        y: magnetY,
                        duration: 0.18,
                        ease: 'power2.out',
                    });
                });

                card.addEventListener('mouseleave', () => {
                    if (enableCardMotion) {
                        gsap.to(card, {
                            rotateX: 0,
                            rotateY: 0,
                            x: 0,
                            y: 0,
                            duration: 0.28,
                            ease: 'power2.out',
                        });
                    }

                    card.style.setProperty('--glow-intensity', '0');
                });

                card.addEventListener('click', (e) => {
                    const rect = card.getBoundingClientRect();
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;

                    const ripple = document.createElement('div');
                    const radius = Math.max(rect.width, rect.height) * 1.1;
                    ripple.style.cssText = `
                        position:absolute;
                        width:${radius}px;
                        height:${radius}px;
                        border-radius:50%;
                        left:${x - radius / 2}px;
                        top:${y - radius / 2}px;
                        background:radial-gradient(circle, rgba(124,111,255,0.35) 0%, rgba(0,229,255,0.2) 35%, transparent 72%);
                        pointer-events:none;
                        z-index:3;
                    `;
                    card.appendChild(ripple);

                    gsap.fromTo(
                        ripple,
                        { scale: 0, opacity: 1 },
                        {
                            scale: 1,
                            opacity: 0,
                            duration: 0.75,
                            ease: 'power2.out',
                            onComplete: () => ripple.remove(),
                        }
                    );
                });
            });

            document.addEventListener('mousemove', (e) => {
                const rect = content.getBoundingClientRect();
                const inside = e.clientX >= rect.left && e.clientX <= rect.right && e.clientY >= rect.top && e.clientY <= rect.bottom;

                if (!inside) {
                    gsap.to(spotlight, { opacity: 0, duration: 0.25, ease: 'power2.out' });
                    cards.forEach((card) => card.style.setProperty('--glow-intensity', '0'));
                    return;
                }

                gsap.to(spotlight, {
                    left: e.clientX,
                    top: e.clientY,
                    opacity: 0.8,
                    duration: 0.14,
                    ease: 'power2.out',
                });

                const proximity = spotlightRadius * 0.5;
                const fadeDistance = spotlightRadius * 0.78;

                cards.forEach((card) => {
                    const cardRect = card.getBoundingClientRect();
                    const centerX = cardRect.left + cardRect.width / 2;
                    const centerY = cardRect.top + cardRect.height / 2;
                    const distance = Math.hypot(e.clientX - centerX, e.clientY - centerY) - Math.max(cardRect.width, cardRect.height) / 2;
                    const effectiveDistance = Math.max(0, distance);

                    let glow = 0;
                    if (effectiveDistance <= proximity) {
                        glow = 1;
                    } else if (effectiveDistance <= fadeDistance) {
                        glow = (fadeDistance - effectiveDistance) / (fadeDistance - proximity);
                    }

                    updateGlowForCard(card, e.clientX, e.clientY, glow);
                });
            });
        })();
    </script>
</body>

</html>