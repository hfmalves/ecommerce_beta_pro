<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>
<section class="login-register container">
    <h2 class="page-title">Recuperar Palavra-passe</h2>
    <div class="login-form">
        <form method="post" action="<?= base_url('user/auth/recovery') ?>" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input name="email" type="email"
                       class="form-control form-control_gray"
                       id="recoveryEmail"
                       placeholder="Endereço de email"
                       required>
                <label for="recoveryEmail">Endereço de email *</label>
            </div>
            <p class="text-secondary mb-3">
                Será enviado um email com instruções para redefinir a sua palavra-passe.
            </p>
            <button class="btn btn-primary w-100 text-uppercase">
                Recuperar Palavra-passe
            </button>
            <div class="text-center mt-4">
                <a href="<?= base_url('user/auth/login') ?>" class="btn-text">
                    Voltar ao login
                </a>
            </div>
        </form>
    </div>
</section>
<div class="mb-5 pb-xl-5"></div>
<?= $this->endSection() ?>
