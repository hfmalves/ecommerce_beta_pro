<?php
$items  = $block['items'] ?? [];
$layout = $block['blockConfig']['layout'] ?? 'horizontal';
?>

<div class="service-promotion <?= esc($layout) ?> container">
    <div class="row">
        <?php foreach ($items as $item): ?>
            <div class="col-md-4 mb-5 mb-md-0 d-flex align-items-center justify-content-center gap-3">
                <div class="service-promotion__icon">
                    <svg width="52" height="52">
                        <use href="#<?= esc($item['icon']) ?>"></use>
                    </svg>
                </div>

                <div class="service-promotion__content-wrap">
                    <h3 class="service-promotion__title h6 text-uppercase mb-1">
                        <?= esc($item['title']) ?>
                    </h3>
                    <p class="service-promotion__content text-secondary mb-0">
                        <?= esc($item['subtitle']) ?>
                    </p>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
