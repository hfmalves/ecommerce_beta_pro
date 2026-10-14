<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>

<section class="my-account container">
    <h2 class="page-title">Lista de Desejos</h2>

    <div class="row">
        <div class="col-lg-3">
            <ul class="account-nav">
                <li>
                    <a href="<?= base_url('user/account/dashboard') ?>" class="menu-link menu-link_us-s">
                        Painel
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/orders') ?>" class="menu-link menu-link_us-s">
                        Encomendas
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/address') ?>" class="menu-link menu-link_us-s">
                        Endereços
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/edit') ?>" class="menu-link menu-link_us-s">
                        Dados da Conta
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/wishlist') ?>" class="menu-link menu-link_us-s menu-link_active">
                        Lista de Desejos
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/auth/logout') ?>" class="menu-link menu-link_us-s">
                        Terminar Sessão
                    </a>
                </li>
            </ul>
        </div>

        <div class="col-lg-9">
            <div class="page-content my-account__wishlist">
                <div class="products-grid row row-cols-2 row-cols-lg-3" id="products-grid">

                    <!-- Produto -->
                    <div class="product-card-wrapper">
                        <div class="product-card mb-3 mb-md-4 mb-xxl-5">
                            <div class="pc__img-wrapper">
                                <div class="swiper-container background-img js-swiper-slider" data-settings='{"resizeObserver": true}'>
                                    <div class="swiper-wrapper">
                                        <div class="swiper-slide">
                                            <img loading="lazy"
                                                 src="<?= base_url('images/products/product_5.jpg') ?>"
                                                 width="330" height="400"
                                                 alt="Casaco curto em pele sintética"
                                                 class="pc__img">
                                        </div>
                                        <div class="swiper-slide">
                                            <img loading="lazy"
                                                 src="<?= base_url('images/products/product_5-1.jpg') ?>"
                                                 width="330" height="400"
                                                 alt="Casaco curto em pele sintética"
                                                 class="pc__img">
                                        </div>
                                    </div>
                                    <span class="pc__img-prev"><svg width="7" height="11"><use href="#icon_prev_sm" /></svg></span>
                                    <span class="pc__img-next"><svg width="7" height="11"><use href="#icon_next_sm" /></svg></span>
                                </div>

                                <button class="btn-remove-from-wishlist" title="Remover da lista de desejos">
                                    <svg width="12" height="12"><use href="#icon_close" /></svg>
                                </button>
                            </div>

                            <div class="pc__info position-relative">
                                <p class="pc__category">Vestidos</p>
                                <h6 class="pc__title">Casaco Colorido</h6>

                                <div class="product-card__price d-flex">
                                    <span class="money price">€29</span>
                                </div>

                                <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0"
                                        title="Adicionar à lista de desejos">
                                    <svg width="16" height="16"><use href="#icon_heart" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /Produto -->

                    <!-- Os restantes produtos seguem exatamente a mesma estrutura -->
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mb-5 pb-xl-5"></div>

<?= $this->endSection() ?>
