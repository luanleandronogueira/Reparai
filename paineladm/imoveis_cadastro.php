<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Busca todas as entidades cadastradas para popular o combo de relacionamento
$EntidadeModel = new EntidadeModel();
$entidades = $EntidadeModel->listaTodasEntidades();
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header">
            <h2 class="form-card-title">Cadastro de Imóvel</h2>
            <p class="form-card-subtitle">Insira as especificações da propriedade física e faça a vinculação institucional.</p>
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

        <form action="provedores/imoveis_cadastro.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-grid-layout">
                
                <div class="form-group-modern full-width">
                    <label for="nome_locacao">Nome da Locação / Identificação do Imóvel</label>
                    <input type="text" name="nome_locacao" id="nome_locacao" class="form-control-modern" placeholder="Ex: Prédio Central - Setor Administrativo" maxlength="255" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="endereco">Endereço Completo</label>
                    <input type="text" name="endereco" id="endereco" class="form-control-modern" placeholder="Ex: Av. Governador Agamenon Magalhães, nº 1500, Centro" maxlength="255" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="entidade">Entidade Parceira Responsável</label>
                    <select name="entidade" id="entidade" class="form-control-modern" required>
                        <option value="" disabled selected>Selecione uma entidade para vincular...</option>
                        <?php 
                        if (!empty($entidades)) {
                            foreach ($entidades as $empresa) {
                                // Exibe apenas entidades que estejam ativas, se preferir filtre por $empresa['ativo'] === 'S'
                                echo '<option value="' . htmlspecialchars($empresa['id']) . '">' . htmlspecialchars($empresa['entidade_nome']) . '</option>';
                            }
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="form-actions-zone">
                <a href="imoveis_listagem.php" class="btn-cancel-modern">Voltar para Listagem</a>
                <button type="submit" class="btn-submit-modern btn-save-fix">
                    <span>Salvar Imóvel</span>
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