<!-- HEADER -->
<div class="aside-header d-flex align-items-center">
    <h3 class="text-uppercase fs-6 mb-0">
        MEU CARRINHO (
        <span class="cart-amount js-cart-items-count">
                <?= esc($cartTotals['total_items'] ?? 0) ?>
            </span>
        )
    </h3>
    <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
</div>
<!-- ITEMS -->
<div class="aside-content cart-drawer-items-list">
    <div style="font-size:12px;color:#999;margin:10px">
        session_id: <?= esc(session_id()) ?>
    </div>
    <?php if (empty($cartItems)): ?>
        <p class="text-center text-muted mt-4">Carrinho vazio</p>
    <?php else: ?>
        <?php foreach ($cartItems as $row): ?>
            <?php
            $item    = $row['item'];
            $product = $row['product'];
            $variant = $row['variant'];
            ?>
            <div class="cart-drawer-item d-flex position-relative">
                <!-- IMAGE -->
                <div class="position-relative">
                    <a href="<?= base_url('product/' . $product['slug']) ?>">
                        <img
                                loading="lazy"
                                class="cart-drawer-item__img"
                                src="<?= !empty($product['image'])
                                    ? base_url('uploads/product_images/' . $product['image'])
                                    : 'https://placehold.co/80x100' ?>"
                                alt="<?= esc($product['name']) ?>">
                    </a>
                </div>
                <!-- INFO -->
                <div class="cart-drawer-item__info flex-grow-1">
                    <h6 class="cart-drawer-item__title fw-normal">
                        <a href="<?= base_url('product/' . $product['slug']) ?>">
                            <?= esc($product['name']) ?>
                        </a>
                    </h6>
                    <?php if ($variant): ?>
                        <?php foreach ($variant as $key => $val): ?>
                            <?php if (str_starts_with($key, 'attr_')): ?>
                                <p class="cart-drawer-item__option text-secondary">
                                    <?= esc(ucfirst(str_replace('attr_', '', $key))) ?>:
                                    <?= esc($val) ?>
                                </p>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    <div class="d-flex align-items-center justify-content-between mt-1">
                        <!-- QTY -->
                        <div class="qty-control position-relative">
                            <input
                                    type="number"
                                    value="<?= (int)$item['qty'] ?>"
                                    min="1"
                                    class="qty-control__number border-0 text-center"
                                    data-product="<?= $item['product_id'] ?>"
                                    data-variant="<?= $item['variant_id'] ?>">
                            <div class="qty-control__reduce">-</div>
                            <div class="qty-control__increase">+</div>
                        </div>
                        <!-- PRICE -->
                        <span class="cart-drawer-item__price money price">
                                €<?= number_format($item['price'], 2, ',', '.') ?>
                            </span>
                    </div>
                </div>
                <!-- REMOVE -->
                <button
                        type="button"
                        class="btn-close-xs position-absolute top-0 end-0 js-cart-item-remove"
                        data-product="<?= $item['product_id'] ?>"
                        data-variant="<?= $item['variant_id'] ?>">
                </button>
            </div>
            <hr class="cart-drawer-divider">
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<!-- FOOTER -->
<div class="cart-drawer-actions position-absolute start-0 bottom-0 w-100">
    <hr class="cart-drawer-divider">
    <div class="d-flex justify-content-between">
        <h6 class="fs-base fw-medium">SUBTOTAL:</h6>
        <span class="cart-subtotal fw-medium">
                €<?= number_format($cartTotals['total_value'] ?? 0, 2, ',', '.') ?>
            </span>
    </div>
    <a href="<?= base_url('cart') ?>" class="btn btn-light mt-3 d-block">
        Ver Carrinho
    </a>
    <a href="<?= base_url('checkout') ?>" class="btn btn-primary mt-3 d-block">
        Finalizar Compra
    </a>
</div>