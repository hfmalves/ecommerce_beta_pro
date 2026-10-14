<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<section class="full-width_padding">
    <div class="full-width_border border-2" style="border-color: #f5e6e0;">
        <div class="shop-banner position-relative ">
            <div class="background-img" style="background-color: #f5e6e0;">
                <img loading="lazy" src="../images/shop/shop_banner_2.png" width="1759" height="420" alt="Pattern" class="slideshow-bg__img object-fit-cover">
            </div>
            <div class="shop-banner__content container position-absolute start-50 top-50 translate-middle">
                <h2 class="h1 text-uppercase text-center fw-bold mb-3 mb-xl-4 mb-xl-5">
                    <?= esc($category['name']) ?>
                </h2>
                <ul class="list list-inline mb-0">
                    <?php if (!empty($subcategories)): ?>
                        <?php foreach ($subcategories as $sub): ?>
                            <li class="list-item">
                                <a
                                    href="<?= site_url('category/' . $sub['slug']) ?>"
                                    class="menu-link py-1 <?= $sub['id'] == $category['id'] ? 'menu-link_active' : '' ?>"
                                >
                                    <?= esc($sub['name']) ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>

                    <?php endif; ?>
                </ul>
            </div><!-- /.shop-banner__content -->
        </div><!-- /.shop-banner position-relative -->
    </div><!-- /.full-width_border -->
