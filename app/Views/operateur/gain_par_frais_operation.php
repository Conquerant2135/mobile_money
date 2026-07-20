<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gains par opération</title>
</head>

<body>
    <h1>Gains générés par les frais d'opération</h1>

    <table border="1">
        <thead>
            <tr>
                <th>Opération</th>
                <th>Gain par frais</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Retrait</td>
                <td><?= number_format($gainRetait, 2, ',', ' ') ?> Ar</td>
            </tr>
            <tr>
                <td>Transfert</td>
                <td><?= number_format($gainTransfert, 2, ',', ' ') ?> Ar</td>
            </tr>
        </tbody>
        <tfoot>
            <tr>
                <th>Total des gains</th>
                <th><?= number_format($gainRetait + $gainTransfert, 2, ',', ' ') ?> Ar</th>
            </tr>
        </tfoot>
    </table>
</body>

</html>