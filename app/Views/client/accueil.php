<?php
$pageTitle  = 'Historique des opérations';
$activeMenu = 'client-accueil';

/** Retourne la classe de badge Bootstrap selon le libellé de l'opération. */
if (!function_exists('badgeClassForOperation')) {
    function badgeClassForOperation(string $nom): string
    {
        $nom = mb_strtolower($nom);
        if (strpos($nom, 'depot') !== false || strpos($nom, 'dépôt') !== false) {
            return 'badge-depot';
        }
        if (strpos($nom, 'retrait') !== false) {
            return 'badge-retrait';
        }
        if (strpos($nom, 'transfert') !== false) {
            return 'badge-transfert';
        }
        return 'bg-secondary-subtle text-secondary';
    }
}
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <p class="text-secondary mb-0">Retrouvez l'ensemble de vos opérations et filtrez-les selon vos besoins.</p>
    </div>
    <a href="<?= base_url('client/operation') ?>" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Faire une opération
    </a>
</div>

<!-- Filtres -->
<div class="card mb-4">
    <div class="card-body">
        <form action="<?= base_url('client') ?>" method="GET" class="row g-3 align-items-end">
            <div class="col-12 col-md-3">
                <label for="operation_id" class="form-label">Opération</label>
                <select name="operation_id" id="operation_id" class="form-select">
                    <option value="">Toutes</option>
                    <?php foreach ($operations as $op) : ?>
                        <option value="<?= esc($op['id']) ?>" <?= ($filters['operation_id'] ?? '') == $op['id'] ? 'selected' : '' ?>>
                            <?= esc($op['nom']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="date_debut" class="form-label">Du</label>
                <input type="date" name="date_debut" id="date_debut" class="form-control"
                       value="<?= esc($filters['date_debut'] ?? '') ?>">
            </div>

            <div class="col-6 col-md-2">
                <label for="date_fin" class="form-label">Au</label>
                <input type="date" name="date_fin" id="date_fin" class="form-control"
                       value="<?= esc($filters['date_fin'] ?? '') ?>">
            </div>

            <div class="col-6 col-md-2">
                <label for="per_page" class="form-label">Par page</label>
                <select name="per_page" id="per_page" class="form-select">
                    <?php foreach ($perPageOptions as $option) : ?>
                        <option value="<?= esc($option) ?>" <?= ($perPage ?? '') == $option ? 'selected' : '' ?>>
                            <?= esc($option) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-3 d-grid d-md-block">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel"></i> Filtrer
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Tableau -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>Réf.</th>
                    <th>Opération</th>
                    <th>Destinataire</th>
                    <th class="text-end">Montant</th>
                    <th class="text-end">Frais</th>
                    <th>Description</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($historiques)) : ?>
                    <?php foreach ($historiques as $histo) : ?>
                        <tr>
                            <td class="text-secondary"><?= esc($histo['ref']) ?></td>
                            <td>
                                <span class="badge <?= badgeClassForOperation($histo['operation']) ?>">
                                    <?= esc($histo['operation']) ?>
                                </span>
                            </td>
                            <td><?= esc($histo['destinataire_numero'] ?? '-') ?></td>
                            <td class="text-end fw-semibold"><?= number_format((float) $histo['montant'], 2, ',', ' ') ?> Ar</td>
                            <td class="text-end text-secondary"><?= number_format((float) $histo['frais'], 2, ',', ' ') ?> Ar</td>
                            <td class="text-secondary"><?= esc($histo['description'] ?? '-') ?></td>
                            <td class="text-secondary"><?= esc($histo['date_op']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="7" class="text-center text-secondary py-4">
                            Aucune opération trouvée pour ces filtres.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if (isset($pager)) : ?>
        <div class="card-body border-top">
            <?= $pager->links() ?>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>