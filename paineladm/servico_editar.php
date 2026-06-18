<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Filtra e valida o ID enviado via GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow-sm rounded-3'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i> Por favor, insira um ID válido para consultar! 
            <a class='btn btn-sm btn-danger float-end' href='servico_listagem.php'>Voltar</a>
          </div></div>";
    include_once("controladores/footer.php");
    exit;
}

// Instancia o model e busca os dados do serviço
$ServicosModel = new ServicosModel();
$servico = $ServicosModel->buscaServicoPorId($id);

// Se o serviço não for encontrado, exibe o alerta e aborta
if (!$servico) {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow-sm rounded-3'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i> O serviço solicitado não foi encontrado no sistema! 
            <a class='btn btn-sm btn-danger float-end' href='servico_listagem.php'>Voltar</a>
          </div></div>";
    include_once("controladores/footer.php");
    exit;
}
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header">
            <h2 class="form-card-title">Editar Serviço</h2>
            <p class="form-card-subtitle">Atualize a descrição ou a nomenclatura do serviço selecionado.</p>
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

        <form action="provedores/servico_editar.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($servico['id']) ?>">

            <div class="form-grid-layout">
                <div class="form-group-modern full-width">
                    <label for="tipo">Tipo / Descrição do Serviço</label>
                    <input type="text" name="tipo" id="tipo" class="form-control-modern" placeholder="Ex: Manutenção Preventiva de Ar Condicionado" maxlength="300" value="<?= htmlspecialchars($servico['tipo']) ?>" required>
                </div>
            </div>

            <div class="form-actions-zone">
                <a href="servico_listagem.php" class="btn-cancel-modern">Cancelar e Voltar</a>
                <button type="submit" class="btn-submit-modern btn-save-fix">
                    <span>Atualizar Serviço</span>
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