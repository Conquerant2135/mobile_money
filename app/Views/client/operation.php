<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="<?= base_url("/styles.css") ?>">
    <title>Faire une operation</title>
</head>

<body>
    <header>
        <nav>

        </nav>
    </header>

    <main>
        <?php if (session()->getFlashdata('error')) { ?>
            <div class="alert alert-error">
                <?= esc(session()->getFlashdata('error')) ?>
            </div>
        <?php } ?>
        <?php if (session()->getFlashdata('success')) { ?>
            <div class="alert alert-success">
                <?= esc(session()->getFlashdata('success')) ?>
            </div>
        <?php } ?>
        <form action="<?= base_url("/client/operation") ?>" method="POST" class="contact-form">
            <div class="form-group">
                <label for="montant">Montant:</label>
                <input type="number" id="montant" name="montant">
            </div>

            <div class="form-group">
                <label for="operation">Operation :</label>
                <select name="operation" id="operation">
                    <?php foreach ($operations as $op) { ?>
                        <option value="<?= esc($op["id"]) ?>"><?= esc($op["nom"]) ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group" id="desc-field" style="display:none;">
                <label for="desc">Description :</label>
                <textarea name="desc" id="desc"></textarea>
            </div>
            <div class="form-group" id="phone-field" style="display:none;">
                <label for="phone">Telephone :</label>
                <input type="text" name="phone" id="phone">
            </div>


            <button type="submit" class="submit-btn" id="submit-stuff">Depot</button>
        </form>
    </main>

    <footer>

    </footer>

    <script src="<?= base_url("/script/client/form.js") ?>"></script>
</body>

</html>