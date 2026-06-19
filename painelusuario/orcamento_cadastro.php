<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Modelos necessários
$ImoveisModel = new ImoveisModel();

// Recupera o ID da entidade da sessão
$entidade_id = $_SESSION['usuario_entidade'] ?? null;

// Busca imóveis vinculados a esta entidade específica
$meusImoveis = $ImoveisModel->listarImoveisPorEntidade($entidade_id);
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        <div class="form-card-header">
            <h2 class="form-card-title">Solicitar Orçamento de Serviço</h2>
            <p class="form-card-subtitle">Descreva o problema e anexe evidências para análise técnica.</p>
        </div>

        <form action="provedores/orcamento_cadastrar.php" method="POST" enctype="multipart/form-data" class="modern-form-layout">
            
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            <input type="hidden" name="entidade_id" value="<?= htmlspecialchars($entidade_id) ?>">
            <input type="hidden" name="usuario_id" value="<?= $_SESSION['usuario_id'] ?>">

            <div class="form-grid-layout">
                
                <div class="form-group-modern full-width">
                    <label>Imóvel</label>
                    <select name="imovel_id" class="form-control-modern" required>
                        <option value="" selected disabled>Selecione o imóvel que necessita de reparo...</option>
                        <?php foreach ($meusImoveis as $imovel): ?>
                            <option value="<?= $imovel['id'] ?>"><?= htmlspecialchars($imovel['nome_locacao'])?> - <em><?= $imovel['endereco'] ?></em></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group-modern full-width">
                    <label>Descrição do Problema (Serviços)</label>
                    <textarea name="servicos" class="form-control-modern" rows="4" placeholder="Descreva detalhadamente o que precisa ser reparado..." required></textarea>
                </div>

                <div class="form-group-modern full-width">
                    <label>Observações Adicionais (Opcional)</label>
                    <textarea name="observacoes" class="form-control-modern" rows="2" placeholder="Informações extras, horários de acesso, etc."></textarea>
                </div>

                <div class="form-group-modern">
                    <label>Nível de Urgência</label>
                    <select name="tipo_urgencia" class="form-control-modern" required>
                        <option value="B">Baixa</option>
                        <option value="M">Média</option>
                        <option value="A">Alta</option>
                    </select>
                </div>

                <div class="form-group-modern">
                    <label>Anexo (Imagem ou PDF)</label>
                    <input type="file" name="arquivo_anexo" class="form-control-modern" accept="image/*, application/pdf">
                </div>

            </div>

            <div class="form-actions-zone">
                <a href="painel.php" class="btn-cancel-modern">Cancelar</a>
                <button type="submit" class="btn-submit-modern">
                    <span>Enviar Solicitação</span>
                </button>
            </div>
        </form>
    </div>
</div>

<?php include 'controladores/footer.php'; ?>