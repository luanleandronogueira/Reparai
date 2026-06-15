<?php
session_start();
require_once '../model/Model.php';

// Verifica se a requisição foi submetida pelo método correto
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../imoveis_listagem.php");
    exit();
}

// 1. Validação do Token CSRF para mitigar ataques cross-site
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = "Falha na validação de segurança (CSRF Inválido). Tente novamente.";
    header("Location: ../imoveis_listagem.php?mensagem_erro=" . urlencode($mensagem));
    exit();
}

// 2. Coleta e sanitização estruturada das variáveis de entrada
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$nome_locacao = filter_input(INPUT_POST, 'nome_locacao', FILTER_DEFAULT);
$endereco = filter_input(INPUT_POST, 'endereco', FILTER_DEFAULT);
$entidade = filter_input(INPUT_POST, 'entidade', FILTER_VALIDATE_INT);

// 3. Validação de preenchimento obrigatório dos campos do formulário
if (!$id || empty(trim($nome_locacao)) || empty(trim($endereco)) || !$entidade) {
    $mensagem = "Todos os campos estruturais do imóvel precisam ser preenchidos corretamente.";
    header("Location: ../imoveis_editar.php?id=" . $id . "&mensagem_erro=" . urlencode($mensagem));
    exit();
}

// Prepara o array associativo conforme esperado pela assinatura de atualizarImovel
$dadosImovel = [
    'id' => $id,
    'nome_locacao' => trim($nome_locacao),
    'endereco' => trim($endereco),
    'entidade' => $entidade
];

$ImoveisModel = new ImoveisModel();

// 4. Execução da atualização de persistência no Banco de Dados
if ($ImoveisModel->atualizarImovel($dadosImovel)) {
    $mensagem = "O registro do imóvel foi atualizado com sucesso e as credenciais foram sincronizadas.";
    header("Location: ../imoveis_listagem.php?mensagem_sucesso=" . urlencode($mensagem));
    exit();
} else {
    $mensagem = "Ocorreu um erro interno ao processar a atualização no banco de dados. Verifique o relatório de erros.";
    header("Location: ../imoveis_editar.php?id=" . $id . "&mensagem_erro=" . urlencode($mensagem));
    exit();
}