<?php
/**
 * Layout maître de l'application.
 * Chaque vue "client" ou "operateur" doit commencer par :
 *   <?= $this->extend('layout/main') ?>
 * définir la section 'content', et optionnellement :
 *   - $pageTitle (string) : titre affiché dans la topbar + <title>
 *   - $activeMenu (string) : identifiant du lien actif dans la sidebar
 */

$currentPath = uri_string();
$activeMenu  = $activeMenu ?? '';
$pageTitle   = $pageTitle ?? 'Mobile Money';
$role        = session()->get('role'); // 'client' ou 'operateur'
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($pageTitle) ?> — Mobile Money</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <style>
        /* --- à déplacer dans style.css si tu préfères tout centraliser --- */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1030;
        }
        .sidebar-overlay.show { display: block; }

        .sidebar-close-btn {
            display: none;
        }

        @media (max-width: 991.98px) {
            .app-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                height: 100vh;
                transform: translateX(-100%);
                transition: transform 0.25s ease;
                z-index: 1040;
                overflow-y: auto;
            }
            .app-sidebar.show {
                transform: translateX(0);
            }
            .sidebar-close-btn {
                display: inline-flex;
            }
            body.sidebar-open {
                overflow: hidden; /* empêche le scroll de fond derrière la sidebar ouverte */
            }
        }
    </style>
</head>
<body>
<div class="app-shell">

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- SIDEBAR -->
    <aside class="app-sidebar" id="appSidebar" aria-hidden="true">
        <div class="d-flex align-items-center justify-content-between">
            <div class="brand">
                <i class="bi bi-wallet2"></i>
                <span>Mobile Money</span>
            </div>
            <button class="btn btn-sm btn-light sidebar-close-btn" id="sidebarClose" type="button" aria-label="Fermer le menu">
                <i class="bi bi-x-lg"></i>
            </button>
        </div>

        <?php if ($role === 'client') { ?>
            <div class="nav-section-title">Client</div>
            <ul class="nav nav-pills flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'client-accueil' ? 'active' : '' ?>" href="<?= base_url('client') ?>">
                        <i class="bi bi-clock-history"></i> Historique
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'client-operation' ? 'active' : '' ?>" href="<?= base_url('client/operation') ?>">
                        <i class="bi bi-arrow-left-right"></i> Faire une opération
                    </a>
                </li>
            </ul>
        <?php } ?>

        <?php if ($role === 'operateur') { ?>
            <div class="nav-section-title">Back-office opérateur</div>
            <ul class="nav nav-pills flex-column mb-2">
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'op-operations' ? 'active' : '' ?>" href="<?= base_url('operateur/operations') ?>">
                        <i class="bi bi-diagram-3"></i> Types d'opération
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'op-frais' ? 'active' : '' ?>" href="<?= base_url('operateur/frais') ?>">
                        <i class="bi bi-percent"></i> Barèmes de frais
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'op-prefixes' ? 'active' : '' ?>" href="<?= base_url('operateur/prefixes') ?>">
                        <i class="bi bi-telephone"></i> Préfixes valables
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'op-situation' ? 'active' : '' ?>" href="<?= base_url('operateur/situation_compte_client') ?>">
                        <i class="bi bi-people"></i> Situation des comptes
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= $activeMenu === 'op-gains' ? 'active' : '' ?>" href="<?= base_url('operateur/gain_par_frais_operation') ?>">
                        <i class="bi bi-graph-up-arrow"></i> Gains par opération
                    </a>
                </li>
            </ul>
        <?php } ?>
    </aside>

    <!-- MAIN -->
    <div class="app-main">
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggle" type="button" aria-label="Ouvrir le menu" aria-expanded="false" aria-controls="appSidebar">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h1><?= esc($pageTitle) ?></h1>
            </div>
            <form action="<?= base_url('logout') ?>" method="GET">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>
                <button type="submit" class="btn btn-outline-secondary btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Déconnexion
                </button>
            </form>
        </header>

        <main class="app-content">
            <?= $this->renderSection('content') ?>
        </main>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const toggleBtn = document.getElementById('sidebarToggle');
    const closeBtn  = document.getElementById('sidebarClose');
    const sidebar   = document.getElementById('appSidebar');
    const overlay   = document.getElementById('sidebarOverlay');

    function openSidebar() {
        sidebar.classList.add('show');
        overlay.classList.add('show');
        document.body.classList.add('sidebar-open');
        toggleBtn.setAttribute('aria-expanded', 'true');
        sidebar.setAttribute('aria-hidden', 'false');
    }

    function closeSidebar() {
        sidebar.classList.remove('show');
        overlay.classList.remove('show');
        document.body.classList.remove('sidebar-open');
        toggleBtn.setAttribute('aria-expanded', 'false');
        sidebar.setAttribute('aria-hidden', 'true');
    }

    if (toggleBtn && sidebar && overlay) {
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.contains('show') ? closeSidebar() : openSidebar();
        });

        closeBtn.addEventListener('click', closeSidebar);
        overlay.addEventListener('click', closeSidebar);

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && sidebar.classList.contains('show')) {
                closeSidebar();
            }
        });

        // referme automatiquement si on repasse en desktop (évite un état "show" bloqué en resize)
        window.addEventListener('resize', () => {
            if (window.innerWidth >= 992) {
                closeSidebar();
            }
        });
    }
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>