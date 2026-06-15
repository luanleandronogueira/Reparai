<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

$ImoveisModel = new ImoveisModel();
$imoveis = $ImoveisModel->listarImoveis();

$EntidadeModel = new EntidadeModel();
?>



<div class="content-page-container table-page-wide">

    <div class="table-header-actions">
        <div class="header-title-zone">
            <h2 class="form-card-title">Imóveis Cadastrados</h2>
            <p class="form-card-subtitle">Gerencie os locais de locação, endereços estruturados e os vínculos com as entidades parceiras.</p>
        </div>

        <a href="imoveis_cadastro.php" class="btn-submit-modern btn-add-new-entity">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            <span>Cadastrar Novo Imóvel</span>
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
        <table id="tabela-imoveis" class="cloud-data-table display responsive nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nome da Locação</th>
                    <th>Endereço</th>
                    <th>Entidade Vinculada</th>
                    <th class="text-center">Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                if (!empty($imoveis)) {
                    foreach ($imoveis as $imovel) { 
                        $entidade = $EntidadeModel->buscaEntidadePorId($imovel['entidade']);
                        $nomeEntidade = $entidade ? $entidade['entidade_nome'] : 'Não Vinculada';
                        ?>
                        <tr>
                            <td><strong>#<?= htmlspecialchars($imovel['id']) ?></strong></td>
                            <td><?= htmlspecialchars($imovel['nome_locacao']) ?></td>
                            <td><?= htmlspecialchars($imovel['endereco']) ?></td>
                            <td>
                                <span class="badge-status badge-active" style="font-weight: 500;">
                                    <?= htmlspecialchars($nomeEntidade) ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="actions-flex-zone">
                                    <a href="imoveis_editar.php?id=<?= $imovel['id'] ?>" class="action-btn btn-edit-row" title="Editar Imóvel">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </a>
                                    
                                    <a href="provedores/imovel_deletar.php?id=<?= $imovel['id'] ?>" class="action-btn btn-disable-row" title="Excluir Imóvel" onclick="return confirm('Deseja realmente remover este imóvel permanentemente?');">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="16" height="16">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-1.816A2.25 2.25 0 0116.741 2.5h-9.482a2.25 2.25 0 01-2.24 2.155V5.625" />
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