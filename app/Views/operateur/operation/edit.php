<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le type d'opération</title>
</head>

<body>
    <h1>Modifier l'opération #<?= $operation['id'] ?></h1>

    <?php if (session()->getFlashdata('errors')): ?>
        <ul style="color: red;">
            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                <li><?= esc($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="<?= base_url('operateur/operations/update/' . $operation['id']) ?>" method="post">
        <?= csrf_field() ?>
        
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= old('nom', $operation['nom']) ?>" required>

        <label for="code">Code :</label>
        <input type="text" name="code" id="code" value="<?= old('code', $operation['code']) ?>" required>

        <button type="submit">Enregistrer les modifications</button>
        <a href="<?= base_url('operateur/operations') ?>">Annuler</a>
    </form>
</body>

</html>