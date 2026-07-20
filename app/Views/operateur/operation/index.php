<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Types d'Opération</title>
</head>

<body>
    <h1>Gestion des Types d'Opération</h1>

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

    <!-- Formulaire d'ajout -->
    <h2>Ajouter un type d'opération</h2>
    <form action="<?= base_url('operateur/operations/create') ?>" method="post">
        <?= csrf_field() ?>
        
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" value="<?= old('nom') ?>" placeholder="ex: Dépôt" required>

        <label for="code">Code :</label>
        <input type="text" name="code" id="code" value="<?= old('code') ?>" placeholder="ex: DEP" required>

        <button type="submit">Ajouter</button>
    </form>

    <hr>

    <!-- Liste -->
    <h2>Liste des opérations</h2>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Code</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($operations) && is_array($operations)): ?>
                <?php foreach ($operations as $o): ?>
                    <tr>
                        <td><?= $o['id'] ?></td>
                        <td><?= esc($o['nom']) ?></td>
                        <td><strong><?= esc($o['code']) ?></strong></td>
                        <td>
                            <a href="<?= base_url('operateur/operations/edit/' . $o['id']) ?>">Modifier</a> |
                            <a href="<?= base_url('operateur/operations/delete/' . $o['id']) ?>" onclick="return confirm('Confirmer la suppression ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Aucune opération enregistrée.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>

</html>