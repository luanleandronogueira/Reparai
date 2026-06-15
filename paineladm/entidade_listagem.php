<?php
include 'controladores/header.php';
require_once '../model/Model.php';

$EntidadeModel = new EntidadeModel();
$entidades = $EntidadeModel->listaTodasEntidades();

?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">

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
        <table id="tabela-entidades" class="cloud-data-table display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Razão Social / Nome</th>
                    <th>CNPJ</th>
                    <th>Responsável</th>
                    <th>Contato</th>
                    <th class="text-center">Status</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($entidades)) { 
                    foreach ($entidades as $empresa) { ?>
                        <tr>
                            <td class="font-semibold text-primary-dark">
                                <?= htmlspecialchars($empresa['entidade_nome']) ?>
                            </td>
                            <td class="text-muted text-nowrap">
                                <?= htmlspecialchars($empresa['cnpj']) ?>
                            </td>
                            <td>
                                <?= htmlspecialchars($empresa['responsavel']) ?>
                            </td>
                            <td>
                                <div class="cell-contact-info">
                                    <span class="contact-email"><?= htmlspecialchars($empresa['email']) ?></span>
                                    <span class="contact-phone"><?= htmlspecialchars($empresa['telefone']) ?></span>
                                </div>
                            </td>
                            <td class="text-center">
                                <?php if ($empresa['ativo'] === 'S') { ?>
                                    <span class="badge-status bg-active">Ativo</span>
                                <?php } else { ?>
                                    <span class="badge-status bg-inactive">Inativo</span>
                                <?php } ?>
                            </td>
                            <td class="text-center">
                                <div class="row-actions-container">
                                    <a href="entidade_editar.php?id=<?= $empresa['id'] ?>" class="action-btn btn-edit" title="Editar Informações">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                                        </svg>
                                    </a>

                                    <?php if ($empresa['ativo'] === 'S') { ?>
                                        <a href="provedores/entidade_status_toggle.php?id=<?= $empresa['id'] ?>&status=N" class="action-btn btn-disable" title="Inativar Entidade" onclick="return confirm('Deseja realmente inativar os acessos desta entidade?');">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        </a>
                                    <?php } else { ?>
                                        <a href="provedores/entidade_status_toggle.php?id=<?= $empresa['id'] ?>&status=S" class="action-btn btn-enable-row" title="Ativar Entidade">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
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

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabela-entidades').DataTable({
        "language": {
            "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json"
        },
        "pageLength": 10,
        "order": [[0, "asc"]], // Ordena por padrão pelo nome da entidade (Coluna 0)
        "columnDefs": [
            { "orderable": false, "targets": [4, 5] } // Desativa a ordenação nas colunas de Status e Ações
        ]
    });
});
</script>

<?php
include 'controladores/footer.php';
?>