<div class="cart-table__wrapper">
    <table class="cart-table">
        <thead>
        <tr>
            <th>Produto</th>
            <th></th>
            <th>Preço</th>
            <th>Quantidade</th>
            <th>Subtotal</th>
            <th></th>
        </tr>
        </thead>

        <tbody>
        <?php if (empty($cartItems)): ?>
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    Carrinho vazio
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($cartItems as $row): ?>
                <?php
                $item    = $row['item'];
                $product = $row['product'];
                $variant = $row['variant'];
                ?>
                <tr>
                    <!-- IMAGEM -->
                    <td>
                        <div class="shopping-cart__product-item">
                            <a href="<?= base_url('product/' . $product['slug']) ?>">
                                <img loading="lazy" src="<?= !empty($product['image']) ? base_url('uploads/product_images/' . $product['image']) : 'https://placehold.co/120x120' ?>" width="120" height="120" alt="<?= esc($product['name']) ?>">
                            </a>
                        </div>
                    </td>

                    <!-- NOME / VARIANTES -->
                    <td>
                        <div class="shopping-cart__product-item__detail">
                            <h4>
                                <a href="<?= base_url('product/' . $product['slug']) ?>"><?= esc($product['name']) ?></a>
                            </h4>
                            <?php if ($variant): ?>
                                <ul class="shopping-cart__product-item__options">
                                    <?php foreach ($variant as $key => $val): ?>
                                        <?php if (str_starts_with($key, 'attr_')): ?>
                                            <li><?= esc(ucfirst(str_replace('attr_', '', $key))) ?>: <?= esc($val) ?></li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </div>
                    </td>

                    <!-- PREÇO -->
                    <td>
                        <span class="shopping-cart__product-price">€<?= number_format($item['price'], 2, ',', '.') ?></span>
                    </td>

                    <!-- QUANTIDADE -->
                    <td>
                        <div class="qty-control position-relative">
                            <input type="number" value="<?= (int)$item['qty'] ?>" min="1" class="qty-control__number text-center" data-product="<?= $item['product_id'] ?>" data-variant="<?= $item['variant_id'] ?>">
                            <div class="qty-control__reduce">-</div>
                            <div class="qty-control__increase">+</div>
                        </div>
                    </td>

                    <!-- SUBTOTAL -->
                    <td>
                        <span class="shopping-cart__subtotal">€<?= number_format($item['price'] * $item['qty'], 2, ',', '.') ?></span>
                    </td>

                    <!-- REMOVER -->
                    <td>
                        <button class="btn-close-xs position-absolute js-cart-item-remove" data-product="<?= $item['product_id'] ?>" data-variant="<?= $item['variant_id'] ?>"></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>

    <div class="cart-table-footer mb-5">
        <!-- CÓDIGO DE DESCONTO -->
        <form class="position-relative bg-body">
            <input class="form-control" type="text" placeholder="Código de desconto">
            <input class="btn-link fw-medium position-absolute top-0 end-0 h-100 px-4" type="submit" value="APLICAR">
        </form>
        <button class="btn btn-light">Atualizar Carrinho</button>
    </div>
</div>

<!-- TOTAL -->
<!-- TOTAL -->
<div class="shopping-cart__totals-wrapper">
    <div class="sticky-content">
        <div class="shopping-cart__totals">
            <h3>Totais do Carrinho</h3>

            <table class="cart-totals">
                <tbody>
                <tr>
                    <th>Envio</th>
                    <td>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="free_shipping">
                            <label for="free_shipping">Envio grátis</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="flat_rate">
                            <label for="flat_rate">Taxa fixa: €49</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="local_pickup">
                            <label for="local_pickup">Levantamento local: €8</label>
                        </div>
                        <div>Envio para Portugal</div>
                        <a href="#" class="menu-link menu-link_us-s">ALTERAR MORADA</a>
                    </td>
                </tr>
                <tr>
                    <th>Subtotal</th>
                    <?php
                    // Cálculo do subtotal (sem IVA)
                    $subtotal = $cartTotals['total_value'] / 1.23;
                    ?>
                    <td>€<?= number_format($subtotal, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>IVA (23%)</th>
                    <?php
                    // Cálculo do IVA
                    $iva = $subtotal * 0.23;
                    ?>
                    <td>€<?= number_format($iva, 2, ',', '.') ?></td>
                </tr>
                <tr>
                    <th>Total</th>
                    <td>€<?= number_format($cartTotals['total_value'], 2, ',', '.') ?></td>
                </tr>
                </tbody>
            </table>
        </div>

        <div class="mobile_fixed-btn_wrapper">
            <div class="button-wrapper container">
                <a href="<?= base_url('checkout') ?>" class="btn btn-primary btn-checkout">
                    Avançar para Checkout
                </a>
            </div>
        </div>
    </div>
</div>

