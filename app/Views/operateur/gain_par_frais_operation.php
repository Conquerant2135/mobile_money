<?php
$pageTitle  = "Gains par opération";
$activeMenu = 'op-gains';
$totalGain  = $gainRetait + $gainTransfert;
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row g-3 mb-4">
    <div class="col-12 col-md-4">
        <div class="card-kpi kpi-retrait">
            <div class="text-secondary" style="font-size: var(--font-size-sm);">Gain — Retrait</div>
            <div class="fs-3 fw-semibold"><?= number_format($gainRetait, 2, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-kpi kpi-transfert">
            <div class="text-secondary" style="font-size: var(--font-size-sm);">Gain — Transfert</div>
            <div class="fs-3 fw-semibold"><?= number_format($gainTransfert, 2, ',', ' ') ?> Ar</div>
        </div>
    </div>
    <div class="col-12 col-md-4">
        <div class="card-kpi kpi-depot">
            <div class="text-secondary" style="font-size: var(--font-size-sm);">Total des gains</div>
            <div class="fs-3 fw-semibold"><?= number_format($totalGain, 2, ',', ' ') ?> Ar</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-bottom">
        <h2 class="h6 mb-0">Détail des gains générés par les frais d'opération</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>Opération</th>
                    <th class="text-end">Gain par frais</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-retrait">Retrait</span></td>
                    <td class="text-end fw-semibold"><?= number_format($gainRetait, 2, ',', ' ') ?> Ar</td>
                </tr>
                <tr>
                    <td><span class="badge badge-transfert">Transfert</span></td>
                    <td class="text-end fw-semibold"><?= number_format($gainTransfert, 2, ',', ' ') ?> Ar</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="fw-bold">
                    <td>Total des gains</td>
                    <td class="text-end"><?= number_format($totalGain, 2, ',', ' ') ?> Ar</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>