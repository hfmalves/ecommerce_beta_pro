<?php
$config = $block['blockConfig'] ?? [];
$slidesDesktop = (int)($config['slides_desktop'] ?? 3);
$slidesTablet  = (int)($config['slides_tablet'] ?? 2);
$slidesMobile  = (int)($config['slides_mobile'] ?? 1);
$autoplayDelay = (int)($config['autoplay_delay'] ?? 5000);
$loop          = !empty($config['loop']) ? 'true' : 'false';
?>
<div class="mb-2 mb-xl-5 pt-2 pb-2"></div>
<section class="blog-carousel container">
    <h2 class="section-title fw-normal text-center mb-3 pb-xl-3 mb-xl-3">
        <?= esc($config['section_title'] ?? 'Our Blog') ?>
    </h2>

    <div class="position-relative">
        <div class="swiper-container js-swiper-slider"
             data-settings='{
                "autoplay": { "delay": <?= $autoplayDelay ?> },
                "slidesPerView": <?= $slidesDesktop ?>,
                "slidesPerGroup": <?= $slidesDesktop ?>,
                "loop": <?= $loop ?>,
                "pagination": {
                  "el": ".blog-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "breakpoints": {
                  "320": {
                    "slidesPerView": <?= $slidesMobile ?>,
                    "slidesPerGroup": <?= $slidesMobile ?>,
                    "spaceBetween": 14
                  },
                  "768": {
                    "slidesPerView": <?= $slidesTablet ?>,
                    "slidesPerGroup": <?= $slidesTablet ?>,
                    "spaceBetween": 24
                  },
                  "992": {
                    "slidesPerView": <?= $slidesDesktop ?>,
                    "slidesPerGroup": <?= $slidesDesktop ?>,
                    "spaceBetween": 30
                  }
                }
             }'>

            <div class="swiper-wrapper blog-grid row-cols-xl-3">

                <?php foreach ($block['items'] as $item): ?>
                    <div class="swiper-slide blog-grid__item mb-4">
                        <div class="blog-grid__item-image">
                            <img loading="lazy"
                                 src="<?= base_url('uploads/' . esc($item['image'])) ?>"
                                 onerror="this.onerror=null;this.src='https://placehold.co/450x300';"
                                 width="450" height="300">
                        </div>

                        <div class="blog-grid__item-detail">
                            <div class="blog-grid__item-meta">
                                <span class="blog-grid__item-meta__author">
                                    By <?= esc($item['author']) ?>
                                </span>
                                <span class="blog-grid__item-meta__date">
                                    <?= date('F d, Y', strtotime($item['published_at'])) ?>
                                </span>
                            </div>

                            <div class="blog-grid__item-title mb-0">
                                <a href="<?= esc($item['link']) ?>">
                                    <?= esc($item['title']) ?>
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

            </div>
        </div>

        <div class="blog-pagination type2 mt-4 d-flex align-items-center justify-content-center"></div>
    </div>
</section>
