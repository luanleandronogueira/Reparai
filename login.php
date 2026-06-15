<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include 'controladores/header.php';
?>

<div class="login-viewport-container">
    <div class="login-center-box">
        
        <div class="login-icon-wrapper">
            <div class="brand-shield-icon">
                <span class="shield-text">JL</span>
            </div>
        </div>

        <h2 class="login-main-title">Sistema de <span class="text-highlight">Gestão</span></h2>
        <p class="login-top-subtitle">Acesse com seu e-mail corporativo para continuar</p>

        <?php if (isset($_GET['mensagem_erro']) && !empty($_GET['mensagem_erro'])) { ?>
            <div class="alert-danger-modern">
                <?= htmlspecialchars($_GET['mensagem_erro']) ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['mensagem_sucesso']) && !empty($_GET['mensagem_sucesso'])) { ?>
            <div class="alert alert-success border-0 rounded-4 p-3 mb-3 text-start small fw-bold">
                <?= htmlspecialchars($_GET['mensagem_sucesso']) ?>
            </div>
        <?php } ?>

        <div class="login-card-modern">
            <form action="provedores/autentica_usuario.php" method="POST" id="loginForm">
                
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <input type="hidden" name="g-recaptcha-response" id="g-recaptcha-response">

                <div class="form-group-modern">
                    <label for="email">E-MAIL CORPORATIVO</label>
                    <input type="email" name="email" id="email" class="form-control-modern" placeholder="exemplo@jlcomercio.com.br" required autocomplete="email">
                </div>

                <div class="form-group-modern">
                    <div class="label-row">
                        <label for="senha">SENHA DE ACESSO</label>
                        <a href="recuperar_senha.php" class="forgot-password-link">Esqueceu a senha?</a>
                    </div>
                    <input type="password" name="senha" id="senha" class="form-modern-control form-control-modern" placeholder="••••••••" required autocomplete="current-password">
                </div>

                <button type="submit" class="btn-submit-modern">
                    <span>Entrar no Sistema</span>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </form>
        </div>

        <p class="login-footer-help">
            Precisa de ajuda? <a href="suporte.php">Contatar o suporte técnico</a>
        </p>
    </div>
</div>


<?php
include 'controladores/footer.php';
?>