<?= $this->extend('themes/base/layout/main_website') ?>

<?= $this->section('content') ?>
<section class="product-single container">
    <?php
    /** @var array $product */
    /** @var array $images */
    /** @var array $variants */
    /** @var array $attributes */
    ?>
    <div class="mb-md-1 pb-md-5"></div>
    <div class="row">
        <?php
        $grouped = [];
        foreach ($attributes as $variantId => $attrs) {
            foreach ($attrs as $code => $values) {
                $grouped[$code] = array_unique(
                        array_merge($grouped[$code] ?? [], $values)
                );
            }
        }

        $type = $product['type'] ?? 'simple';

        $isOutOfStock = (
                ($product['manage_stock'] ?? 0) == 1 &&
                ($product['stock_qty'] ?? 0) == 0
        );
        ?>
        <!-- GALERIA -->
        <div class="col-lg-7">
            <div class="product-single__media vertical-thumbnail product-media-initialized">

                <!-- THUMBNAILS -->
                <div class="product-single__thumbnail">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">

                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $img): ?>
                                    <div class="swiper-slide product-single__image-item">
                                        <img
                                                src="<?= !empty($img['path'])
                                                        ? base_url('uploads/product_images/' . esc($img['path']))
                                                        : 'https://placehold.co/104x104'; ?>"
                                                width="104"
                                                height="104"
                                                onerror="this.onerror=null;this.src='https://placehold.co/104x104';"
                                        >
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- PLACEHOLDER THUMB -->
                                <div class="swiper-slide product-single__image-item">
                                    <img
                                            src="https://placehold.co/104x104"
                                            width="104"
                                            height="104"
                                    >
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

                <!-- IMAGEM PRINCIPAL -->
                <div class="product-single__image">
                    <div class="swiper-container">
                        <div class="swiper-wrapper">

                            <?php if (!empty($images)): ?>
                                <?php foreach ($images as $img): ?>
                                    <div class="swiper-slide product-single__image-item">
                                        <img
                                                loading="lazy"
                                                class="h-auto"
                                                src="<?= !empty($img['path'])
                                                        ? base_url('uploads/product_images/' . esc($img['path']))
                                                        : 'https://placehold.co/674x674'; ?>"
                                                width="674"
                                                height="674"
                                                alt="<?= esc($product['name']) ?>"
                                                onerror="this.onerror=null;this.src='https://placehold.co/674x674';"
                                        >
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- PLACEHOLDER MAIN -->
                                <div class="swiper-slide product-single__image-item">
                                    <img
                                            loading="lazy"
                                            class="h-auto"
                                            src="https://placehold.co/674x674"
                                            width="674"
                                            height="674"
                                            alt="Imagem não disponível"
                                    >
                                </div>
                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div
                class="col-lg-5"
                x-data="cartApp"
                data-product-type="<?= esc($type) ?>"
                data-product-id="<?= (int)$product['id'] ?>"
        >
            <div class="d-flex justify-content-between mb-4 pb-md-2">
                <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                    <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">Home</a>
                    <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                    <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium">The Shop</a>
                </div>
                <div class="product-single__prev-next d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                </div>
            </div>
            <h1 class="product-single__name">
                <?= esc($product['name']) ?>
            </h1>
            <div class="product-single__price">
                <span class="old-price d-none"></span>
                <span class="current-price"></span>
                <span class="d-none"
                      data-price-base="<?= (float)$product['base_price_tax'] ?>"
                      data-price-special="<?= (float)$product['special_price'] ?>">
                </span>
            </div>

            <div class="product-single__short-desc">
                <?= esc($product['short_description']) ?>
                <?= esc($product['description']) ?>
            </div>

            <form name="addtocart-form" method="post">
                <?php if ($type === 'configurable'): ?>
                    <div class="product-single__swatches">
                        <?php foreach ($attributesMeta as $attr): ?>
                            <?php
                            $code = $attr['code'];
                            if (empty($grouped[$code])) continue;
                            $first = true;
                            ?>

                            <div class="product-swatch <?= ($attr['type'] === 'color') ? 'color-swatches' : 'text-swatches' ?>">
                                <label><?= esc($attr['name']) ?></label>

                                <div class="swatch-list">
                                    <?php foreach ($grouped[$code] as $value): ?>
                                        <input type="radio"
                                               name="<?= esc($code) ?>"
                                               id="<?= $code ?>-<?= md5($value) ?>"
                                                <?= $first ? 'checked' : '' ?>>

                                        <label class="swatch js-swatch"
                                               for="<?= $code ?>-<?= md5($value) ?>"
                                               data-attr="<?= esc($code) ?>"
                                               data-value="<?= esc($value) ?>">
                                            <?= esc($value) ?>
                                        </label>
                                        <?php $first = false; endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div id="variants-map" class="d-none">
                        <?php foreach ($variants as $variant): ?>
                            <div class="variant"
                                 data-id="<?= $variant['id'] ?>"
                                 data-base="<?= $variant['base_price_tax'] ?>"
                                 data-special="<?= $variant['special_price'] ?>"
                                    <?php foreach ($attributes[$variant['id']] as $code => $vals): ?>
                                        data-<?= esc($code) ?>="<?= esc($vals[0]) ?>"
                                    <?php endforeach; ?>
                            ></div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?php if ($type === 'virtual' && $virtual): ?>
                    <div class="product-virtual-info mb-3">
                        <p class="mb-1">Tipo: <?= esc($virtual['virtual_type']) ?></p>
                        <p class="mb-1">Validade: <?= (int)$virtual['virtual_expiry_days'] ?> dias</p>
                        <?php if (!empty($virtual['virtual_url'])): ?>
                            <small class="text-muted">Download após pagamento</small>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
                <?php if ($type === 'pack' && !empty($packItems)): ?>
                    <div class="product-single__addtocart product-single__grouped">
                        <?php foreach ($packItems as $item): ?>
                            <div class="grouped-item">
                                <div class="grouped-item__name"><?= esc($item['product_sku']) ?></div>
                                <div class="grouped-item__price">
                                    €<?= number_format($item['base_price'], 2, ',', '.') ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <div class="product-single__addtocart">
                    <div class="qty-control position-relative qty-initialized">
                        <input type="number" name="quantity" value="1" min="1" class="qty-control__number text-center">
                        <div class="qty-control__reduce">-</div>
                        <div class="qty-control__increase">+</div>
                    </div>
                    <button
                            type="button"
                            class="btn btn-primary btn-addtocart js-open-aside"
                            data-aside="cartDrawer"
                            @click="addToCart(document.querySelector('[name=quantity]').value)"
                    >
                        Comprar
                    </button>
                </div>
            </form>
            <!-- META -->
            <div class="product-single__meta-info mt-4">
                <div><strong>SKU:</strong> <?= esc($product['sku'] ?? 'N/A') ?></div>
            </div>
            <div class="product-single__addtolinks">
                <a href="#" class="menu-link menu-link_us-s add-to-wishlist"><svg width="16" height="16" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg><span>Adicionar Favoritos</span></a>
                <share-button class="share-button">
                    <button class="menu-link menu-link_us-s to-share border-0 bg-transparent d-flex align-items-center">
                        <svg width="16" height="19" viewBox="0 0 16 19" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_sharing" /></svg>
                        <span>Partilhar</span>
                    </button>
                    <details id="Details-share-template__main" class="m-1 xl:m-1.5" hidden="">
                        <summary class="btn-solid m-1 xl:m-1.5 pt-3.5 pb-3 px-5">+</summary>
                        <div id="Article-share-template__main" class="share-button__fallback flex items-center absolute top-full left-0 w-full px-2 py-4 bg-container shadow-theme border-t z-10">
                            <div class="field grow mr-4">
                                <label class="field__label sr-only" for="url">Link</label>
                                <input type="text" class="field__input w-full" id="url" value="https://uomo-crystal.myshopify.com/blogs/news/go-to-wellness-tips-for-mental-health" placeholder="Link" onclick="this.select();" readonly="">
                            </div>
                            <button class="share-button__copy no-js-hidden">
                                <svg class="icon icon-clipboard inline-block mr-1" width="11" height="13" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" focusable="false" viewBox="0 0 11 13">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M2 1a1 1 0 011-1h7a1 1 0 011 1v9a1 1 0 01-1 1V1H2zM1 2a1 1 0 00-1 1v9a1 1 0 001 1h7a1 1 0 001-1V3a1 1 0 00-1-1H1zm0 10V3h7v9H1z" fill="currentColor"></path>
                                </svg>
                                <span class="sr-only">Copy link</span>
                            </button>
                        </div>
                    </details>
                </share-button>
            </div>
        </div>
    </div>
    <div class="product-single__details-tab">
        <ul class="nav nav-tabs" id="myTab1" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore active" id="tab-description-tab" data-bs-toggle="tab" href="#tab-description" role="tab" aria-controls="tab-description" aria-selected="true">Descrição</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link nav-link_underscore" id="tab-additional-info-tab" data-bs-toggle="tab" href="#tab-additional-info" role="tab" aria-controls="tab-additional-info" aria-selected="false">Informação Adicional</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-description" role="tabpanel" aria-labelledby="tab-description-tab">
                <div class="product-single__description">

                    <?php if (!empty($product['short_description'])): ?>
                        <h3 class="block-title mb-4">
                            <?= esc($product['short_description']) ?>
                        </h3>
                    <?php endif; ?>

                    <?php if (!empty($product['description'])): ?>
                        <p class="content">
                            <?= $product['description'] ?>
                        </p>
                    <?php endif; ?>

                </div>
            </div>
            <div class="tab-pane fade" id="tab-additional-info" role="tabpanel" aria-labelledby="tab-additional-info-tab">
                <div class="product-single__addtional-info">
                    <?php if (!empty($product['weight'])): ?>
                        <div class="item">
                            <label class="h6">Peso</label>
                            <span><?= esc($product['weight']) ?> kg</span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($product['width']) || !empty($product['height']) || !empty($product['length'])): ?>
                        <div class="item">
                            <label class="h6">Dimenções</label>
                            <span>
                            <?= esc($product['width'] ?? '-') ?>
                            x <?= esc($product['height'] ?? '-') ?>
                            x <?= esc($product['length'] ?? '-') ?> cm
                        </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="products-carousel container">
    <h2 class="h3 text-uppercase mb-4 pb-xl-2 mb-xl-4">
        Related <strong>Products</strong>
    </h2>
    <div id="related_products" class="position-relative">
        <div class="swiper-container js-swiper-slider"
             data-settings='{
                "autoplay": false,
                "slidesPerView": 4,
                "slidesPerGroup": 4,
                "effect": "none",
                "loop": true,
                "pagination": {
                  "el": "#related_products .products-pagination",
                  "type": "bullets",
                  "clickable": true
                },
                "navigation": {
                  "nextEl": "#related_products .products-carousel__next",
                  "prevEl": "#related_products .products-carousel__prev"
                },
                "breakpoints": {
                  "320": { "slidesPerView": 2, "slidesPerGroup": 2, "spaceBetween": 14 },
                  "768": { "slidesPerView": 3, "slidesPerGroup": 3, "spaceBetween": 24 },
                  "992": { "slidesPerView": 4, "slidesPerGroup": 4, "spaceBetween": 30 }
                }
             }'>

            <div class="swiper-wrapper">

                <?php foreach ($relatedProducts as $p): ?>
                    <div class="swiper-slide product-card">
                        <div class="pc__img-wrapper">
                            <a href="<?= base_url('product/'.$p['slug']) ?>">
                                <img loading="lazy"
                                     src="<?= !empty($p['images'][0]['path'])
                                         ? base_url('uploads/product_images/'.$p['images'][0]['path'])
                                         : 'https://placehold.co/330x400' ?>"
                                     onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                     width="330" height="400"
                                     class="pc__img">

                                <img loading="lazy"
                                     src="<?= !empty($p['images'][1]['path'])
                                         ? base_url('uploads/product_images/'.$p['images'][1]['path'])
                                         : (!empty($p['images'][0]['path'])
                                             ? base_url('uploads/product_images/'.$p['images'][0]['path'])
                                             : 'https://placehold.co/330x400') ?>"
                                     onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                     width="330" height="400"
                                     class="pc__img pc__img-second">
                            </a>

                            <button
                                    type="button"
                                    class="btn btn-primary btn-addtocart"
                                    @click="addSimple(<?= (int)$product['id'] ?>, document.querySelector('[name=quantity]').value)"
                            >
                                Add to Cart 1
                            </button>


                        </div>

                        <div class="pc__info position-relative">
                            <h6 class="pc__title">
                                <a href="<?= base_url('product/'.$p['slug']) ?>">
                                    <?= esc($p['name']) ?>
                                </a>
                            </h6>

                            <div class="product-card__price d-flex">
                                <?php if (!empty($p['special_price'])): ?>
                                    <span class="money price price-old"><?= esc($p['base_price_tax']) ?> €</span>
                                    <span class="money price price-sale"><?= esc($p['special_price']) ?> €</span>
                                <?php else: ?>
                                    <span class="money price"><?= esc($p['base_price_tax']) ?> €</span>
                                <?php endif; ?>
                            </div>

                            <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0">
                                <svg width="16" height="16">
                                    <use href="#icon_heart"/>
                                </svg>
                            </button>
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

        <div class="products-pagination mt-4 mb-5 d-flex align-items-center justify-content-center"></div>
    </div>
</section>

<?= $this->endSection() ?>
