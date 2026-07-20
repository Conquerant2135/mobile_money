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