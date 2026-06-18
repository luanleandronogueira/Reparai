<?php
session_start();

require_once '../model/Model.php';

// Valida se o método HTTP utilizado é o POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método não permitido.';
    header('Location: ../prestadores_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Recebe os dados da requisição
$contents = $_POST;

// Validação de Segurança: Token CSRF
if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Validação individual de campos obrigatórios (evita lixo no banco de dados)
if (empty(trim($contents['nome']))) {
    $mensagem = 'O campo Nome do Prestador / Empresa é obrigatório!';
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

if (empty(trim($contents['contato']))) {
    $mensagem = 'O campo Telefone / Contato é obrigatório!';
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

if (empty(trim($contents['cidade']))) {
    $mensagem = 'O campo Cidade de Atuação é obrigatório!';
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

if (empty(trim($contents['servicos_prestados']))) {
    $mensagem = 'Você deve adicionar ao menos um Serviço ao portfólio deste prestador!';
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Sanitização dos dados antes de enviar para a Model
$dados = [
    'nome'               => trim(htmlspecialchars(strtoupper($contents['nome']))),
    'contato'            => trim(htmlspecialchars($contents['contato'])),
    'cidade'             => trim(htmlspecialchars(strtoupper($contents['cidade']))),
    'servicos_prestados' => trim(htmlspecialchars($contents['servicos_prestados']))
];

// Instancia a Model de Prestadores de Serviço
$PrestadoresServicoModel = new PrestadoresServicoModel();

// Executa a persistência de dados
$validacao = $PrestadoresServicoModel->inserirPrestador($dados);

// Redirecionamento condicional baseado no sucesso da query
if ($validacao) {
    $mensagem = 'Prestador de serviço cadastrado com sucesso!';
    // Envia de volta para a listagem principal exibindo o banner verde
    header('Location: ../prestador_servico_listagem.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Erro interno ao tentar salvar o prestador no banco de dados. Verifique os relatórios de erro.';
    // Envia de volta ao formulário mantendo o banner vermelho
    header('Location: ../prestador_servico_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}