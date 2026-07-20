<?php
$pageTitle  = 'Préfixes valables';
$activeMenu = 'op-prefixes';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<?= $this->include('partials/flash') ?>

<div class="card mb-4">
    <div class="card-body">
        <h2 class="h6 mb-3">Ajouter un nouveau préfixe</h2>
        <form action="<?= base_url('operateur/prefixes/create') ?>" method="post" class="row g-3 align-items-end">
            <?= function_exists('csrf_field') ? csrf_field() : '' ?>

            <div class="col-6 col-md-3">
                <label for="prefix" class="form-label">Préfixe</label>
                <input type="text" class="form-control" name="prefix" id="prefix" value="<?= old('prefix') ?>" placeholder="ex: 034" required>
            </div>

            <div class="col-6 col-md-3">
                <div class="form-check form-switch pt-4">
                    <input class="form-check-input" type="checkbox" name="actif" id="actif" value="1" checked>
                    <label class="form-check-label" for="actif">Actif</label>
                </div>
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
        <h2 class="h6 mb-0">Liste des préfixes</h2>
    </div>
    <div class="table-responsive">
        <table class="table table-app mb-0 align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Préfixe</th>
                    <th>Statut</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prefixes) && is_array($prefixes)) : ?>
                    <?php foreach ($prefixes as $p) : ?>
                        <tr>
                            <td class="text-secondary">#<?= esc($p['id']) ?></td>
                            <td class="fw-medium"><?= esc($p['prefix']) ?></td>
                            <td>
                                <?php if ($p['actif'] == 1) : ?>
                                    <span class="badge badge-depot">Actif</span>
                                <?php else : ?>
                                    <span class="badge bg-secondary-subtle text-secondary">Inactif</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end">
                                <a href="<?= base_url('operateur/prefixes/edit/' . $p['id']) ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Modifier
                                </a>
                                <a href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Confirmer la suppression ?');">
                                    <i class="bi bi-trash"></i> Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="4" class="text-center text-secondary py-4">Aucun préfixe enregistré.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>