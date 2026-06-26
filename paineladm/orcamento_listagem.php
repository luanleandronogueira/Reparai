<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Instancia a Classe e busca a lista completa com os JOINs necessários
$OrcamentosModel = new OrcamentosModel();
$orcamentos = $OrcamentosModel->listaTodosOrcamentosCompleto();

?>

<div class="content-page-container table-page-wide">
    <div class="table-header-actions">
        <div class="header-title-zone">
            <h2 class="form-card-title">Orçamentos Solicitados</h2>
            <p class="form-card-subtitle">Gerencie as unidades institucionais, credenciais de contato e permissões de acesso.</p>
        </div>

        <a href="orcamento_cadastro.php" class="btn-submit-modern btn-add-new-entity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Cadastrar Novo Orçamento</span>
        </a>
    </div>

    <?php if (isset($_GET['mensagem_sucesso']) && !empty($_GET['mensagem_sucesso'])) { ?>
        <div class="alert-success-modern">
            <?= $_GET['mensagem_sucesso'] ?>
        </div>
    <?php } ?>
    <?php if (isset($_GET['mensagem_erro']) && !empty($_GET['mensagem_erro'])) { ?>
        <div class="alert-danger-modern">
            <?= $_GET['mensagem_erro'] ?>
        </div>
    <?php } ?>

    <div class="cloud-table-wrapper" style="padding: 20px;">
        <table id="tabela-orcamentos" class="cloud-data-table display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th style="width: 60px;">ID</th>
                    <th>Imóvel / Localização</th>
                    <th>Serviços Solicitados</th>
                    <th>Urgência</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Aprovação</th>
                    <th class="text-center">Anexo</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orcamentos)) {
                    foreach ($orcamentos as $item) { ?>
                        <tr>
                            <td class="font-semibold text-primary-dark">
                                #<?= str_pad($item['id'], 4, '0', STR_PAD_LEFT) ?>
                            </td>

                            <td class="font-semibold text-primary-dark">
                                <?= $item['nome_locacao'] ?? 'Não informado' ?>
                            </td>

                            <td class="text-muted">
                                <?= $item['servicos'] ?? '' ?>
                            </td>

                            <td>
                                <?php if ($item['tipo_urgencia'] === 'A') { ?>
                                    <span class="badge-status bg-inactive" style="background-color: #dc3545; color: #fff;">Alta</span>
                                <?php } elseif ($item['tipo_urgencia'] === 'B') { ?>
                                    <span class="badge-status bg-inactive">Baixa</span>
                                <?php } else { ?>
                                    <span class="badge-status bg-active" style="background-color: #6c757d; color: #fff;">Média</span>
                                <?php } ?>
                            </td>

                            <td class="text-center">
                                <?php if ($item['status_atendimento'] === 'ORÇAMENTO ENVIADO') { ?>
                                    <span class="badge-status bg-active">Enviado</span>
                                <?php } else { ?>
                                    <span class="badge-status bg-inactive">Pendente</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <?php 
                                // Captura o valor do campo aprovacao (padrão 'A' se vazio)
                                $statusAprovacao = !empty($item['aprovado']) ? $item['aprovado'] : 'A';
                                
                                if ($statusAprovacao === 'S') {
                                    echo '<span class="badge-status bg-active" style="background-color: #2e7d32; color: #fff;">Aprovado</span>';
                                } elseif ($statusAprovacao === 'N') {
                                    echo '<span class="badge-status bg-inactive" style="background-color: var(--brand-red); color: #fff;"><small>Não Aprovado</small></span>';
                                } else {
                                    echo '<span class="badge-status" style="background-color: #0284c7; color: #fff;">Aberto</span>';
                                }
                                ?>
                            </td>

                            <td class="text-center">
                                <?php if ($item['status_atendimento'] === 'ORÇAMENTO ENVIADO') { ?>
                                    <a href="orcamento_pdf.php?id=<?= $item['id'] ?>" target="_blank" class="action-btn" title="Visualizar PDF do Orçamento" style="color: #brand-red; display: inline-flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </a>
                                <?php } else { ?>
                                    <span class="text-muted" title="Disponível apenas após o envio" style="opacity: 0.35; cursor: not-allowed; display: inline-flex; align-items: center; justify-content: center;">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                        </svg>
                                    </span>
                                <?php } ?>
                            </td>

                            <td class="text-center">
                                <div class="row-actions-container">
                                    <a href="orcamento_avaliar.php?id=<?= $item['id'] ?>" class="action-btn btn-edit" title="Ver Detalhes / Avaliar">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.011 9.963 7.178a1.011 1.011 0 010 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </a>

                                    <?php if ($statusAprovacao === 'S') { ?>
                                        <a href="servico_gerar.php?id=<?= $item['id'] ?>" class="action-btn" title="Iniciar Serviço" style="background-color: #2e7d32; color: #fff; margin-left: 4px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A1.5 1.5 0 0019.8 19.8l-5.83-5.83m-2.55 1.2a4.49 4.49 0 01-6.36-6.36m5.1-1.35A4.49 4.49 0 0116.05 6.3M11.42 9.42l4.63-4.63M12 3v1.5M12 19.5V21M3 12h1.5M19.5 12H21m-2.1-6.9l-1.05 1.05M6.6 17.4l-1.05 1.05m0-11.4l1.05 1.05m10.8 10.8l1.05-1.05" />
                                            </svg>
                                        </a>
                                    <?php } ?>
                                </div>
                            </td>
                        </tr>
                <?php }
                } ?>
            </tbody>
        </table>
    </div>

</div>

<?php include 'controladores/footer.php'; ?>