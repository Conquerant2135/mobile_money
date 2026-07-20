<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="<?= base_url("/styles.css") ?>">
    <title>Se connecter</title>
</head>
<body>
    <header>
        <nav>
            
        </nav>
    </header>
    
    <main>
        <form action="<?= base_url("/login") ?>" method="POST" class="contact-form">
            <div class="form-group">
                <label for="phone">Numero de telephone:</label>
                <input type="text" name="phone" id="phone" placeholder="0340011100" value="0340011100">
            </div>
            <button type="submit" class="submit-btn">Se connecter</button>
        </form>
    </main>
    
    <footer>
        
    </footer>
    
    <script src="script.js"></script>
</body>
</html>