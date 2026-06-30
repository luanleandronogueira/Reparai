<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Filtra e valida o ID enviado via GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<div style='padding: 20px; max-width: 1200px; margin: 20px auto; background: #fde8e8; color: var(--brand-red); border-radius: var(--radius-sm); font-weight: 600;'>ID de orçamento inválido ou não informado. <a href='orcamento_listagem.php' style='color: inherit; margin-left: 10px;'>Voltar</a></div>";
    include_once("controladores/footer.php");
    exit;
}

// Instancia e busca os dados completos do orçamento aprovado
$OrcamentosModel = new OrcamentosModel();
$orcamento = $OrcamentosModel->buscaOrcamentoPorId($id);

if (!$orcamento) {
    echo "<div style='padding: 20px; max-width: 1200px; margin: 20px auto; background: #fde8e8; color: var(--brand-red); border-radius: var(--radius-sm); font-weight: 600;'>Orçamento não localizado no sistema. <a href='orcamento_listagem.php' style='color: inherit; margin-left: 10px;'>Voltar</a></div>";
    include_once("controladores/footer.php");
    exit;
}

// Inicializa o Prestador de Serviço para listar no campo de seleção
$PrestadoresModel = new PrestadoresServicoModel();
$prestadores = $PrestadoresModel->listarPrestadores() ?: []; 
?>

<div class="order-page-fullscreen">
    <div class="order-panel-box">
        
        <div class="order-panel-header">
            <h2 class="order-panel-title">Gerar Ordem de Serviço</h2>
            <p class="order-panel-subtitle">Confirme as informações estruturais do orçamento aprovado para iniciar a execução da atividade.</p>
        </div>

        <form action="provedores/servico_registrar_ordem.php" method="POST" class="modern-form-layout">
            
            <input type="hidden" name="orcamento_id" value="<?= htmlspecialchars($orcamento['id']) ?>">
            <input type="hidden" name="entidade_id" value="<?= htmlspecialchars($orcamento['entidade_id']) ?>">
            <input type="hidden" name="imovel_id" value="<?= htmlspecialchars($orcamento['imovel_id']) ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">
            
            <input type="hidden" name="valor_contratado" value="<?= htmlspecialchars($orcamento['custo'] ?? '0.00') ?>">

            <div class="order-info-section">
                <h3 class="order-section-title">Detalhamento Técnico do Orçamento #<?= str_pad($orcamento['id'], 4, '0', STR_PAD_LEFT) ?></h3>
                
                <div class="order-meta-grid">
                    <div class="order-meta-item">
                        <label>Imóvel / Prédio Alvo</label>
                        <div><?= htmlspecialchars($orcamento['nome_locacao'] ?? 'Não informado') ?></div>
                    </div>
                    <div class="order-meta-item">
                        <label>Valor Contratado Base</label>
                        <div style="color: #2e7d32;">R$ <?= number_format((float)($orcamento['custo'] ?? 0), 2, ',', '.') ?></div>
                    </div>
                    <div class="order-meta-item">
                        <label>Prazo Estimado Original</label>
                        <div><?= !empty($orcamento['prazo']) ? htmlspecialchars($orcamento['prazo']) : 'Não informado' ?></div>
                    </div>
                </div>

                <div class="order-meta-item" style="margin-top: 20px;">
                    <label>Especificação dos Serviços Solicitados</label>
                    <div class="order-desc-box">
                        <?= nl2br(htmlspecialchars($orcamento['servicos'])) ?>
                    </div>
                </div>
            </div>

            <div class="form-section-divider">Alocação e Cronograma de Execução</div>

            <div class="form-grid-layout">
                
                <div class="form-group-modern">
                    <label for="prestador_id">Prestador de Serviço Alocado</label>
                    <select name="prestador_id" id="prestador_id" class="form-control-modern" required>
                        <option value="">-- Escolha o profissional responsável --</option>
                        <?php foreach ($prestadores as $p) { ?>
                            <option value="<?= $p['id'] ?>">
                                <?= htmlspecialchars($p['nome_prestador'] ?? $p['nome'] ?? '') ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>

                <div class="form-group-modern">
                    <label for="data_inicio_real">Data de Início Efetivo</label>
                    <input type="date" name="data_inicio_real" id="data_inicio_real" class="form-control-modern" value="<?= date('Y-m-d') ?>">
                </div>

                <div class="form-group-modern">
                    <label for="prazo_acordado">Prazo Acordado (Data Limite)</label>
                    <input type="date" name="prazo_acordado" id="prazo_acordado" class="form-control-modern" required>
                </div>

                <div class="form-group-modern">
                    <label for="status_execucao">Status Inicial de Execução</label>
                    <select name="status_execucao" id="status_execucao" class="form-control-modern" required>
                        <option value="AGUARDANDO INÍCIO" selected>AGUARDANDO INÍCIO</option>
                        <option value="EM ANDAMENTO">EM ANDAMENTO</option>
                        <option value="PARALISADO">PARALISADO</option>
                        <option value="CONCLUÍDO">CONCLUÍDO</option>
                    </select>
                </div>

            </div>

            <div class="form-section-divider">Planejamento e Controle Financeiro</div>

            <div class="form-grid-layout">

                <div class="form-group-modern">
                    <label for="forma_pagamento">Forma de Pagamento Inicial</label>
                    <select name="forma_pagamento" id="forma_pagamento" class="form-control-modern">
                        <option value="">-- Selecione uma opção --</option>
                        <option value="PIX">PIX</option>
                        <option value="Boleto Bancário">Boleto Bancário</option>
                        <option value="Transferência">Transferência Bancária</option>
                        <option value="Dinheiro">Dinheiro</option>
                    </select>
                </div>

                <div class="form-group-modern">
                    <label for="status_pagamento">Status do Pagamento</label>
                    <select name="status_pagamento" id="status_pagamento" class="form-control-modern" required>
                        <option value="PENDENTE" selected>PENDENTE</option>
                        <option value="PAGO PARCIALMENTE">PAGO PARCIALMENTE</option>
                        <option value="PAGO">PAGO</option>
                        <option value="CANCELADO">CANCELADO</option>
                    </select>
                </div>

                <div class="form-group-modern full-width">
                    <label for="observacoes_finais">Instruções Iniciais e Observações de Campo</label>
                    <textarea name="observacoes_finais" id="observacoes_finais" class="form-control-modern" rows="3" placeholder="Insira aqui as observações iniciais, restrições de horários ou direcionamentos específicos para o prestador em campo..."></textarea>
                </div>

            </div>

            <div class="form-actions-zone" style="margin-top: 30px;">
                <a href="orcamento_listagem.php" class="btn-cancel-modern">Voltar</a>
                <button type="submit" class="btn-submit-modern" style="background-color: #2e7d32;">
                    <span>Criar Ordem de Serviço</span>
                </button>
            </div>
        </form>

    </div>
</div>

<?php include 'controladores/footer.php'; ?>