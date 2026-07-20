<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Frais par Opération</title>
</head>

<body>
    <h1>Gestion des Barèmes de Frais</h1>

    <!-- Notifications -->
    <?php if (session()->getFlashdata('success')): ?>
        <p style="color: green;"><?= session()->getFlashdata('success') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <p style="color: red;"><?= session()->getFlashdata('error') ?></p>
    <?php endif; ?>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <!-- Formulaire de création -->
    <h2>Ajouter un barème de frais</h2>
    <form action="<?= base_url('operateur/frais/create') ?>" method="post">
        <?= csrf_field() ?>

        <label for="operation_id">Opération :</label>
        <select name="operation_id" id="operation_id" required>
            <option value="">-- Sélectionner une opération --</option>
            <?php foreach ($operations as $op): ?>
                <option value="<?= $op['id'] ?>" <?= old('operation_id') == $op['id'] ? 'selected' : '' ?>>
                    <?= esc($op['nom']) ?> (<?= esc($op['code']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="min">Montant Min :</label>
        <input type="number" step="0.01" name="min" id="min" value="<?= old('min', '0') ?>" required>

        <label for="max">Montant Max :</label>
        <input type="number" step="0.01" name="max" id="max" value="<?= old('max') ?>" required>

        <label for="frais_val">Frais (Ar) :</label>
        <input type="number" step="0.01" name="frais_val" id="frais_val" value="<?= old('frais_val') ?>" required>

        <button type="submit">Ajouter</button>
    </form>

    <hr>

    <!-- Liste des frais -->
    <h2>Liste des barèmes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Opération</th>
                <th>Min (Ar)</th>
                <th>Max (Ar)</th>
                <th>Frais (Ar)</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($frais_list) && is_array($frais_list)): ?>
                <?php foreach ($frais_list as $f): ?>
                    <tr>
                        <td><?= $f['id'] ?></td>
                        <td><?= esc($f['operation_nom']) ?> (<strong><?= esc($f['operation_code']) ?></strong>)</td>
                        <td><?= number_format($f['min'], 2, ',', ' ') ?></td>
                        <td><?= number_format($f['max'], 2, ',', ' ') ?></td>
                        <td><?= number_format($f['frais_val'], 2, ',', ' ') ?></td>
                        <td>
                            <a href="<?= base_url('operateur/frais/edit/' . $f['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('operateur/frais/delete/' . $f['id']) ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6">Aucun barème trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>