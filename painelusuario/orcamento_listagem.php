<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Instancia a Classe
$OrcamentosModel = new OrcamentosModel();
$orcamentos = $OrcamentosModel->listaOrcamentos($_SESSION['usuario_entidade']);

?>

<div class="content-page-container table-page-wide">
    
    <div class="table-header-actions">
        <div class="header-title-zone">
            <h2 class="form-card-title">Entidades Parceiras</h2>
            <p class="form-card-subtitle">Gerencie as unidades institucionais, credenciais de contato e permissões de acesso.</p>
        </div>
        
        <a href="entidade_cadastro.php" class="btn-submit-modern btn-add-new-entity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Cadastrar Nova Entidade</span>
        </a>
    </div>

    <?php if (isset($_GET['mensagem_sucesso']) && !empty($_GET['mensagem_sucesso'])) { ?>
        <div class="alert-success-modern">
            <?= htmlspecialchars($_GET['mensagem_sucesso']) ?>
        </div>
    <?php } ?>
    <?php if (isset($_GET['mensagem_erro']) && !empty($_GET['mensagem_erro'])) { ?>
        <div class="alert-danger-modern">
            <?= htmlspecialchars($_GET['mensagem_erro']) ?>
        </div>
    <?php } ?>

    <div class="cloud-table-wrapper" style="padding: 20px;">
        <table id="tabela-orcamentos" class="cloud-data-table display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Locação</th>
                    <th>Serviço</th>
                    <th>Status</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($orcamentos)) { 
                    foreach ($orcamentos as $empresa) { ?>
                        <tr>
                            <td class="font-semibold text-primary-dark">
                                <?= htmlspecialchars($empresa['id']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($empresa['nome_locacao']) ?>
                            </td>
                            <td>
                                <?= $empresa['servicos'] ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($empresa['status_atendimento']) ?>
                            </td>
                            <td class="text-center">
                                <?php
                                // Mostrar prioridade/gravidade quando disponível: B => Baixa, M => Média, U => URGENTE
                                if (!empty($empresa['tipo_urgencia'])) {
                                    $map = [
                                        'B' => ['label' => 'Baixa', 'class' => 'bg-low'],
                                        'M' => ['label' => 'Média', 'class' => 'bg-medium'],
                                        'A' => ['label' => 'URGENTE', 'class' => 'bg-urgent'],
                                    ];
                                    $s = strtoupper($empresa['tipo_urgencia']);
                                    if (isset($map[$s])) {
                                        $info = $map[$s];
                                        echo "<span class=\"badge-status {$info['class']}\">" . htmlspecialchars($info['label']) . "</span>";
                                    } else {
                                        echo "<span class=\"badge-status bg-unknown\">" . htmlspecialchars($empresa['status']) . "</span>";
                                    }
                                } else {
                                    if ($empresa['status_atendimento']) {
                                        echo '<span class="badge-status bg-active">Ativo</span>';
                                    } else {
                                        echo '<span class="badge-status bg-inactive">Inativo</span>';
                                    }
                                }
                                ?>
                            </td>
                            <td class="text-center">
                                <div class="row-actions-container">
                                    <a href="entidade_editar.php?id=<?= $empresa['id'] ?>" class="action-btn btn-edit" title="Editar Informações">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>

                                   
                                </div>
                            </td>
                        </tr>
                    <?php } 
                } ?>
            </tbody>
        </table>
    </div>

</div>


<?php
include 'controladores/footer.php';

?>