</section><!-- /.full-width_padding-->
<div class="mb-4 pb-lg-3"></div>
<section class="shop-main container d-flex">
    <div class="shop-sidebar side-sticky bg-body" id="shopFilter">
        <div class="aside-header d-flex d-lg-none align-items-center">
            <h3 class="text-uppercase fs-6 mb-0">Filtrar por</h3>
            <button class="btn-close-lg js-close-aside btn-close-aside ms-auto"></button>
        </div><!-- /.aside-header -->

        <div class="pt-4 pt-lg-0"></div>

        <div class="accordion" id="categories-list">
            <div class="accordion-item mb-4 pb-3">
                <h5 class="accordion-header" id="accordion-heading-11">
                    <button class="accordion-button p-0 border-0 fs-5 text-uppercase" type="button" data-bs-toggle="collapse" data-bs-target="#accordion-filter-1" aria-expanded="true" aria-controls="accordion-filter-1">
                        Categorias
                        <svg class="accordion-button__icon type2" viewBox="0 0 10 6" xmlns="http://www.w3.org/2000/svg">
                            <g aria-hidden="true" stroke="none" fill-rule="evenodd">
                                <path d="M5.35668 0.159286C5.16235 -0.053094 4.83769 -0.0530941 4.64287 0.159286L0.147611 5.05963C-0.0492049 5.27473 -0.049205 5.62357 0.147611 5.83813C0.344427 6.05323 0.664108 6.05323 0.860924 5.83813L5 1.32706L9.13858 5.83867C9.33589 6.05378 9.65507 6.05378 9.85239 5.83867C10.0492 5.62357 10.0492 5.27473 9.85239 5.06018L5.35668 0.159286Z"/>
                            </g>
                        </svg>
                    </button>
                </h5>
                <div id="accordion-filter-1" class="accordion-collapse collapse show border-0" aria-labelledby="accordion-heading-11" data-bs-parent="#categories-list">
                    <div class="accordion-body px-0 pb-0 pt-3">
                        <ul class="list list-inline mb-0">
                            <?php foreach ($allCategories as $cat): ?>
                                <li class="list-item">
                                    <a
                                        href="<?= site_url('category/' . $cat['slug']) ?>"
                                        class="menu-link py-1 <?= $cat['id'] === $category['id'] ? 'menu-link_active' : '' ?>"
                                    >
                                        <?= esc($cat['name']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                    </div>
                </div>
            </div><!-- /.accordion-item -->
        </div><!-- /.accordion-item -->
        <?php foreach ($attributes as $attribute): ?>
            <?php if (empty($attribute['values'])) continue; ?>

            <div class="accordion mb-4 pb-3">
                <div class="accordion-item">
                    <h5 class="accordion-header">
                        <button class="accordion-button p-0 border-0 fs-5 text-uppercase"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#filter-<?= esc($attribute['id']) ?>">
                            <?= esc($attribute['name']) ?>
                        </button>
                    </h5>

                    <div id="filter-<?= esc($attribute['id']) ?>" class="accordion-collapse collapse show">
                        <div class="accordion-body px-0 pb-0">
                            <div class="d-flex flex-wrap">

                                <?php foreach ($attribute['values'] as $value): ?>
                                    <a href="#"
                                       class="btn btn-sm btn-outline-light mb-3 me-3 js-filter"
                                       data-attribute="<?= esc($attribute['code']) ?>"
                                       data-value="<?= esc($value['value']) ?>">
                                        <?= esc($value['value']) ?>
                                    </a>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div><!-- /.shop-sidebar -->

    <div class="shop-list flex-grow-1">
        <div class="d-flex justify-content-between mb-4 pb-md-2">
            <div class="breadcrumb mb-0 d-none d-md-block flex-grow-1">
                <a href="<?= base_url(''); ?>" class="menu-link menu-link_us-s text-uppercase fw-medium" >Home</a>
                <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                <a href="#" class="menu-link menu-link_us-s text-uppercase fw-medium" >Categorias</a>
                <span class="breadcrumb-separator menu-link fw-medium ps-1 pe-1">/</span>
                <a href="<?= site_url('category/' . $category['slug']) ?>"
                   class="menu-link menu-link_us-s text-uppercase fw-medium">
                    <?= esc($category['name']) ?>
                </a>
            </div><!-- /.breadcrumb -->

            <div class="shop-acs d-flex align-items-center justify-content-between justify-content-md-end flex-grow-1">
                <select class="shop-acs__select form-select w-auto border-0 py-0 order-1 order-md-0"
                        aria-label="Ordenar produtos"
                        name="sort">
                    <option value="" selected>Ordenação padrão</option>
                    <option value="featured">Destaques</option>
                    <option value="best_selling">Mais vendidos</option>
                    <option value="az">Ordem alfabética (A–Z)</option>
                    <option value="za">Ordem alfabética (Z–A)</option>
                    <option value="price_asc">Preço: do mais baixo ao mais alto</option>
                    <option value="price_desc">Preço: do mais alto ao mais baixo</option>
                    <option value="date_asc">Data: do mais antigo ao mais recente</option>
                    <option value="date_desc">Data: do mais recente ao mais antigo</option>
                </select>
            </div><!-- /.shop-acs -->
        </div><!-- /.d-flex justify-content-between -->

        <div class="products-grid row row-cols-2 row-cols-md-3" id="products-grid">

            <?php if (empty($products)): ?>
                <div class="col-12">
                    <p class="text-muted">Não existem produtos nesta categoria.</p>
                </div>
            <?php endif; ?>

            <?php foreach ($products as $product): ?>
                <div class="product-card-wrapper">
                    <div class="product-card mb-3 mb-md-4 mb-xxl-5">

                        <div class="pc__img-wrapper">
                            <a href="<?= site_url('product/' . $product['slug']) ?>">
                                <?php if (!empty($product['image'])): ?>
                                    <img
                                            loading="lazy"
                                            src="<?= base_url('uploads/product_images/' . esc($product['image'])) ?>"
                                            alt="<?= esc($product['name']) ?>"
                                            width="330"
                                            height="400"
                                            class="pc__img mb-2"
                                            onerror="this.onerror=null;this.src='https://placehold.co/330x400';"
                                    />
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

                            <button
                                class="pc__atc btn anim_appear-bottom position-absolute border-0 text-uppercase fw-medium">
                                Adicionar ao carrinho
                            </button>
                        </div>

                        <div class="pc__info position-relative">
                            <p class="pc__category"><?= esc($category['name']) ?></p>

                            <h6 class="pc__title">
                                <a href="<?= site_url('product/' . $product['slug']) ?>">
                                    <?= esc($product['name']) ?>
                                </a>
                            </h6>

                            <div class="product-card__price d-flex">
                        <span class="money price">
                            <?= number_format($product['base_price'], 2, ',', '.') ?> €
                        </span>
                            </div>

                            <button class="pc__btn-wl position-absolute top-0 end-0 bg-transparent border-0">
                                <svg width="16" height="16"><use href="#icon_heart"/></svg>
                            </button>
                        </div>

                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <nav class="shop-pages d-flex justify-content-between mt-3" aria-label="Navegação de páginas">
            <a href="#" class="btn-link d-inline-flex align-items-center">
                <svg class="me-1" width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_prev_sm" />
                </svg>
                <span class="fw-medium">ANTERIOR</span>
            </a>

            <ul class="pagination mb-0">
                <li class="page-item">
                    <a class="btn-link px-1 mx-2 btn-link_active" href="#">1</a>
                </li>
                <li class="page-item">
                    <a class="btn-link px-1 mx-2" href="#">2</a>
                </li>
                <li class="page-item">
                    <a class="btn-link px-1 mx-2" href="#">3</a>
                </li>
                <li class="page-item">
                    <a class="btn-link px-1 mx-2" href="#">4</a>
                </li>
            </ul>

            <a href="#" class="btn-link d-inline-flex align-items-center">
                <span class="fw-medium me-1">SEGUINTE</span>
                <svg width="7" height="11" viewBox="0 0 7 11" xmlns="http://www.w3.org/2000/svg">
                    <use href="#icon_next_sm" />
                </svg>
            </a>
        </nav>

    </div>
</section><!-- /.shop-main container -->
<?= $this->endSection() ?>
