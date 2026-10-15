<!doctype html>
<html lang="pt-PT">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pré-visualização do bloco</title>
    <link rel="stylesheet" href="<?= base_url('assets/website/css/plugins/swiper.min.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/website/css/style.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/website/css/custom.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/admin/showcase-block-preview.css') ?>">
</head>
<body class="showcase-preview showcase-preview--<?= esc($previewType, 'attr') ?>">
    <?= view('admin/website/showcases/preview_icons') ?>
    <div id="showcase-preview-content"><?= $previewHtml ?></div>
    <script src="<?= base_url('assets/website/js/plugins/bootstrap.bundle.min.js') ?>"></script>
    <script src="<?= base_url('assets/website/js/plugins/swiper.min.js') ?>"></script>
    <script src="<?= base_url('assets/js/admin/showcases/block-preview.js') ?>"></script>
</body>
</html>
