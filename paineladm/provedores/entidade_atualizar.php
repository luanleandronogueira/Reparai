<?php
session_start();
require_once '../model/Model.php';

// 1. Valida se a requisição foi submetida pelo método POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método de requisição não permitido.';
    header('Location: ../entidade_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 2. Validação de Segurança: Token CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../entidade_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 3. Recolha e sanitização básica dos dados recebidos
$dados = [
    'id'            => filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT),
    'entidade_nome' => strtoupper(filter_input(INPUT_POST, 'entidade_nome', FILTER_DEFAULT)),
    'cnpj'          => filter_input(INPUT_POST, 'cnpj', FILTER_DEFAULT),
    'responsavel'   => filter_input(INPUT_POST, 'responsavel', FILTER_DEFAULT),
    'email'         => filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL),
    'telefone'      => filter_input(INPUT_POST, 'telefone', FILTER_DEFAULT),
    'ativo'         => filter_input(INPUT_POST, 'ativo', FILTER_DEFAULT)
];

// 4. Verificação de consistência dos campos obrigatórios
if (!$dados['id'] || empty($dados['entidade_nome']) || empty($dados['cnpj']) || empty($dados['responsavel']) || !$dados['email'] || empty($dados['telefone']) || empty($dados['ativo'])) {
    $mensagem = 'Por favor, preencha todos os campos obrigatórios corretamente.';
    // Retorna para a página de edição preservando o ID para não quebrar o fluxo
    header('Location: ../entidade_editar.php?id=' . $dados['id'] . '&&mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Limpeza de caracteres não numéricos para o CNPJ e Telefone (Mantém coerência com máscaras de inserção)
$dados['cnpj'] = preg_replace('/\D/', '', $dados['cnpj']);
$dados['telefone'] = preg_replace('/\D/', '', $dados['telefone']);

// 5. Instanciação do Modelo e Execução da Persistência
$EntidadeModel = new EntidadeModel();

// Valida se o ID existe de facto no banco antes de tentar atualizar
$entidadeExistente = $EntidadeModel->buscaEntidadePorId($dados['id']);
if (!$entidadeExistente) {
    $mensagem = 'A entidade que tenta editar não foi localizada no sistema.';
    header('Location: ../entidade_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Executa a query de atualização mapeada na EntidadeModel
$sucesso = $EntidadeModel->atualizarEntidade($dados);

if ($sucesso) {
    $mensagem = 'Entidade atualizada com sucesso!';
    header('Location: ../entidade_listagem.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Erro crítico ao atualizar os dados no banco de dados. Verifique os logs.';
    header('Location: ../entidade_editar.php?id=' . $dados['id'] . '&&mensagem_erro=' . urlencode($mensagem));
    exit();
}