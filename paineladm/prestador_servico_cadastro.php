<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php'; // Ajuste o caminho se necessário (ex: '../model/Model.php')

$PrestadoresServicoModel = new PrestadoresServicoModel();
$ServicosModel = new ServicosModel();

// Busca todos os serviços disponíveis para popular o dropdown
$listaServicos = $ServicosModel->listarServicos();
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header">
            <h2 class="form-card-title">Cadastro de Prestador de Serviço</h2>
            <p class="form-card-subtitle">Insira as informações de contato e defina o portefólio de serviços deste parceiro.</p>
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

        <form action="provedores/prestador_servico_cadastrar.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-grid-layout">
                
                <div class="form-group-modern full-width">
                    <label for="nome">Nome do Prestador / Empresa</label>
                    <input type="text" name="nome" id="nome" class="form-control-modern" placeholder="Ex: EletroGesso Instalações LTDA" maxlength="300" required>
                </div>

                <div class="form-group-modern">
                    <label for="contato">Telefone / Contato</label>
                    <input type="text" name="contato" id="contato" class="form-control-modern" placeholder="(81) 99999-9999" maxlength="15" required>
                </div>

                <div class="form-group-modern">
                    <label for="cidade">Cidade de Atuação</label>
                    <input type="text" name="cidade" id="cidade" class="form-control-modern" placeholder="Ex: Garanhuns" maxlength="100" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="servico_selector">Adicionar Serviço ao Portefólio</label>
                    <select id="servico_selector" class="form-control-modern">
                        <option value="" selected disabled>Selecione um serviço para adicionar...</option>
                        <?php 
                        if (!empty($listaServicos)) {
                            foreach ($listaServicos as $servico) {
                                echo '<option value="' . htmlspecialchars($servico['id']) . '">' . htmlspecialchars($servico['tipo']) . '</option>';
                            }
                        } else {
                            echo '<option value="" disabled>Nenhum serviço cadastrado no sistema</option>';
                        }
                        ?>
                    </select>
                    <small style="color: var(--text-muted); font-size: 11px; margin-top: 4px; display: block;">
                        * Clique em um serviço acima para adicioná-lo automaticamente ao campo abaixo.
                    </small>
                </div>

                <div class="form-group-modern full-width">
                    <label for="servicos_prestados">Serviços Prestados (Separados por vírgula)</label>
                    <textarea name="servicos_prestados" id="servicos_prestados" class="form-control-modern" rows="3" placeholder="Os serviços selecionados aparecerão aqui..." required></textarea>
                </div>

            </div>

            <div class="form-actions-zone">
                <a href="prestadores_listagem.php" class="btn-cancel-modern">Voltar à Listagem</a>
                <button type="submit" class="btn-submit-modern btn-save-fix">
                    <span>Salvar Registro</span>
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