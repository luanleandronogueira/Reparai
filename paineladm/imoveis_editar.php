<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Filtra e valida o ID do imóvel enviado via URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow-sm rounded-3'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i> Por favor, insira um ID válido para consultar! 
            <a class='btn btn-sm btn-danger float-end' href='imoveis_listagem.php'>Voltar</a>
          </div></div>";
    include_once("controladores/footer.php");
    exit;
}

// Instancia as models necessárias
$ImoveisModel = new ImoveisModel();
$EntidadeModel = new EntidadeModel();

// Busca os dados do imóvel atual e todas as entidades para o select de vínculo
$imovel = $ImoveisModel->buscaImovelPorId($id);
$entidades = $EntidadeModel->listaTodasEntidades();

//Se o imóvel não for encontrado, aborta a renderização
if (!$imovel) {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow-sm rounded-3'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i> O imóvel solicitado não foi encontrado no sistema! 
            <a class='btn btn-sm btn-danger float-end' href='imoveis_listagem.php'>Voltar</a>
          </div></div>";
    include_once("controladores/footer.php");
    exit;
}


?>

<div class="content-page-container">
    <div class="form-card-wrapper">

        <div class="form-card-header">
            <h2 class="form-card-title">Editar Imóvel</h2>
            <p class="form-card-subtitle">Atualize as informações de localização, identificação e o vínculo institucional da unidade selecionada.</p>
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

        <form action="provedores/imoveis_editar.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="id" value="<?= htmlspecialchars($imovel['id']) ?>">

            <div class="form-grid-layout">
                
                <div class="form-group-modern full-width">
                    <label for="nome_locacao">Nome da Locação / Identificação do Imóvel</label>
                    <input type="text" name="nome_locacao" id="nome_locacao" class="form-control-modern" placeholder="Ex: Prédio Administrativo - Centro" value="<?= htmlspecialchars($imovel['nome_locacao']) ?>" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="endereco">Endereço Completo</label>
                    <input type="text" name="endereco" id="endereco" class="form-control-modern" placeholder="Rua, Nº, Bairro, Cidade - UF" value="<?= htmlspecialchars($imovel['endereco']) ?>" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="entidade">Entidade Vinculada / Beneficiária</label>
                    <select name="entidade" id="entidade" class="form-control-modern" required>
                        <option value="" disabled>Selecione uma Entidade Parceira...</option>
                        <?php 
                        if (!empty($entidades)) {
                            foreach ($entidades as $empresa) { 
                                $selected = ($imovel['entidade'] == $empresa['id']) ? 'selected' : '';
                                ?>
                                <option value="<?= htmlspecialchars($empresa['id']) ?>" <?= $selected ?>>
                                    <?= htmlspecialchars($empresa['entidade_nome']) ?> (CNPJ: <?= htmlspecialchars($empresa['cnpj']) ?>)
                                </option>
                            <?php 
                            }
                        } else { ?>
                            <option value="" disabled>Nenhuma entidade cadastrada no sistema</option>
                        <?php } ?>
                    </select>
                </div>

            </div>

            <div class="form-actions-zone">
                <a href="imoveis_listagem.php" class="btn-cancel-modern">Cancelar e Voltar</a>
                <button type="submit" class="btn-submit-modern btn-save-fix">
                    <span>Atualizar Registro</span>
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="16" height="16">
                        <path stroke-linecap=\"round\" stroke-linejoin=\"round\" d=\"M4.5 12.75l6 6 9-13.5\" />
                    </svg>
                </button>
            </div>

        </form>

    </div>
</div>

<?php
include 'controladores/footer.php';
?>