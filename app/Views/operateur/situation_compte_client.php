<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Situation des comptes</title>
</head>

<body>
    <h1>Solde des comptes</h1>
    <table border="1">
        <thead>
            <tr>
                <th>Client</th>
                <th>Solde</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($clientSoldes) && is_array($clientSoldes)): ?>
                <?php foreach ($clientSoldes as $client): ?>
                    <tr>
                        <td><?= esc($client['nom']) . ' ' . esc($client['prenom']) ?></td>
                        <td><?= number_format($client['solde'], 2, ',', ' ') ?> Ar</td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="2">Aucun client trouvé.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

</body>

</html>