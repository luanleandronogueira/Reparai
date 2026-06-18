<?php
include 'controladores/header.php';
require_once '../model/Model.php';

$PrestadoresServicoModel = new PrestadoresServicoModel();
$prestadores_servico = $PrestadoresServicoModel->listarPrestadores();

?>

<div class="content-page-container table-page-wide">
    
    <div class="table-header-actions">
        <div class="header-title-zone">
            <h2 class="form-card-title">Prestadores de Serviços</h2>
            <p class="form-card-subtitle">Gerencie seus Prestadores.</p>
        </div>
        
        <a href="prestador_servico_cadastro.php" class="btn-submit-modern btn-add-new-entity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Cadastrar Prestador Serviço</span>
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
        <table id="tabela-prestador-servico" class="cloud-data-table display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Prestador</th>
                    <th>Contato</th>
                    <th>Cidade</th>
                    <th>Serviços Prestados</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($prestadores_servico)) { 
                    foreach ($prestadores_servico as $prestador) { ?>
                        <tr>
                            <td class="font-semibold text-primary-dark">
                                #<?= htmlspecialchars($prestador['id']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($prestador['nome']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($prestador['contato']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($prestador['cidade']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($prestador['servicos_prestados']) ?>
                            </td>
                            <td class="text-center">
                                <div class="row-actions-container">
                                    <a href="prestador_servico_editar.php?id=<?= htmlspecialchars($prestador['id']) ?>" class="action-btn btn-edit" title="Editar Informações">
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