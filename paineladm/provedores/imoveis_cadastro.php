<?php
session_start();
require_once '../model/Model.php';

// Valida se o método é POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método não permitido.';
    header('Location: ../imoveis_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Recebe os dados por POST
$contents = $_POST;

// Validação de Segurança: Token CSRF
if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../imoveis_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Conferir se os campos obrigatórios estão vazios
if (empty($contents['nome_locacao'])) {
    $mensagem = 'O campo Nome da Locação é obrigatório!';
    header('Location: ../imoveis_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

if (empty($contents['endereco'])) {
    $mensagem = 'O campo Endereço é obrigatório!';
    header('Location: ../imoveis_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

if (empty($contents['entidade'])) {
    $mensagem = 'Você deve selecionar uma Entidade Parceira para vincular este imóvel!';
    header('Location: ../imoveis_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Instancia o modelo de Imóveis
$ImoveisModel = new ImoveisModel();

// Executa a inserção passando o array $contents diretamente
$validacao = $ImoveisModel->inserirImovel($contents);

if ($validacao) {
    $mensagem = 'Imóvel cadastrado com sucesso!';
    header('Location: ../imoveis_listagem.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Erro ao tentar salvar o imóvel no banco de dados. Tente novamente.';
    header('Location: ../imoveis_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}