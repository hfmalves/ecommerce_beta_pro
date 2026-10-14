<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>

<div class="mb-4 pb-4"></div>

<section class="my-account container">
    <h2 class="page-title">Endereços</h2>

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
                    <a href="<?= base_url('user/account/address') ?>" class="menu-link menu-link_us-s menu-link_active">
                        Endereços
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/edit') ?>" class="menu-link menu-link_us-s">
                        Dados da Conta
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/wishlist') ?>" class="menu-link menu-link_us-s">
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
            <div class="page-content my-account__address">
                <p class="notice">
                    Os endereços abaixo serão utilizados por defeito no processo de checkout.
                </p>

                <div class="my-account__address-list">
                    <div class="my-account__address-item">
                        <div class="my-account__address-item__title">
                            <h5>Endereço de Faturação</h5>
                            <a href="#">Editar</a>
                        </div>
                        <div class="my-account__address-item__detail">
                            <p>Daniel Robinson</p>
                            <p>1418 River Drive, Suite 35, Cottonhall, CA 9622</p>
                            <p>Estados Unidos</p>
                            <br>
                            <p>sale@uomo.com</p>
                            <p>+1 246-345-0695</p>
                        </div>
                    </div>

                    <div class="my-account__address-item">
                        <div class="my-account__address-item__title">
                            <h5>Endereço de Envio</h5>
                            <a href="#">Editar</a>
                        </div>
                        <div class="my-account__address-item__detail">
                            <p>Daniel Robinson</p>
                            <p>1418 River Drive, Suite 35, Cottonhall, CA 9622</p>
                            <p>Estados Unidos</p>
                            <br>
                            <p>sale@uomo.com</p>
                            <p>+1 246-345-0695</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mb-5 pb-xl-5"></div>

<?= $this->endSection() ?>
