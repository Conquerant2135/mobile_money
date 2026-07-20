<?php
$pageTitle  = 'Barèmes de frais';
$activeMenu = 'op-frais';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('partials/flash') ?>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter un barème de frais</h2>
        <form action="<?= base_url('operateur/frais/create') ?>" method="post" class="row g-3 align-items-end">
            <?= function_exists('csrf_field') ? csrf_field() : '' ?>

            <div class="col-12 col-md-3">
                <label for="operation_id" class="form-label">Opération</label>
                <select name="operation_id" id="operation_id" class="form-select" required>
                    <option value="">-- Sélectionner --</option>
                    <?php foreach ($operations as $op) : ?>
                        <option value="<?= esc($op['id']) ?>" <?= old('operation_id') == $op['id'] ? 'selected' : '' ?>>
                            <?= esc($op['nom']) ?> (<?= esc($op['code']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="col-6 col-md-2">
                <label for="min" class="form-label">Montant min</label>
                <input type="number" step="0.01" class="form-control" name="min" id="min" value="<?= old('min', '0') ?>" required>
            </div>

            <div class="col-6 col-md-2">
                <label for="max" class="form-label">Montant max</label>
                <input type="number" step="0.01" class="form-control" name="max" id="max" value="<?= old('max') ?>" required>
            </div>

            <div class="col-6 col-md-2">
                <label for="frais_val" class="form-label">Frais (Ar)</label>
                <input type="number" step="0.01" class="form-control" name="frais_val" id="frais_val" value="<?= old('frais_val') ?>" required>
            </div>

            <div class="col-6 col-md-3 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-bottom">
        <h2 class="h6 mb-0">Liste des barèmes</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Opération</th>
                    <th class="text-end">Min (Ar)</th>
                    <th class="text-end">Max (Ar)</th>
                    <th class="text-end">Frais (Ar)</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($frais_list) && is_array($frais_list)) : ?>
                    <?php foreach ($frais_list as $f) : ?>
                        <tr>
                            <td class="text-secondary">#<?= esc($f['id']) ?></td>
                            <td>
                                <?= esc($f['operation_nom']) ?>
                                <span class="badge bg-primary-subtle text-primary"><?= esc($f['operation_code']) ?></span>
                            </td>
                            <td class="text-end"><?= number_format((float) $f['min'], 2, ',', ' ') ?></td>
                            <td class="text-end"><?= number_format((float) $f['max'], 2, ',', ' ') ?></td>
                            <td class="text-end fw-semibold"><?= number_format((float) $f['frais_val'], 2, ',', ' ') ?></td>
                            <td class="text-end">
                                <a href="<?= base_url('operateur/frais/edit/' . $f['id']) ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="<?= base_url('operateur/frais/delete/' . $f['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Confirmer la suppression ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="6" class="text-center text-secondary py-4">Aucun barème trouvé.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>