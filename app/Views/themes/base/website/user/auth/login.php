<?= $this->extend('themes/base/layout/main_website') ?>
<?= $this->section('content') ?>
<div class="mb-4 pb-4"></div>
<section class="login-register container">
    <h2 class="page-title">Iniciar Sessão</h2>
    <div class="login-form">
        <form method="post" action="<?= base_url('user/auth/login') ?>" class="needs-validation" novalidate>
            <div class="form-floating mb-3">
                <input name="email" type="email"
                       class="form-control form-control_gray"
                       id="loginEmail"
                       placeholder="Endereço de email"
                       required>
                <label for="loginEmail">Endereço de email *</label>
            </div>
            <div class="form-floating mb-3">
                <input name="password" type="password"
                       class="form-control form-control_gray"
                       id="loginPassword"
                       placeholder="Palavra-passe"
                       required>
                <label for="loginPassword">Palavra-passe *</label>
            </div>
            <div class="d-flex align-items-center mb-3">
                <div class="form-check">
                    <input name="remember" class="form-check-input" type="checkbox" id="rememberMe">
                    <label class="form-check-label" for="rememberMe">
                        Lembrar-me
                    </label>
                </div>
                <a href="<?= base_url('user/auth/recovery') ?>" class="btn-text ms-auto">
                    Esqueceu-se da palavra-passe?
                </a>
            </div>
            <button class="btn btn-primary w-100 text-uppercase">
                Entrar
            </button>
            <div class="text-center mt-4">
                <span class="text-secondary">Ainda não tem conta?</span>
                <a href="<?= base_url('user/auth/register') ?>" class="btn-text">
                    Criar conta
                </a>
            </div>
        </form>
    </div>
</section>
<div class="mb-5 pb-xl-5"></div>
<?= $this->endSection() ?>
