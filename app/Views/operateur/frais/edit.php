<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Barème de Frais</title>
</head>

<body>
    <h1>Modifier le Barème #<?= $frais['id'] ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= base_url('operateur/frais/update/' . $frais['id']) ?>" method="post">
        <?= csrf_field() ?>

        <label for="operation_id">Opération :</label>
        <select name="operation_id" id="operation_id" required>
            <?php foreach ($operations as $op): ?>
                <option value="<?= $op['id'] ?>" <?= old('operation_id', $frais['operation_id']) == $op['id'] ? 'selected' : '' ?>>
                    <?= esc($op['nom']) ?> (<?= esc($op['code']) ?>)
                </option>
            <?php endforeach; ?>
        </select>

        <label for="min">Montant Min :</label>
        <input type="number" step="0.01" name="min" id="min" value="<?= old('min', $frais['min']) ?>" required>

        <label for="max">Montant Max :</label>
        <input type="number" step="0.01" name="max" id="max" value="<?= old('max', $frais['max']) ?>" required>

        <label for="frais_val">Frais (Ar) :</label>
        <input type="number" step="0.01" name="frais_val" id="frais_val" value="<?= old('frais_val', $frais['frais_val']) ?>" required>

        <button type="submit">Enregistrer</button>
        <a href="<?= base_url('operateur/frais') ?>">Annuler</a>
    </form>
</body>

</html>