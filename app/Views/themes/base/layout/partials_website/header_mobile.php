<!-- Mobile Header -->
<div class="header-mobile header_sticky">
    <div class="container d-flex align-items-center h-100">
        <a class="mobile-nav-activator d-block position-relative" href="#">
            <svg class="nav-icon" width="25" height="18" viewBox="0 0 25 18" xmlns="http://www.w3.org/2000/svg"><use href="#icon_nav" /></svg>
            <span class="btn-close-lg position-absolute top-0 start-0 w-100"></span>
        </a>
        <div class="logo">
            <a href="<?= base_url(); ?>">
                <img src="<?= base_url('themes/base/images/logo.png'); ?>" alt="Uomo" class="logo__image d-block">
            </a>
        </div>
        <a href="#" class="header-tools__item header-tools__cart js-open-aside" data-aside="cartDrawer">
            <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_cart" /></svg>
            <span class="cart-amount d-block position-absolute js-cart-items-count">3</span>
        </a>
    </div>
    <nav class="header-mobile__navigation navigation d-flex flex-column w-100 position-absolute top-100 bg-body overflow-auto">
        <div class="container">
            <form action="<?= base_url('search'); ?>" method="GET" class="search-field position-relative mt-4 mb-3">
                <div class="position-relative">
                    <input class="search-field__input w-100 border rounded-1" type="text" name="search-keyword" placeholder="Pesquisar">
                    <button class="btn-icon search-popup__submit pb-0 me-2" type="submit">
                        <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_search" /></svg>
                    </button>
                    <button class="btn-icon btn-close-lg search-popup__reset pb-0 me-2" type="reset"></button>
                </div>
                <div class="position-absolute start-0 top-100 m-0 w-100">
                    <div class="search-result"></div>
                </div>
            </form><!-- /.header-search -->
        </div><!-- /.container -->
        <div class="container">
            <div class="overflow-hidden">
                <ul class="navigation__list list-unstyled position-relative">
                    <?php
                    helper('website/menu_mobile');
                    echo website_menu_mobile($menu);
                    ?>
                </ul><!-- /.navigation__list -->
            </div><!-- /.overflow-hidden -->
        </div><!-- /.container --> 
    </nav><!-- /.navigation -->
</div><!-- /.header-mobile -->