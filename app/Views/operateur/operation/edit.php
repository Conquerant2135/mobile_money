<?php
$pageTitle  = "Modifier le type d'opération";
$activeMenu = 'op-operations';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">

        <?= $this->include('partials/flash') ?>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5 mb-4">Modifier l'opération #<?= esc($operation['id']) ?></h2>

                <form action="<?= base_url('operateur/operations/update/' . $operation['id']) ?>" method="post">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="nom" class="form-label">Nom</label>
                        <input type="text" class="form-control" name="nom" id="nom"
                               value="<?= old('nom', $operation['nom']) ?>" required>
                    </div>

                    <div class="mb-4">
                        <label for="code" class="form-label">Code</label>
                        <input type="text" class="form-control" name="code" id="code"
                               value="<?= old('code', $operation['code']) ?>" required>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Enregistrer les modifications
                        </button>
                        <a href="<?= base_url('operateur/operations') ?>" class="btn btn-outline-secondary">Annuler</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>