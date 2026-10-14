<?php
$items = $block['data']['items'] ?? [];

if (empty($items)) {
    return;
}
?>
<div class="mb-2 mb-xl-5 pt-1 pb-2"></div>
<section class="grid-banner container mb-3 mt-3" id="section-grid-banner">
    <div class="row">

        <?php foreach ($items as $item): ?>
            <?php
            $img = $item['image'] ?? '';
            $src = base_url('uploads/blocks/large/' . $img);
            ?>
            <div class="col-lg-4">
                <div class="grid-banner__item position-relative mb-3">

                    <img
                            loading="lazy"
                            class="w-100 h-auto"
                            src="<?= $src ?>"
                            onerror="this.onerror=null;this.src='https://placehold.co/450x450';"
                            width="450"
                            height="450"
                            alt="<?= esc($item['title'] ?? '') ?>"
                    >

                    <div class="content_abs content_center text-center <?= ($item['text_color'] ?? '') === 'light' ? 'text-white' : '' ?>">
                        <?php if (!empty($item['subtitle'])): ?>
                            <p class="mb-1"><?= esc($item['subtitle']) ?></p>
                        <?php endif; ?>

                        <?php if (!empty($item['title'])): ?>
                            <h3 class="text-uppercase fw-bold mb-1">
                                <?= esc($item['title']) ?>
                            </h3>
                        <?php endif; ?>

                        <?php if (!empty($item['link'])): ?>
                            <a
                                    href="<?= base_url($item['link']) ?>"
                                    class="btn-link default-underline text-uppercase fw-medium <?= ($item['text_color'] ?? '') === 'light' ? 'text-white' : '' ?>"
                            >
                                Ver mais
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>

