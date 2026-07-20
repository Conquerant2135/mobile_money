<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter — Mobile Money</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">
</head>
<body>
    <div class="auth-page">
        <div class="auth-card">
            <div class="brand">
                <i class="bi bi-wallet2"></i>
                <span>Mobile Money</span>
            </div>

            <h2 class="h5 mb-1">Connexion</h2>
            <p class="text-secondary mb-4" style="font-size: var(--font-size-sm);">
                Entrez votre numéro de téléphone pour accéder à votre compte.
            </p>

            <?php if (session()->getFlashdata('error')) : ?>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    <div><?= esc(session()->getFlashdata('error')) ?></div>
                </div>
            <?php endif; ?>

            <form action="<?= base_url('login') ?>" method="POST">
                <?= function_exists('csrf_field') ? csrf_field() : '' ?>

                <div class="mb-3">
                    <label for="phone" class="form-label">Numéro de téléphone</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-telephone"></i></span>
                        <input type="text" class="form-control" name="phone" id="phone"
                               placeholder="0340011100" value="<?= old('phone', '0340011100') ?>">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-2">
                    Se connecter
                </button>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>