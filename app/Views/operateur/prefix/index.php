<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Préfixes Valables</title>
</head>

<body>
    <h1>Gestion des Préfixes Valables</h1>

    <!-- Messages de notification -->
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
    <h2>Ajouter un nouveau préfixe</h2>
    <form action="<?= base_url('operateur/prefixes/create') ?>" method="post">
        <?= csrf_field() ?>
        <label for="prefix">Préfixe :</label>
        <input type="text" name="prefix" id="prefix" value="<?= old('prefix') ?>" placeholder="ex: 034" required>

        <label for="actif">
            <input type="checkbox" name="actif" id="actif" value="1" checked> Actif
        </label>

        <button type="submit">Ajouter</button>
    </form>

    <hr>

    <!-- Liste des préfixes -->
    <h2>Liste des préfixes</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Préfixe</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($prefixes) && is_array($prefixes)): ?>
                <?php foreach ($prefixes as $p): ?>
                    <tr>
                        <td><?= $p['id'] ?></td>
                        <td><?= esc($p['prefix']) ?></td>
                        <td><?= $p['actif'] == 1 ? 'Actif' : 'Inactif' ?></td>
                        <td>
                            <a href="<?= base_url('operateur/prefixes/edit/' . $p['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('operateur/prefixes/delete/' . $p['id']) ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucun préfixe enregistré.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>