<div class="mb-4 pb-4"></div>
<section class="store-location container">
    <h2 class="page-title">Localizar Loja</h2>
    <div class="row">
        <div class="col-lg-4">
            <form action="https://uomo-html.flexkitux.com/Demo1/search.html" method="GET">
                <div class="store-location__search-result">
                    <div class="store-location__search-result__item">
                        <h5>Loja</h5>
                        <p>
                            <?= esc($store_settings['address']) ?><br>
                            <?= esc($store_settings['country']) ?><br>
                            <?= esc($store_settings['phone']) ?><br>
                            <?= nl2br(esc($store_settings['working_hours'])) ?>
                        </p>

                        <a id="store_selector_1" href="#map">Ver no mapa</a>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-lg-8">
            <div class="google-map__wrapper">
                <div id="map">
                </div>
                <div class="google-map__marker-detail hide">
                    <a href="javascript:void(0)" class="btn-close">
                        <svg width="10" height="10" viewBox="0 0 10 10" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M0.259435 8.85506L9.11449 0L10 0.885506L1.14494 9.74056L0.259435 8.85506Z" fill="#767676"/>
                            <path d="M0.885506 0.0889838L9.74057 8.94404L8.85506 9.82955L0 0.97449L0.885506 0.0889838Z" fill="#767676"/>
                        </svg>
                    </a>
                    <div class="google-map__marker-detail__content">
                        <h5>Store in London</h5>
                        <p>1418 River Drive, Suite 35 Cottonhall, CA 9622<br>United States<br>+1 246-345-0695<br>10 am - 10 pm EST, 7 days a week</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>