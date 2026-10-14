<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>
<section class="login-register container">
    <h2 class="page-title">Criar Conta</h2>
    <div class="register-form">
        <form method="post" action="<?= base_url('user/auth/register') ?>" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input name="username" type="text"
                       class="form-control form-control_gray"
                       id="registerUsername"
                       placeholder="Nome de utilizador"
                       required>
                <label for="registerUsername">Nome de utilizador</label>
            </div>
            <div class="form-floating mb-3">
                <input name="email" type="email"
                       class="form-control form-control_gray"
                       id="registerEmail"
                       placeholder="Endereço de email"
                       required>
                <label for="registerEmail">Endereço de email *</label>
            </div>
            <div class="form-floating mb-3">
                <input name="password" type="password"
                       class="form-control form-control_gray"
                       id="registerPassword"
                       placeholder="Palavra-passe"
                       required>
                <label for="registerPassword">Palavra-passe *</label>
            </div>
            <p class="text-secondary mb-3">
                Os seus dados serão utilizados apenas para gestão da sua conta,
                de acordo com a nossa política de privacidade.
            </p>
            <button class="btn btn-primary w-100 text-uppercase">
                Registar
            </button>
            <div class="text-center mt-4">
                <span class="text-secondary">Já tem conta?</span>
                <a href="<?= base_url('user/auth/login') ?>" class="btn-text">
                    Iniciar sessão
                </a>
            </div>
        </form>
    </div>
</section>
<div class="mb-5 pb-xl-5"></div>
<?= $this->endSection() ?>
