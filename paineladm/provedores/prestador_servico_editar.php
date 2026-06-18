<?php
session_start();
require_once '../model/Model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../prestadores_listagem.php?mensagem_erro=' . urlencode('Método inválido.'));
    exit();
}

$contents = $_POST;

if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../prestadores_listagem.php?mensagem_erro=' . urlencode('Token inválido.'));
    exit();
}

// Sanitização e Validação seguindo o padrão
$dados = [
    'id'                 => $contents['id'],
    'nome'               => trim(htmlspecialchars(strtoupper($contents['nome']))),
    'contato'            => trim(htmlspecialchars($contents['contato'])),
    'cidade'             => trim(htmlspecialchars(strtoupper($contents['cidade']))),
    'servicos_prestados' => trim(htmlspecialchars($contents['servicos_prestados']))
];

$PrestadoresServicoModel = new PrestadoresServicoModel();
$validacao = $PrestadoresServicoModel->atualizarPrestador($dados);

if ($validacao) {
    header('Location: ../prestador_servico_listagem.php?mensagem_sucesso=' . urlencode('Prestador atualizado com sucesso!'));
} else {
    header('Location: ../prestador_servico_editar.php?id=' . $dados['id'] . '&mensagem_erro=' . urlencode('Erro ao atualizar registro.'));
}
exit();