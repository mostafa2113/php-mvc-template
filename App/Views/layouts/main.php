<!DOCTYPE html>
<html lang="<?= $_SESSION['lang'] ?? 'en' ?>" dir="<?= ($_SESSION['lang'] ?? 'en') === 'ar' ? 'rtl' : 'ltr' ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PHP MVC App' ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; padding: 20px; }
        header { background: #f0f0f0; padding: 20px; margin-bottom: 20px; }
        main { padding: 20px; }
        footer { margin-top: 20px; padding: 20px; background: #f0f0f0; }
    </style>
</head>
<body>
    <header>
        <h1>PHP MVC Framework</h1>
        <form method="post" action="<?= url('language/switch') ?>" style="position: absolute; top: 20px; right: 20px;">
            <button type="submit" name="lang" value="en">English</button>
            <button type="submit" name="lang" value="ar">العربية</button>
        </form>
    </header>
    
    <main>
        {{content}}
    </main>

    <footer>
        <p>&copy; <?= date('Y') ?> PHP MVC Application</p>
    </footer>
</body>
</html>