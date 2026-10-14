<?php
$products = $block['products'] ?? [];
$title    = $block['blockConfig']['title'] ?? '';
?>
<div class="mb-2 mb-xl-5 pt-1 pb-2"></div>
<section class="products-carousel container">

    <?php if ($title): ?>
        <h2 class="section-title text-uppercase fw-bold text-center mb-4 pb-xl-2 mb-xl-4">
            <?= esc($title) ?>
        </h2>
    <?php endif; ?>

    <?php if (empty($products)) return; ?>

    <div class="position-relative">
        <div class="swiper-container js-swiper-slider"
             data-settings='{
                "autoplay": { "delay": 5000 },
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "effect": "none",
                "loop": false,
                "scrollbar": {
                  "el": ".products-carousel__scrollbar",
                  "draggable": true
                },
                "navigation": {
                  "nextEl": ".products-carousel__next",
                  "prevEl": ".products-carousel__prev"
                },
                "breakpoints": {
                  "320": { "slidesPerView": 2, "slidesPerGroup": 2, "spaceBetween": 14 },
                  "768": { "slidesPerView": 3, "slidesPerGroup": 3, "spaceBetween": 24 },
                  "992": { "slidesPerView": 4, "slidesPerGroup": 1, "spaceBetween": 30 }
                }
             }'>

            <div class="swiper-wrapper">
                <?php foreach ($products as $product): ?>
                    <?php
                    $images=$product['images']??[];
                    $img1=$images[0]['path']??null;
                    $img2=$images[1]['path']??$img1;
                    ?>
                    <div class="swiper-slide product-card">
                        <div class="pc__img-wrapper">
                            <a href="<?= base_url('product/'.$product['slug']) ?>">
                                <img loading="lazy"
                                     src="<?= $img1?base_url('uploads/'.esc($img1)):'https://placehold.co/330x400' ?>"
                                     onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                     width="330" height="400"
                                     class="pc__img">
                                <?php if($img2): ?>
                                    <img loading="lazy"
                                         src="<?= $img2?base_url('uploads/'.esc($img2)):'https://placehold.co/330x400' ?>"
                                         onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                         width="330" height="400"
                                         class="pc__img pc__img-second">
                                <?php endif; ?>
                            </a>
                            <div class="anim_appear-bottom position-absolute bottom-0 start-0 w-100 d-none d-sm-flex align-items-center">
                                <button class="btn btn-primary flex-grow-1 fs-base ps-3 ps-xxl-4 pe-0 border-0 text-uppercase fw-medium">Adicionar</button>
                                <button class="btn btn-primary flex-grow-1 fs-base ps-0 pe-3 pe-xxl-4 border-0 text-uppercase fw-medium">Ver</button>
                            </div>
                            <button class="pc__btn-wl position-absolute bg-body rounded-circle border-0 text-primary">
                                <svg width="16" height="16"><use href="#icon_heart"/></svg>
                            </button>
                        </div>
                        <div class="pc__info position-relative">
<!--                            <p class="pc__category third-color">--><?php //= esc($product['category']??'JEAN') ?><!--</p>-->
                            <h6 class="pc__title">
                                <a href="<?= base_url('product/'.$product['slug']) ?>"><?= esc($product['name']) ?></a>
                            </h6>
                            <div class="product-card__price d-flex align-items-center">
                                <?php if(!empty($product['special_price'])): ?>
                                    <span class="money price-old"><?= esc($product['base_price_tax']) ?> €</span>
                                    <span class="money price"><?= esc($product['special_price']) ?> €</span>
                                <?php else: ?>
                                    <span class="money price"><?= esc($product['base_price_tax']) ?> €</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

        </div>

        <div class="products-carousel__prev position-absolute top-50 d-flex align-items-center justify-content-center">
            <svg width="25" height="25"><use href="#icon_prev_md"/></svg>
        </div>

        <div class="products-carousel__next position-absolute top-50 d-flex align-items-center justify-content-center">
            <svg width="25" height="25"><use href="#icon_next_md"/></svg>
        </div>

        <div class="pb-2 mb-2 mb-xl-4"></div>

        <div class="products-carousel__scrollbar swiper-scrollbar"></div>
    </div>
</section>
