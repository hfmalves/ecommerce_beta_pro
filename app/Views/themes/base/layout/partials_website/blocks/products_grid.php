<div class="mb-2 mb-xl-5 pt-1 pb-2"></div>
<?php
$products    = $block['products']    ?? [];
$blockConfig = $block['blockConfig'] ?? [];
$title       = $blockConfig['title'] ?? '';
?>

<section class="products-grid container">

    <?php if ($title): ?>
        <h2 class="section-title text-uppercase text-center mb-1 mb-md-3 pb-xl-2 mb-xl-4">
            <?= esc($title) ?>
        </h2>
    <?php endif; ?>

    <?php if (!empty($products)): ?>
        <div class="row">

            <?php foreach ($products as $product): ?>
                <?php $variationId = $product['variation']['id'] ?? null; ?>

                <div class="col-6 col-md-4 col-lg-3">
                    <div class="product-card mb-3 mb-md-4 mb-xxl-5">

                        <div class="pc__img-wrapper">
                            <a href="<?= base_url('product/' . $product['slug']) ?>">

                                <?php if (!empty($product['images'])): ?>
                                    <?php foreach ($product['images'] as $img): ?>
                                        <img
                                                loading="lazy"
                                                src="<?= base_url('uploads/product_images/' . esc($img['path'])) ?>"
                                                alt="<?= esc($product['name']) ?>"
                                                width="330"
                                                height="400"
                                                class="pc__img mb-2"
                                                onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                        />
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <img
                                            loading="lazy"
                                            src="https://placehold.co/330x400"
                                            alt="Imagem não disponível"
                                            width="330"
                                            height="400"
                                            class="pc__img mb-2"
                                    />
                                <?php endif; ?>

                            </a>

                            <form method="post" class="addtocart-form">
                                <input type="hidden" name="product_id" value="<?= esc($product['id']) ?>">
                                <input type="hidden" name="product_variation_id" value="<?= esc($variationId) ?>">

                                <input
                                        id="product-quantity-<?= $product['id'] ?>"
                                        type="number"
                                        name="quantity"
                                        value="1"
                                        min="1"
                                        class="qty-control__number text-center product-quantity"
                                >

                                <button
                                        type="button"
                                        class="js-add-cart pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium js-open-aside"
                                        data-aside="cartDrawer"
                                        data-product-id="<?= $product['id'] ?>"
                                        data-variation-id="<?= esc($variationId) ?>"
                                        data-quantity-input="#product-quantity-<?= $product['id'] ?>"
                                >
                                    Adicionar ao Carrinho
                                </button>
                            </form>
                        </div>

                        <div class="pc__info position-relative">
                            <p class="pc__category">
                                <?= esc($product['category']['name'] ?? '') ?>
                            </p>

                            <h6 class="pc__title">
                                <a href="<?= base_url('product/' . $product['slug']) ?>">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h6>

                            <div class="product-card__price d-flex">
                                <?php if (!empty($product['special_price'])): ?>
                                    <span class="money price price-old"><?= esc($product['base_price_tax']) ?> €</span>
                                    <span class="money price price-sale"><?= esc($product['special_price']) ?> €</span>
                                <?php else: ?>
                                    <span class="money price"><?= esc($product['base_price_tax']) ?> €</span>
                                <?php endif; ?>
                            </div>

                            <button
                                    type="button"
                                    class="btn btn-round-sm btn-hover-red d-block border-0 js-add-wishlist"
                                    data-product-id="<?= $product['id'] ?>"
                                    data-variation-id="<?= esc($variationId) ?>"
                            >
                                <svg width="14" height="14"><use href="#icon_heart"></use></svg>
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    <?php else: ?>
        <p class="text-muted text-center">Nenhum produto disponível neste momento.</p>
    <?php endif; ?>
</section>
