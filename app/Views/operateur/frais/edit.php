<?php
$pageTitle  = 'Modifier le barème de frais';
$activeMenu = 'op-frais';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">

        <?= $this->include('partials/flash') ?>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Modifier le barème #<?= esc($frais['id']) ?></h2>

                <form action="<?= base_url('operateur/frais/update/' . $frais['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="operation_id" class="form-label">Opération</label>
                        <select name="operation_id" id="operation_id" class="form-select" required>
                            <?php foreach ($operations as $op) : ?>
                                <option value="<?= esc($op['id']) ?>" <?= old('operation_id', $frais['operation_id']) == $op['id'] ? 'selected' : '' ?>>
                                    <?= esc($op['nom']) ?> (<?= esc($op['code']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-4">
                            <label for="min" class="form-label">Min</label>
                            <input type="number" step="0.01" class="form-control" name="min" id="min"
                                   value="<?= old('min', $frais['min']) ?>" required>
                        </div>
                        <div class="col-4">
                            <label for="max" class="form-label">Max</label>
                            <input type="number" step="0.01" class="form-control" name="max" id="max"
                                   value="<?= old('max', $frais['max']) ?>" required>
                        </div>
                        <div class="col-4">
                            <label for="frais_val" class="form-label">Frais (Ar)</label>
                            <input type="number" step="0.01" class="form-control" name="frais_val" id="frais_val"
                                   value="<?= old('frais_val', $frais['frais_val']) ?>" required>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Enregistrer
                        </button>
                        <a href="<?= base_url('operateur/frais') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>