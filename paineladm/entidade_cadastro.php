<?php
session_start();
include 'controladores/header.php';

?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header">
            <h2 class="form-card-title">Cadastro de Entidade</h2>
            <p class="form-card-subtitle">Insira as informações institucionais e de contato da nova unidade.</p>
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

        <form action="provedores/entidade_cadastro.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

            <div class="form-grid-layout">
                
                <div class="form-group-modern full-width">
                    <label for="entidade_nome">Nome da Entidade / Razão Social</label>
                    <input type="text" name="entidade_nome" id="entidade_nome" class="form-control-modern" placeholder="Ex: JL Comércio e Serviços LTDA" maxlength="200" required>
                </div>

                <div class="form-group-modern">
                    <label for="cnpj">CNPJ</label>
                    <input type="text" name="cnpj" id="cnpj" class="form-control-modern" placeholder="00.000.000/0001-00" maxlength="15" required>
                </div>

                <div class="form-group-modern">
                    <label for="responsavel">Nome do Responsável</label>
                    <input type="text" name="responsavel" id="responsavel" class="form-control-modern" placeholder="Ex: João da Silva" maxlength="150" required>
                </div>

                <div class="form-group-modern">
                    <label for="email">E-mail de Contato</label>
                    <input type="email" name="email" id="email" class="form-control-modern" placeholder="Ex: contato@empresa.com" maxlength="200" required>
                </div>

                <div class="form-group-modern">
                    <label for="telefone">Telefone Comercial</label>
                    <input type="text" name="telefone" id="telefone" class="form-control-modern" placeholder="(81) 3771-1234" maxlength="11" required>
                </div>

                <div class="form-group-modern full-width">
                    <label for="ativo">Status de Ativação</label>
                    <select name="ativo" id="ativo" class="form-control-modern" required>
                        <option value="S" selected>Ativo (Acesso Liberado ao Sistema)</option>
                        <option value="N">Inativo (Acesso Bloqueado)</option>
                    </select>
                </div>

            </div>

            <div class="form-actions-zone">
                <a href="painel.php" class="btn-cancel-modern">Voltar ao Painel</a>
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