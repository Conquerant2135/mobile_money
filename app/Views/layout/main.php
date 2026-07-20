<?php
/**
 * Layout maître de l'application.
 * Chaque vue "client" ou "operateur" doit commencer par :
 *   <?= $this->extend('layout/main') ?>
 * définir la section 'content', et optionnellement :
 *   - $pageTitle (string) : titre affiché dans la topbar + <title>
 *   - $activeMenu (string) : identifiant du lien actif dans la sidebar
 */

$currentPath = uri_string(); // ex: client, operateur/frais, operateur/operations
$activeMenu  = $activeMenu ?? '';
$pageTitle   = $pageTitle ?? 'Mobile Money';
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
</head>
<body>
<div class="app-shell">

    <!-- SIDEBAR -->
    <aside class="app-sidebar" id="appSidebar">
        <div class="brand">
            <i class="bi bi-wallet2"></i>
            <span>Mobile Money</span>
        </div>

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
    </aside>

    <!-- MAIN -->
    <div class="app-main">
        <header class="app-topbar">
            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-light d-lg-none" id="sidebarToggle" type="button" aria-label="Menu">
                    <i class="bi bi-list fs-4"></i>
                </button>
                <h1><?= esc($pageTitle) ?></h1>
            </div>
            <form action="<?= base_url('logout') ?>" method="post">
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
    const sidebar = document.getElementById('appSidebar');
    if (toggleBtn && sidebar) {
        toggleBtn.addEventListener('click', () => sidebar.classList.toggle('show'));
    }
</script>
<?= $this->renderSection('scripts') ?>
</body>
</html>