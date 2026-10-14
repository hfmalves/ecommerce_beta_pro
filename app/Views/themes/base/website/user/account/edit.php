<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>

<section class="my-account container">
    <h2 class="page-title">Dados da Conta</h2>

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
                    <a href="<?= base_url('user/account/edit') ?>" class="menu-link menu-link_us-s menu-link_active">
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
            <div class="page-content my-account__edit">
                <div class="my-account__edit-form">
                    <form name="account_edit_form" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" id="account_first_name" placeholder="Nome" required>
                                    <label for="account_first_name">Nome</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" id="account_last_name" placeholder="Apelido" required>
                                    <label for="account_last_name">Apelido</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="text" class="form-control" id="account_display_name" placeholder="Nome de Exibição" required>
                                    <label for="account_display_name">Nome de Exibição</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="email" class="form-control" id="account_email" placeholder="Endereço de Email" required>
                                    <label for="account_email">Endereço de Email</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="my-3">
                                    <h5 class="text-uppercase mb-0">Alteração de Palavra-passe</h5>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="password" class="form-control" id="account_current_password" placeholder="Palavra-passe atual" required>
                                    <label for="account_current_password">Palavra-passe Atual</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input type="password" class="form-control" id="account_new_password" placeholder="Nova palavra-passe" required>
                                    <label for="account_new_password">Nova Palavra-passe</label>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-floating my-3">
                                    <input
                                            type="password"
                                            class="form-control"
                                            data-cf-pwd="#account_new_password"
                                            id="account_confirm_password"
                                            placeholder="Confirmar nova palavra-passe"
                                            required
                                    >
                                    <label for="account_confirm_password">Confirmar Nova Palavra-passe</label>
                                    <div class="invalid-feedback">
                                        As palavras-passe não coincidem.
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="my-3">
                                    <button class="btn btn-primary">
                                        Guardar Alterações
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="mb-5 pb-xl-5"></div>

<?= $this->endSection() ?>
