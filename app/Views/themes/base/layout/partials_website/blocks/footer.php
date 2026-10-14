<?php
$footerConfig = $block['footer']['config'] ?? [];
$categories   = $block['footer']['categories'] ?? [];
?>
<footer id="footer" class="footer footer_type_2 bordered">
    <?php if (!empty($footerConfig['show_newsletter'])): ?>
        <div class="footer-top container">
            <div class="block-newsletter">
                <h3 class="block__title">ASSINE NOSSA NEWSLETTER E APROVEITE</h3>
                <p>Receba as últimas novidades, produtos exclusivos e atualizações diárias de forma direta!</p>
                <form action="<?= base_url(); ?>" class="block-newsletter__form">
                    <input class="form-control" type="email" name="email" placeholder="Endereço de email">
                    <button class="btn btn-secondary fw-medium" type="submit">Subscrever</button>
                </form>
            </div>
        </div>
    <?php endif; ?>
    <div class="footer-middle container">
        <div class="row row-cols-lg-5 row-cols-2">
            <div class="footer-column footer-store-info col-12 mb-4 mb-lg-0">
                <div class="logo">
                    <a href="<?= base_url(); ?>">
                        <img src="<?= base_url('themes/base/images/logo.png'); ?>"
                             alt="Logo"
                             class="logo__image d-block">
                    </a>
                </div>
                <?php if (!empty($footerConfig['show_info'])): ?>
                    <?php
                    $addressParts = array_filter([
                            $footerConfig['store_name'] ?? null,
                            $footerConfig['address_street'] ?? null,
                            trim(
                                    ($footerConfig['address_postcode'] ?? '') . ' ' .
                                    ($footerConfig['address_city'] ?? '')
                            ),
                            $footerConfig['address_country'] ?? null,
                    ]);
                    ?>

                    <?php if (!empty($addressParts)): ?>
                        <p class="footer-address">
                            <?= esc(implode(', ', $addressParts)) ?>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($footerConfig['contact_email'])): ?>
                        <p class="m-0">
                            <strong class="fw-medium">
                                <?= esc($footerConfig['contact_email']) ?>
                            </strong>
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($footerConfig['contact_phone'])): ?>
                        <p>
                            <strong class="fw-medium">
                                <?= esc($footerConfig['contact_phone']) ?>
                            </strong>
                        </p>
                    <?php endif; ?>
                <?php endif; ?>
                <?php if (!empty($footerConfig['show_social'])): ?>
                    <ul class="social-links list-unstyled d-flex flex-wrap mb-0">

                        <?php if (!empty($footerConfig['facebook_url'])): ?>
                            <li>
                                <a href="<?= esc($footerConfig['facebook_url']) ?>" class="footer__social-link d-block" target="_blank">
                                    <svg width="9" height="15"><use href="#icon_facebook" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['instagram_url'])): ?>
                            <li>
                                <a href="<?= esc($footerConfig['instagram_url']) ?>" class="footer__social-link d-block" target="_blank">
                                    <svg width="14" height="14"><use href="#icon_instagram" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['twitter_url'])): ?>
                            <li>
                                <a href="<?= esc($footerConfig['twitter_url']) ?>" class="footer__social-link d-block" target="_blank">
                                    <svg width="14" height="14"><use href="#icon_twitter" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['linkedin_url'])): ?>
                            <li>
                                <a href="<?= esc($footerConfig['linkedin_url']) ?>" class="footer__social-link d-block" target="_blank">
                                    <svg width="14" height="14"><use href="#icon_linkedin" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['youtube_url'])): ?>
                            <li>
                                <a href="<?= esc($footerConfig['youtube_url']) ?>" class="footer__social-link d-block" target="_blank">
                                    <svg width="14" height="14"><use href="#icon_youtube" /></svg>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                <?php endif; ?>

            </div>
            <?php foreach ($categories as $category): ?>
                <div class="footer-column footer-menu mb-4 mb-lg-0">
                    <h6 class="sub-menu__title text-uppercase">
                        <?= esc($category['title']) ?>
                    </h6>
                    <?php if (!empty($category['items'])): ?>
                        <ul class="sub-menu__list list-unstyled">
                            <?php foreach ($category['items'] as $item): ?>
                                <li class="sub-menu__item">
                                    <a
                                            href="<?= esc($item['url'] ?? '#') ?>"
                                            class="menu-link menu-link_us-s"
                                    >
                                        <?= esc($item['label']) ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            <?php if (!empty($footerConfig['show_opening_times'])): ?>
                <div class="footer-column mb-4 mb-lg-0">
                    <h6 class="sub-menu__title text-uppercase">Horário</h6>
                    <ul class="list-unstyled">

                        <?php if (!empty($footerConfig['opening_monday'])): ?>
                            <li><span class="menu-link">Seg: <?= esc($footerConfig['opening_monday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_tuesday'])): ?>
                            <li><span class="menu-link">Ter: <?= esc($footerConfig['opening_tuesday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_wednesday'])): ?>
                            <li><span class="menu-link">Qua: <?= esc($footerConfig['opening_wednesday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_thursday'])): ?>
                            <li><span class="menu-link">Qui: <?= esc($footerConfig['opening_thursday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_friday'])): ?>
                            <li><span class="menu-link">Sex: <?= esc($footerConfig['opening_friday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_saturday'])): ?>
                            <li><span class="menu-link">Sab: <?= esc($footerConfig['opening_saturday']) ?></span></li>
                        <?php endif; ?>

                        <?php if (!empty($footerConfig['opening_sunday'])): ?>
                            <li><span class="menu-link">Dom: <?= esc($footerConfig['opening_sunday']) ?></span></li>
                        <?php endif; ?>

                    </ul>
                </div>
            <?php endif; ?>

        </div>
    </div>
    <div class="footer-bottom">
        <div class="container d-md-flex align-items-center">
            <span class="footer-copyright me-auto">Todos os direitos reservados. ©Nokeme - Produtos de Higine</span>
            <div class="footer-settings d-md-flex align-items-center">
                <span class="footer-copyright me-auto">BAGG - ECOMERCE by HUGO -  Digital Studio</span>
            </div><!-- /.footer-settings -->
        </div><!-- /.container d-flex align-items-center -->
    </div><!-- /.footer-bottom container -->
</footer>
