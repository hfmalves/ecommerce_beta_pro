<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>
<section class="my-account container">
    <h2 class="page-title">A Minha Conta</h2>
    <div class="row">
        <div class="col-lg-3">
            <ul class="account-nav">
                <li>
                    <a href="<?= base_url('user/account/dashboard') ?>"
                       class="menu-link menu-link_us-s menu-link_active">
                        Painel
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/orders') ?>"
                       class="menu-link menu-link_us-s">
                        Encomendas
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/address') ?>"
                       class="menu-link menu-link_us-s">
                        Endereços
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/edit') ?>"
                       class="menu-link menu-link_us-s">
                        Dados da Conta
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/wishlist') ?>"
                       class="menu-link menu-link_us-s">
                        Favoritos
                    </a>
                </li>
            </ul>
        </div>
        <div class="col-lg-9">
            <div class="page-content my-account__dashboard">
                <p>
                    Olá <strong><?= esc($username ?? '') ?></strong>
                    (não é você?
                    <a href="<?= base_url('user/auth/logout') ?>">Terminar sessão</a>)
                </p>
                <p>
                    A partir do painel da sua conta pode consultar as suas
                    <a class="unerline-link" href="<?= base_url('user/account/orders') ?>">
                        encomendas recentes
                    </a>,
                    gerir os seus
                    <a class="unerline-link" href="<?= base_url('user/account/address') ?>">
                        endereços de envio e faturação
                    </a>
                    e
                    <a class="unerline-link" href="<?= base_url('user/account/edit') ?>">
                        alterar a palavra-passe e os dados da conta
                    </a>.
                </p>
            </div>
        </div>
    </div>
</section>
<div class="mb-5 pb-xl-5"></div>
<?= $this->endSection() ?>
