<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Filtra e valida o ID do orçamento enviado via URL
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    header('Location: orcamento_listagem.php?mensagem_erro=' . urlencode('ID de orçamento inválido.'));
    exit;
}

$OrcamentosModel = new OrcamentosModel();
$orcamento = $OrcamentosModel->buscaOrcamentoPorId($id);

if (!$orcamento) {
    header('Location: orcamento_listagem.php?mensagem_erro=' . urlencode('Orçamento não encontrado.'));
    exit;
}
?>

<div class="full-screen-page-container">
    <div class="form-card-wrapper">
        
        <div class="form-card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h2 class="form-card-title">Decisão de Aprovação do Orçamento #<?= str_pad($orcamento['id'], 5, '0', STR_PAD_LEFT) ?? 'Aguardando' ?></h2>
                <p class="form-card-subtitle">Analise as informações abaixo e defina se o orçamento está aprovado ou recusado.</p>
            </div>
            <div>
                <?php if ($orcamento['aprovado'] === 'S') { ?>
                    <span class="badge-status bg-active" style="padding: 8px 16px; font-size: 14px;">Aprovado (S)</span>
                <?php } elseif ($orcamento['aprovado'] === 'N') { ?>
                    <span class="badge-status bg-inactive" style="padding: 8px 16px; font-size: 14px; background-color: var(--brand-red); color: #fff;">Recusado (N)</span>
                <?php } else { ?>
                    <span class="badge-status bg-inactive" style="padding: 8px 16px; font-size: 14px; background-color: #6c757d;">Aguardando Decisão</span>
                <?php } ?>
            </div>
        </div>

        <form id="form-decisao-orcamento" action="provedores/orcamento_avaliar_enviar.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="id" value="<?= $orcamento['id'] ?>">
            <input type="hidden" id="input-aprovacao" name="aprovacao" value="">

            <div class="form-grid-layout">
                <div class="span-12">
                    <div class="form-group-modern" style="margin-bottom: 15px;">
                        <label>Entidade / Cliente</label>
                        <input type="text" class="form-control-modern" value="<?= htmlspecialchars($orcamento['entidade_nome'] ?? 'Não informada') ?>" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                    </div>

                    <div class="form-group-modern" style="margin-bottom: 15px;">
                        <label>Localização / Prédio</label>
                        <input type="text" class="form-control-modern" value="<?= htmlspecialchars($orcamento['nome_locacao'] ?? 'Não informado') ?>" readonly style="background-color: #f5f5f5; cursor: not-allowed;">
                    </div>

                    <div class="form-group-modern" style="margin-bottom: 15px;">
                        <label>Descrição dos Serviços Mapeados</label>
                        <div style="background-color: #fafafa; border: 1px solid #ddd; padding: 12px; border-radius: var(--radius-sm); font-size: 14px; color: var(--text-dark); white-space: pre-wrap;"><?= htmlspecialchars($orcamento['servicos']) ?></div>
                    </div>

                    <div class="form-group-modern" style="margin-bottom: 15px;">
                        <label>Custo Estimado Original</label>
                        <input type="text" class="form-control-modern" value="R$ <?= number_format((float)($orcamento['custo'] ?? 0), 2, ',', '.') ?>" readonly style="background-color: #f5f5f5; cursor: not-allowed; font-weight: bold;">
                    </div>
                </div>
            </div>

            <div class="form-actions-zone" style="margin-top: 30px; border-top: 1px solid #edf2f7; padding-top: 20px; display: flex; justify-content: flex-end; gap: 12px;">
                
                <a href="orcamento_listagem.php" class="btn-cancel-modern" style="display: inline-flex; align-items: center; justify-content: center; min-width: 140px;">
                    Voltar
                </a>
                
                <button type="button" onclick="definirAprovacao('N')" class="btn-submit-modern" style="background-color: var(--brand-red); color: #fff; min-width: 160px;">
                    Recusar (N)
                </button>

                <button type="button" onclick="definirAprovacao('S')" class="btn-submit-modern" style="background-color: #2e7d32; color: #fff; min-width: 160px;">
                    Aprovar (S)
                </button>
                
            </div>
        </form>
    </div>
</div>

<script>
function definirAprovacao(status) {
    const form = document.getElementById('form-decisao-orcamento');
    const input = document.getElementById('input-aprovacao');
    
    input.value = status;
    
    const textoConfirmacao = status === 'S' ? 'APROVAR' : 'RECUSAR';
    if (confirm(`Deseja marcar este orçamento como ${textoConfirmacao}?`)) {
        form.submit();
    }
}
</script>

<?php include 'controladores/footer.php'; ?>