<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>

<section class="shop-checkout container">
  <h2 class="page-title">Encomenda Recebida</h2>
  <div class="checkout-steps">
    <a href="<?= base_url('cart') ?>" class="checkout-steps__item active">
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
    <a href="<?= base_url('order_complete') ?>" class="checkout-steps__item active">
      <span class="checkout-steps__item-number">03</span>
      <span class="checkout-steps__item-title">
        <span>Confirmação</span>
        <em>Rever encomenda</em>
      </span>
    </a>
  </div>
  <div class="order-complete">
    <div class="order-complete__message text-center">
      <svg width="80" height="80" viewBox="0 0 80 80">
        <circle cx="40" cy="40" r="40" fill="#B9A16B"/>
        <path d="M27 41l10 10 18-18" stroke="#fff" stroke-width="5" fill="none"/>
      </svg>
      <h3>Encomenda concluída com sucesso!</h3>
      <p>Obrigado. A sua encomenda foi recebida.</p>
    </div>
    <div class="order-info">
      <div class="order-info__item">
        <label>Nº Encomenda</label>
        <span><?= esc($order_number ?? '13119') ?></span>
      </div>
      <div class="order-info__item">
        <label>Data</label>
        <span><?= esc($order_date ?? date('d/m/Y')) ?></span>
      </div>
      <div class="order-info__item">
        <label>Total</label>
        <span><?= esc($order_total ?? '€81,40') ?></span>
      </div>
      <div class="order-info__item">
        <label>Método de Pagamento</label>
        <span><?= esc($payment_method ?? 'Transferência Bancária') ?></span>
      </div>
    </div>
    <div class="checkout__totals-wrapper">
      <div class="checkout__totals">
        <h3>Detalhes da Encomenda</h3>
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
              <td>€19,00</td>
            </tr>
            <tr>
              <th>Total</th>
              <td>€81,40</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<?= $this->endSection() ?>
