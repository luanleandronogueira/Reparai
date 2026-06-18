<?php
session_start();
require_once '../model/Model.php';

// 1. Validação do método HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método não permitido.';
    header('Location: ../servico_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 2. Recebe os dados por POST (Padrão JL)
$contents = $_POST;

// 3. Validação de Segurança CSRF
if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../servico_cadastrar.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 4. Validação de campos obrigatórios
if (empty(trim($contents['tipo']))) {
    $mensagem = 'O campo Tipo/Descrição do Serviço é obrigatório!';
    header('Location: ../servico_cadastrar.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Sanitiza a entrada principal
$dados = [
    'tipo' => strtoupper(trim(htmlspecialchars($contents['tipo'])))
];

// 5. Instanciação e Execução na Base de Dados
$ServicosModel = new ServicosModel();

if ($ServicosModel->inserirServico($dados)) {
    $mensagem = 'Serviço cadastrado com sucesso!';
    // Redireciona para a listagem após inserção com sucesso
    header('Location: ../servico_tipo.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Erro interno ao tentar salvar o serviço. Verifique os logs.';
    header('Location: ../servico_cadastrar.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}