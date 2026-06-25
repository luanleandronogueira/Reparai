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

$OrcamentosModel = new OrcamentosModel();
$orcamento = $OrcamentosModel->buscaOrcamentoPorId($id);

if (!$orcamento) {
    header('Location: orcamento_listagem.php?mensagem_erro=' . urlencode('Orçamento não encontrado.'));
    exit;
}

?>

<div class="full-screen-page-container">
    <form action="provedores/orcamento_avaliar_enviar.php" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $orcamento['id'] ?>">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
        <input type="hidden" name="responsavel_id" value="<?= $_SESSION['usuario_id'] ?>">

        <div class="grid-12">
            <!-- Cabeçalho Full -->
            <div class="span-12 card-modern" style="margin-bottom: 20px;">
                <h2 style="margin: 0;">Análise de Orçamento #<?= str_pad($orcamento['id'], 5, '0', STR_PAD_LEFT) ?></h2>
            </div>

            <!-- Coluna Esquerda: Detalhes -->
            <div class="span-6 card-modern">
                <h4 style="margin-top:0;">Solicitação</h4>
                <div style="display:flex;justify-content:space-between;gap:15px;margin-bottom:12px;">
                    <div style="flex:1;">
                        <div style="font-size:0.95rem;color:#555;margin-bottom:6px;">Entidade</div>
                        <div style="font-weight:600;color:#222;"><?= htmlspecialchars($orcamento['entidade_nome']) ?></div>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:0.95rem;color:#555;margin-bottom:6px;">Imóvel</div>
                        <div style="font-weight:600;color:#222;"><?= $orcamento['nome_locacao'] ?></div>
                    </div>
                </div>
                <div style="background:#f8f9fb;border:1px solid #e2e5ea;border-radius:8px;padding:14px;margin-bottom:16px;">
                    <div style="font-size:0.95rem;color:#555;margin-bottom:8px;">Serviço</div>
                    <div style="color:#2a2a2a;line-height:1.5;"><?= $orcamento['servicos'] ?></div>
                </div>
                <div style="display:flex;justify-content:space-between;gap:15px;">
                    <div style="flex:1;background:#fff;border:1px solid #e2e5ea;border-radius:8px;padding:12px;">
                        <div style="font-size:0.95rem;color:#555;margin-bottom:6px;">Status Atual</div>
                        <div style="font-weight:600;color:#222;"><?= $orcamento['status_atendimento'] ?></div>
                    </div>
                    <div style="flex:1;background:#fff;border:1px solid #e2e5ea;border-radius:8px;padding:12px;">
                        <div style="font-size:0.95rem;color:#555;margin-bottom:6px;">Urgência</div>
                        <div style="font-weight:600;color:#222;"><?= $orcamento['tipo_urgencia'] == 'A' ? 'ALTA' : 'NORMAL' ?></div>
                    </div>
                    <div style="flex:1;background:#fff;border:1px solid #e2e5ea;border-radius:8px;padding:12px;">
                        <div style="font-size:0.95rem;color:#555;margin-bottom:6px;">Solicitante</div>
                        <div style="font-weight:600;color:#222;"><?= $orcamento['nome_solicitante'] ?></div>
                    </div>
                </div>
            </div>

            <!-- Coluna Direita: Preenchimento Técnico -->
            <div class="span-6 card-modern">
                <h4 style="margin-top:0;">Avaliação Técnica</h4>
                <div class="form-group-modern" style="margin-bottom:15px;">
                    <label>Custo (R$)</label>
                    <input type="text" name="custo" class="form-control-modern currency-mask" required>
                </div>
                <div class="form-group-modern" style="margin-bottom:15px;">
                    <label>Prazo para Execução:</label>
                    <input type="date" name="prazo" class="form-control-modern" required>
                </div>
                <div class="form-group-modern" style="margin-bottom:15px;">
                    <label>Observação do Orçamento</label>
                    <textarea name="observacao_orcamento" class="form-control-modern" rows="3"><?= htmlspecialchars($orcamento['observacao_orcamento']) ?></textarea>
                </div>
                <div class="form-group-modern">
                    <label>Anexar Arquivo (PDF/Imagem)</label>
                    <input type="file" name="arquivo_anexo" class="form-control-modern" accept=".pdf, .jpg, .png">
                </div>
            </div>

            <div class="span-12" style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="orcamento_listagem.php" class="btn-submit-modern" style="padding: 12px 20px;">Cancelar</a>
                <button type="submit" class="btn-submit-modern" style="padding: 12px 20px;">Finalizar Avaliação</button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var custoInput = document.querySelector('input[name="custo"]');
        if (!custoInput) return;

        function formatCurrency(value) {
            var numeric = value.replace(/\D/g, '');
            if (numeric === '') return '';
            if (numeric.length === 1) numeric = '0' + numeric;
            var cents = numeric.slice(-2);
            var integer = numeric.slice(0, -2);
            integer = integer.replace(/^0+/, '');
            if (integer === '') integer = '0';
            integer = integer.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            return 'R$ ' + integer + ',' + cents;
        }

        function unformatCurrency(value) {
            return value.replace(/\D/g, '');
        }

        custoInput.addEventListener('input', function(e) {
            var formatted = formatCurrency(e.target.value);
            e.target.value = formatted;
            e.target.setSelectionRange(formatted.length, formatted.length);
        });

        var form = custoInput.closest('form');
        if (form) {
            form.addEventListener('submit', function() {
                var raw = unformatCurrency(custoInput.value);
                custoInput.value = raw === '' ? '' : (parseInt(raw, 10) / 100).toFixed(2);
            });
        }
    });
</script>

<?php include 'controladores/footer.php'; ?>