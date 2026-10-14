<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Loja') ?></title>
    <link rel="stylesheet" href="<?= base_url('themes/starter/css/main.css') ?>">
    <?= $this->renderSection('custom-css') ?>
</head>
<body>
    <?= $this->include('themes/starter/layout/partials_website/header') ?>
    <main><?= $this->renderSection('content') ?></main>
    <?= $this->include('themes/starter/layout/partials_website/blocks/footer') ?>
    <script src="<?= base_url('themes/starter/js/main.js') ?>" defer></script>
    <?= $this->renderSection('custom-js') ?>
</body>
</html>