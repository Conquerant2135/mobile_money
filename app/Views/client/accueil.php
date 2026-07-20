<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
</head>

<body>
    <ul>
        <li><a href="<?= base_url("/client/operation") ?>">Faire une operation</a></li>
    </ul>

    <form action="<?= base_url("/client/") ?>" method="GET" class="filter-form">
        <div class="form-group">
            <label for="operation_id">Operation :</label>
            <select name="operation_id" id="operation_id">
                <option value="">Toutes</option>
                <?php foreach ($operations as $op) { ?>
                    <option value="<?= esc($op["id"]) ?>" <?= ($filters['operation_id'] == $op["id"]) ? 'selected' : '' ?>>
                        <?= esc($op["nom"]) ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="per_page">Par page :</label>
            <select name="per_page" id="per_page">
                <?php foreach ($perPageOptions as $option) { ?>
                    <option value="<?= $option ?>" <?= ($perPage == $option) ? 'selected' : '' ?>>
                        <?= $option ?>
                    </option>
                <?php } ?>
            </select>
        </div>
        <div class="form-group">
            <label for="date_debut">Du :</label>
            <input type="date" name="date_debut" id="date_debut" value="<?= esc($filters['date_debut'] ?? '') ?>">
        </div>

        <div class="form-group">
            <label for="date_fin">Au :</label>
            <input type="date" name="date_fin" id="date_fin" value="<?= esc($filters['date_fin'] ?? '') ?>">
        </div>

        <button type="submit">Filtrer</button>
    </form>

    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>REF</th>
                    <th>Operation</th>
                    <th>Destinataire</th>
                    <th>Montant</th>
                    <th>Frais</th>
                    <th>Description</th>
                    <th>Date operation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($historiques as $histo) { ?>
                    <tr>
                        <td><?= esc($histo["ref"]) ?></td>
                        <td><?= esc($histo["operation"]) ?></td>
                        <td><?= esc($histo["destinataire_numero"] ?? '-') ?></td>
                        <td><?= esc($histo["montant"]) ?></td>
                        <td><?= esc($histo["frais"]) ?></td>
                        <td><?= esc($histo["description"] ?? '-') ?></td>
                        <td><?= esc($histo["date_op"]) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <?= $pager->links() ?>
    </div>
</body>

</html>