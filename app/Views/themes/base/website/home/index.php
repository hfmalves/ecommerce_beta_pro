<?= $this->extend('themes/base/layout/main_website') ?>

<?= $this->section('content') ?>

<?php foreach ($blocks as $block): ?>
    <?= view('themes/base/layout/partials_website/blocks/' . $block['block_type'], [
        'block' => $block
    ]) ?>
<?php endforeach; ?>

<?= $this->endSection() ?>
