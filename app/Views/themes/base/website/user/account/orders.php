<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>

<section class="my-account container">
    <h2 class="page-title">Encomendas</h2>

    <div class="row">
        <div class="col-lg-3">
            <ul class="account-nav">
                <li>
                    <a href="<?= base_url('user/account/dashboard') ?>" class="menu-link menu-link_us-s">
                        Painel
                    </a>
                </li>
                <li>
                    <a href="<?= base_url('user/account/orders') ?>" class="menu-link menu-link_us-s menu-link_active">
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
            <div class="page-content my-account__orders-list">
                <table class="orders-table">
                    <thead>
                    <tr>
                        <th>Encomenda</th>
                        <th>Data</th>
                        <th>Estado</th>
                        <th>Total</th>
                        <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>#2416</td>
                        <td>1 de outubro de 2023</td>
                        <td>Em espera</td>
                        <td>€1.200,65 · 3 artigos</td>
                        <td>
                            <a href="<?= base_url('user/account/orders/2416') ?>" class="btn btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>#2417</td>
                        <td>2 de outubro de 2023</td>
                        <td>Em espera</td>
                        <td>€1.200,65 · 3 artigos</td>
                        <td>
                            <a href="<?= base_url('user/account/orders/2417') ?>" class="btn btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>#2418</td>
                        <td>3 de outubro de 2023</td>
                        <td>Em espera</td>
                        <td>€1.200,65 · 3 artigos</td>
                        <td>
                            <a href="<?= base_url('user/account/orders/2418') ?>" class="btn btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td>#2419</td>
                        <td>4 de outubro de 2023</td>
                        <td>Em espera</td>
                        <td>€1.200,65 · 3 artigos</td>
                        <td>
                            <a href="<?= base_url('user/account/orders/2419') ?>" class="btn btn-primary">
                                Ver
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>
