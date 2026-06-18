<?php
session_start();
require_once '../model/Model.php';

// 1. Valida se a requisição foi feita pelo formulário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método de requisição não permitido.';
    header('Location: ../servico_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 2. Validação de Segurança CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../servico_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 3. Recolha e sanitização dos dados recebidos
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$tipo = filter_input(INPUT_POST, 'tipo', FILTER_DEFAULT);

// 4. Verificação de campos obrigatórios
if (!$id || empty(trim($tipo))) {
    $mensagem = 'A descrição do serviço não pode ficar vazia.';
    header('Location: ../servico_editar.php?id=' . $id . '&mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Monta o array que será enviado para a Model
$dados = [
    'id' => $id,
    'tipo' => strtoupper(trim(htmlspecialchars($tipo)))
];

// 5. Instanciação e persistência
$ServicosModel = new ServicosModel();

if ($ServicosModel->atualizarServico($dados)) {
    $mensagem = 'Serviço atualizado com sucesso!';
    header('Location: ../servico_tipo.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Ocorreu um erro crítico ao atualizar os dados. Verifique os logs.';
    header('Location: ../servico_editar.php?id=' . $id . '&mensagem_erro=' . urlencode($mensagem));
    exit();
}