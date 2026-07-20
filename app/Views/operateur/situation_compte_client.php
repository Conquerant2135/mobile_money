<?php
$pageTitle  = 'Situation des comptes';
$activeMenu = 'op-situation';

$totalSolde = 0;
if (!empty($clientSoldes) && is_array($clientSoldes)) {
    foreach ($clientSoldes as $c) {
        $totalSolde += (float) $c['solde'];
    }
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-kpi kpi-depot">
            <div class="text-secondary" style="font-size: var(--font-size-sm);">Solde total des comptes</div>
            <div class="fs-3 fw-semibold"><?= number_format($totalSolde, 2, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-kpi kpi-transfert">
            <div class="text-secondary" style="font-size: var(--font-size-sm);">Nombre de clients</div>
            <div class="fs-3 fw-semibold"><?= count($clientSoldes ?? []) ?></div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-bottom">
        <h2 class="h6 mb-0">Solde par client</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>Client</th>
                    <th class="text-end">Solde</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($clientSoldes) && is_array($clientSoldes)) : ?>
                    <?php foreach ($clientSoldes as $client) : ?>
                        <tr>
                            <td><?= esc($client['nom']) ?> <?= esc($client['prenom']) ?></td>
                            <td class="text-end fw-semibold"><?= number_format((float) $client['solde'], 2, ',', ' ') ?> Ar</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" class="text-center text-secondary py-4">Aucun client trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>