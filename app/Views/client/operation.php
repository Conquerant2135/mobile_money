<?php
$pageTitle  = 'Faire une opération';
$activeMenu = 'client-operation';
?>
<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-6">

        <?= $this->include('partials/flash') ?>

        <div class="card">
            <div class="card-body p-4">
                <h2 class="h5 mb-1">Nouvelle opération</h2>
                <p class="text-secondary mb-4" style="font-size: var(--font-size-sm);">
                    Choisissez le type d'opération puis renseignez les informations nécessaires.
                </p>

                <form action="<?= base_url('client/operation') ?>" method="POST">
                    <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                    <div class="mb-3">
                        <label for="operation" class="form-label">Opération</label>
                        <select name="operation" id="operation" class="form-select">
                            <?php foreach ($operations as $op) : ?>
                                <option value="<?= esc($op['id']) ?>"><?= esc($op['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="montant" class="form-label">Montant</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="montant" name="montant" placeholder="0">
                            <span class="input-group-text bg-white">Ar</span>
                        </div>
                    </div>

                    <div class="mb-3" id="phone-field" style="display:none;">
                        <label for="phone" class="form-label">Téléphone du destinataire</label>
                        <input type="text" class="form-control" name="phone" id="phone" placeholder="034xxxxxxx">
                    </div>

                    <div class="mb-3" id="desc-field" style="display:none;">
                        <label for="desc" class="form-label">Description</label>
                        <textarea class="form-control" name="desc" id="desc" rows="3"></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2" id="submit-stuff">
                        Dépôt
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="<?= base_url('script/client/form.js') ?>"></script>
<?= $this->endSection() ?>