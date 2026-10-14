<?php
$faq   = $block['faq'] ?? null;
$items = $block['items'] ?? [];
if (!$faq || empty($items)) return;
?>
<section class="container py-5 mw-930 lh-30">
    <h2 class="section-title text-uppercase fw-bold mb-5">
        <?= esc($faq['title']) ?>
    </h2>

    <div class="accordion" id="faq_accordion_<?= $block['id'] ?>">
        <?php foreach ($items as $i => $item): ?>
            <div class="accordion-item">
                <h5 class="accordion-header" id="heading_<?= $block['id'] ?>_<?= $i ?>">
                    <button class="accordion-button collapsed"
                            type="button"
                            data-bs-toggle="collapse"
                            data-bs-target="#collapse_<?= $block['id'] ?>_<?= $i ?>"
                            aria-expanded="false">
                        <?= esc($item['question']) ?>
                    </button>
                </h5>

                <div id="collapse_<?= $block['id'] ?>_<?= $i ?>"
                     class="accordion-collapse collapse"
                     data-bs-parent="#faq_accordion_<?= $block['id'] ?>">
                    <div class="accordion-body">
                        <?= esc($item['answer']) ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
