<?php
$pageTitle  = "Types d'opération";
$activeMenu = 'op-operations';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('partials/flash') ?>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter un type d'opération</h2>
        <form action="<?= base_url('operateur/operations/create') ?>" method="post" class="row g-3 align-items-end">
            <?= function_exists('csrf_field') ? csrf_field() : '' ?>

            <div class="col-12 col-md-4">
                <label for="nom" class="form-label">Nom</label>
                <input type="text" class="form-control" name="nom" id="nom" value="<?= old('nom') ?>" placeholder="ex: Dépôt" required>
            </div>

            <div class="col-12 col-md-4">
                <label for="code" class="form-label">Code</label>
                <input type="text" class="form-control" name="code" id="code" value="<?= old('code') ?>" placeholder="ex: DEP" required>
            </div>

            <div class="col-12 col-md-4 d-grid">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Ajouter
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-white border-bottom">
        <h2 class="h6 mb-0">Liste des opérations</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Code</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($operations) && is_array($operations)) : ?>
                    <?php foreach ($operations as $o) : ?>
                        <tr>
                            <td class="text-secondary">#<?= esc($o['id']) ?></td>
                            <td class="fw-medium"><?= esc($o['nom']) ?></td>
                            <td><span class="badge bg-primary-subtle text-primary"><?= esc($o['code']) ?></span></td>
                            <td class="text-end">
                                <a href="<?= base_url('operateur/operations/edit/' . $o['id']) ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="<?= base_url('operateur/operations/delete/' . $o['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Confirmer la suppression ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Aucune opération enregistrée.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>