<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>
<section class="shop-checkout container">
    <h2 class="page-title">Envio e Checkout</h2>
    <div class="checkout-steps">
        <a href="<?= base_url('cart') ?>" class="checkout-steps__item">
            <span class="checkout-steps__item-number">01</span>
            <span class="checkout-steps__item-title">
        <span>Saco de Compras</span>
        <em>Gerir a lista de produtos</em>
      </span>
        </a>
        <a href="<?= base_url('checkout') ?>" class="checkout-steps__item active">
            <span class="checkout-steps__item-number">02</span>
            <span class="checkout-steps__item-title">
        <span>Envio e Checkout</span>
        <em>Finalizar a encomenda</em>
      </span>
        </a>
        <a href="<?= base_url('order_complete') ?>" class="checkout-steps__item">
            <span class="checkout-steps__item-number">03</span>
            <span class="checkout-steps__item-title">
        <span>Confirmação</span>
        <em>Rever e submeter encomenda</em>
      </span>
        </a>
    </div>
    <form name="checkout-form" action="<?= base_url('order_complete') ?>" method="get">
        <div class="checkout-form">
            <!-- DADOS DE FATURAÇÃO -->
            <div class="billing-info__wrapper">
                <h4>DADOS DE FATURAÇÃO</h4>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" id="checkout_first_name" placeholder="Nome">
                            <label for="checkout_first_name">Nome</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" id="checkout_last_name" placeholder="Apelido">
                            <label for="checkout_last_name">Apelido</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" id="checkout_company_name" placeholder="Empresa (opcional)">
                            <label for="checkout_company_name">Empresa (opcional)</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="País / Região">
                            <label>País / Região *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="Morada">
                            <label>Morada *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="Cidade">
                            <label>Cidade *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="Código Postal">
                            <label>Código Postal *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="Distrito">
                            <label>Distrito *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="text" class="form-control" placeholder="Telefone">
                            <label>Telefone *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-floating my-3">
                            <input type="email" class="form-control" placeholder="Email">
                            <label>Email *</label>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" id="create_account">
                            <label class="form-check-label" for="create_account">
                                Criar conta?
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="ship_different_address">
                            <label class="form-check-label" for="ship_different_address">
                                Enviar para outra morada?
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mt-3">
                    <textarea class="form-control" rows="6" placeholder="Notas da encomenda (opcional)"></textarea>
                </div>
            </div>
            <!-- RESUMO -->
            <div class="checkout__totals-wrapper">
                <div class="sticky-content">
                    <div class="checkout__totals">
                        <h3>A sua encomenda</h3>
                        <table class="checkout-cart-items">
                            <thead>
                            <tr>
                                <th>PRODUTO</th>
                                <th>SUBTOTAL</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>Zessi Dresses × 2</td>
                                <td>€32,50</td>
                            </tr>
                            <tr>
                                <td>Kirby T-Shirt</td>
                                <td>€29,90</td>
                            </tr>
                            </tbody>
                        </table>
                        <table class="checkout-totals">
                            <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>€62,40</td>
                            </tr>
                            <tr>
                                <th>Envio</th>
                                <td>Envio grátis</td>
                            </tr>
                            <tr>
                                <th>IVA</th>
                                <td>€19</td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td>€81,40</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <!-- PAGAMENTO -->
                    <div class="checkout__payment-methods">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment" checked>
                            <label class="form-check-label">
                                Transferência bancária
                                <span class="option-detail d-block">
                  Use o ID da encomenda como referência.
                </span>
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment">
                            <label class="form-check-label">Pagamento à cobrança</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment">
                            <label class="form-check-label">PayPal</label>
                        </div>
                        <div class="policy-text">
                            Os seus dados pessoais serão utilizados para processar a encomenda,
                            conforme descrito na nossa
                            <a href="<?= base_url('terms') ?>" target="_blank">política de privacidade</a>.
                        </div>
                    </div>
                    <button class="btn btn-primary btn-checkout">
                        Confirmar Encomenda
                    </button>
                </div>
            </div>
        </div>
    </form>
</section>
<?= $this->endSection() ?>
