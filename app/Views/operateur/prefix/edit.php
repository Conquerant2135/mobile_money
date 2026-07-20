<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le préfixe</title>
</head>

<body>
    <h1>Modifier le Préfixe #<?= $prefix['id'] ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= base_url('operateur/prefixes/update/' . $prefix['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <label for="prefix">Préfixe :</label>
        <input type="text" name="prefix" id="prefix" value="<?= old('prefix', $prefix['prefix']) ?>" required>

        <label for="actif">
            <input type="checkbox" name="actif" id="actif" value="1" <?= old('actif', $prefix['actif']) == 1 ? 'checked' : '' ?>> Actif
        </label>

        <button type="submit">Enregistrer les modifications</button>
        <a href="<?= base_url('operateur/prefixes') ?>">Annuler</a>
    </form>
</body>

</html>