<?php
session_start();
require_once '../model/Model.php';

// Proteção contra requisições diretas ou GET
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../orcamento_listagem.php?mensagem_erro=" . urlencode("Método de requisição inválido."));
    exit();
}

// Coleta apenas as duas informações necessárias
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$aprovacao = filter_input(INPUT_POST, 'aprovacao', FILTER_DEFAULT);

if (!$id || !in_array($aprovacao, ['S', 'N'])) {
    header("Location: ../orcamento_listagem.php?mensagem_erro=" . urlencode("Dados inválidos ou insuficientes para salvar a aprovação."));
    exit();
}

$OrcamentosModel = new OrcamentosModel();

// Executa a atualização focada apenas no campo de aprovação
$sucesso = $OrcamentosModel->atualizarApenasAprovacao($id, $aprovacao);

if ($sucesso) {
    $statusTxt = ($aprovacao === 'S') ? "aprovado (S)" : "recusado (N)";
    header("Location: ../orcamento_listagem.php?mensagem_sucesso=" . urlencode("Status de aprovação atualizado para {$statusTxt} com sucesso!"));
} else {
    header("Location: ../orcamento_avaliar.php?id=" . $id . "&mensagem_erro=" . urlencode("Erro ao tentar atualizar o status de aprovação no sistema."));
}
exit();