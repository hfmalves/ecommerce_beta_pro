<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="about-us__content pb-5 mb-5">
    <h1 class="page-title mb-4">
        <?= esc($page['title']) ?>
    </h1>
    <div class="page-body">
        <?= $page['content'] ?>
    </div>
</DIV>
<?= $this->endSection() ?>
