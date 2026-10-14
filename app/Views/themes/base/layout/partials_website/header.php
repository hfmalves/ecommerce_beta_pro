<header id="header" class="header header_sticky header-fullwidth">
    <div class="header-desk header-desk_type_5">
        <div class="logo">
            <a href="<?= base_url(); ?>">
                <img src="<?= base_url('themes/base/images/logo.png'); ?>" alt="Uomo" class="logo__image d-block">
            </a>
        </div>
        <form action="<?= base_url('search'); ?>" method="GET"
              class="header-search search-field d-none d-xxl-flex">
            <button class="btn header-search__btn" type="submit">
                <svg width="20" height="20"><use href="#icon_search"></use></svg>
            </button>
            <input class="header-search__input w-100"
                   type="text"
                   name="search-keyword"
                   placeholder="Search products...">
            <div class="hover-container position-relative">
            </div>
        </form>
        <?= view('themes/base/layout/partials_website/menu') ?>
        <div class="header-tools d-flex align-items-center">
            <div class="header-tools__item hover-container">
                <a href="<?= base_url('user/account/dashboard'); ?>"  class="header-tools__item" href="#" data-aside="customerForms">
                    <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_user" /></svg>
                </a>
            </div>
            <a class="header-tools__item" href="<?= base_url('user/account/wishlist'); ?>">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_heart" /></svg>
            </a>
            <a href="<?= base_url('user/account/wishlist'); ?>" class="header-tools__item header-tools__cart js-open-aside" data-aside="cartDrawer">
                <svg class="d-block" width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><use href="#icon_cart" /></svg>
                <span class="cart-amount d-block position-absolute js-cart-items-count">3</span>
            </a>
        </div>
    </div>
</header>
