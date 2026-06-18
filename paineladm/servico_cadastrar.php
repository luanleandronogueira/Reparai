<?php
session_start();
include 'controladores/header.php';
require_once '../model/Model.php';

// Apenas a instanciação do model, se necessitar de usar algo na view
$ServicosModel = new ServicosModel();
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header">
            <h2 class="form-card-title">Cadastro de Serviço</h2>
            <p class="form-card-subtitle">Insira a descrição ou o tipo do novo serviço a ser disponibilizado no sistema.</p>
        </div>

        <?php if (isset($_GET['mensagem_erro']) && !empty($_GET['mensagem_erro'])) { ?>
            <div class="alert-danger-modern">
                <?= htmlspecialchars($_GET['mensagem_erro']) ?>
            </div>
        <?php } ?>

        <?php if (isset($_GET['mensagem_sucesso']) && !empty($_GET['mensagem_sucesso'])) { ?>
            <div class="alert-success-modern">
                <?= htmlspecialchars($_GET['mensagem_sucesso']) ?>
            </div>
        <?php } ?>

        <form action="provedores/servico_cadastrar.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-grid-layout">
                <div class="form-group-modern full-width">
                    <label for="tipo">Tipo / Descrição do Serviço</label>
                    <input type="text" name="tipo" id="tipo" class="form-control-modern" placeholder="Ex: Manutenção Preventiva de Ar Condicionado" maxlength="300" required>
                </div>
            </div>

            <div class="form-actions-zone">
                <a href="servico_tipo.php" class="btn-cancel-modern">Voltar à Listagem</a>
                <button type="submit" class="btn-submit-modern btn-save-fix">
                    <span>Salvar Serviço</span>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </button>
            </div>

        </form>

    </div>
</div>

<?php
include 'controladores/footer.php';
?>