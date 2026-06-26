<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Instancia o modelo de orçamentos para buscarmos métricas reais na tela inicial
$OrcamentosModel = new OrcamentosModel();
$todosOrcamentos = $OrcamentosModel->listaOrcamentos($_SESSION['usuario_entidade']) ?: [];

// Inicializadores de contadores para os Cards de Métricas
$totalAberto = 0;
$totalAprovado = 0;
$totalRecusado = 0;

foreach ($todosOrcamentos as $o) {
    $status = $o['aprovacao'] ?? 'A';
    if ($status === 'S') {
        $totalAprovado++;
    } elseif ($status === 'N') {
        $totalRecusado++;
    } else {
        $totalAberto++;
    }
}

// Pega apenas os últimos 5 orçamentos para exibir na tabela de monitoramento rápido
$ultimosOrcamentos = array_slice($todosOrcamentos, 0, 5);
?>

<div class="content-page-container table-page-wide" style="padding: 30px; max-width: 1300px; margin: 0 auto;">
    
    <div class="table-header-actions" style="margin-bottom: 35px; border-bottom: 1px solid #edf2f7; padding-bottom: 20px;">
        <div class="header-title-zone">
            <h1 class="form-card-title" style="font-size: 28px; font-family: 'Poppins', sans-serif; color: var(--brand-blue); font-weight: 700;">
                Olá, <?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Usuário') ?>!
            </h1>
            <p class="form-card-subtitle" style="font-size: 15px; color: var(--text-muted); margin-top: 5px;">
                Seja bem-vindo ao painel operacional do **ReparAí**. Aqui está o panorama geral das suas solicitações hoje.
            </p>
        </div>
        
        <a href="orcamento_cadastro.php" class="btn-submit-modern btn-add-new-entity" style="display: inline-flex; align-items: center; gap: 8px; box-shadow: var(--shadow-subtle);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Nova Solicitação</span>
        </a>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 24px; margin-bottom: 40px;">
        
        <div style="background: var(--white); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle); border-left: 5px solid #0284c7; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600; margin-bottom: 8px;">Aguardando Análise</p>
                <h3 style="font-size: 32px; color: var(--text-dark); font-family: 'Poppins', sans-serif; font-weight: 700; line-height: 1;"><?= $totalAberto ?></h3>
            </div>
            <div style="background-color: #e0f2fe; color: #0284c7; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="28" height="28">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        <div style="background: var(--white); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle); border-left: 5px solid #2e7d32; display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600; margin-bottom: 8px;">Orçamentos Aprovados</p>
                <h3 style="font-size: 32px; color: var(--text-dark); font-family: 'Poppins', sans-serif; font-weight: 700; line-height: 1;"><?= $totalAprovado ?></h3>
            </div>
            <div style="background-color: #e8f5e9; color: #2e7d32; padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="28" height="28">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

        <div style="background: var(--white); border-radius: var(--radius-md); padding: 24px; box-shadow: var(--shadow-subtle); border-left: 5px solid var(--brand-red); display: flex; align-items: center; justify-content: space-between;">
            <div>
                <p style="font-size: 13px; text-transform: uppercase; letter-spacing: 0.5px; color: var(--text-muted); font-weight: 600; margin-bottom: 8px;">Pedidos Recusados</p>
                <h3 style="font-size: 32px; color: var(--text-dark); font-family: 'Poppins', sans-serif; font-weight: 700; line-height: 1;"><?= $totalRecusado ?></h3>
            </div>
            <div style="background-color: #fde8e8; color: var(--brand-red); padding: 12px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="28" height="28">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
        </div>

    </div>

    <div style="background: var(--white); border-radius: var(--radius-md); padding: 25px; box-shadow: var(--shadow-md); border: 1px solid #edf2f7;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <div>
                <h3 style="font-size: 18px; font-family: 'Poppins', sans-serif; color: var(--brand-blue); font-weight: 600;">Monitoramento de Solicitações Recentes</h3>
                <p style="font-size: 13px; color: var(--text-muted);">Acompanhe os últimos 5 chamados de orçamentos inseridos na plataforma.</p>
            </div>
            <a href="orcamento_listagem.php" style="font-size: 13px; color: #0284c7; font-weight: 600; text-decoration: none; display: inline-flex; align-items: center; gap: 4px; transition: var(--transition-smooth);">
                <span>Ver listagem completa</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="14" height="14">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>
            </a>
        </div>

        <div class="table-responsive-wrapper">
            <table class="modern-data-table" style="margin-bottom: 0;">
                <thead>
                    <tr>
                        <th style="width: 80px;" class="text-center">ID</th>
                        <th>Imóvel / Prédio</th>
                        <th>Serviço Mapeado</th>
                        <th style="width: 120px;" class="text-center">Urgência</th>
                        <th style="width: 150px;" class="text-center">Status Aprovação</th>
                        <th style="width: 80px;" class="text-center">Ação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($ultimosOrcamentos)) { 
                        foreach ($ultimosOrcamentos as $item) { 
                            $statusAprovacao = $item['aprovacao'] ?? 'A';
                    ?>
                            <tr>
                                <td class="text-center font-semibold">#<?= str_pad($item['id'], 4, '0', STR_PAD_LEFT) ?></td>
                                <td>
                                    <div class="cell-principal-title" style="font-weight: 600; color: var(--text-dark);"><?= htmlspecialchars($item['nome_locacao'] ?? 'Não informado') ?></div>
                                    <div class="cell-sub-title" style="font-size: 12px; color: var(--text-muted);"><?= htmlspecialchars($item['entidade_nome'] ?? '') ?></div>
                                </td>
                                <td>
                                    <div style="max-width: 320px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; font-size: 13px; color: var(--text-dark);" title="<?= htmlspecialchars($item['servicos']) ?>">
                                        <?= htmlspecialchars($item['servicos']) ?>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($item['tipo_urgencia'] === 'A') {
                                        echo '<span class="badge-status" style="background-color: #fde8e8; color: var(--brand-red); font-weight: 600; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 12px;">Alta</span>';
                                    } elseif ($item['tipo_urgencia'] === 'M') {
                                        echo '<span class="badge-status" style="background-color: #fef3c7; color: #b45309; font-weight: 600; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 12px;">Média</span>';
                                    } else {
                                        echo '<span class="badge-status" style="background-color: #e0f2fe; color: #0369a1; font-weight: 600; padding: 4px 8px; border-radius: var(--radius-sm); font-size: 12px;">Baixa</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <?php 
                                    if ($statusAprovacao === 'S') {
                                        echo '<span class="badge-status" style="background-color: #2e7d32; color: #fff; padding: 4px 10px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 500;">Aprovado</span>';
                                    } elseif ($statusAprovacao === 'N') {
                                        echo '<span class="badge-status" style="background-color: var(--brand-red); color: #fff; padding: 4px 10px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 500;">Não Aprovado</span>';
                                    } else {
                                        echo '<span class="badge-status" style="background-color: #0284c7; color: #fff; padding: 4px 10px; border-radius: var(--radius-sm); font-size: 12px; font-weight: 500;">Aberto</span>';
                                    }
                                    ?>
                                </td>
                                <td class="text-center">
                                    <div class="row-actions-container" style="display: flex; justify-content: center;">
                                        <a href="orcamento_avaliar.php?id=<?= $item['id'] ?>" class="action-btn btn-edit" title="Visualizar / Avaliar" style="padding: 6px; border-radius: var(--radius-sm); border: 1px solid #edf2f7; display: inline-flex; align-items: center; justify-content: center; color: var(--brand-blue); transition: var(--transition-smooth);">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.011 9.963 7.178a1.011 1.011 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                    <?php } 
                    } else { ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted" style="padding: 40px; font-size: 14px;">Nenhuma atividade ou orçamento registrado até o momento.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include 'controladores/footer.php'; ?>