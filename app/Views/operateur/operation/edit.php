<?php
$pageTitle  = 'Modifier le préfixe';
$activeMenu = 'op-prefixes';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">

        <?= $this->include('partials/flash') ?>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Modifier le préfixe #<?= esc($prefix['id']) ?></h2>

                <form action="<?= base_url('operateur/prefixes/update/' . $prefix['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="prefix" class="form-label">Préfixe</label>
                        <input type="text" class="form-control" name="prefix" id="prefix"
                               value="<?= old('prefix', $prefix['prefix']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1"
                                   <?= old('actif', $prefix['actif']) == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label" for="actif">Actif</label>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Enregistrer
                        </button>
                        <a href="<?= base_url('operateur/prefixes') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>