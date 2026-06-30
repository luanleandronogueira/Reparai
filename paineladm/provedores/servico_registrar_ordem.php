<?php
session_start();
require_once '../model/Model.php';

// 1. Valida se a requisição foi feita pelo formulário
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método de requisição não permitido.';
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// 2. Validação de Segurança CSRF
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Token de segurança inválido ou expirado. Tente novamente.';
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Captura e sanitiza as entradas com os nomes corrigidos do banco de dados
$orcamento_id = filter_input(INPUT_POST, 'orcamento_id', FILTER_VALIDATE_INT);
$prestador_id = filter_input(INPUT_POST, 'prestador_id', FILTER_VALIDATE_INT);
$valor_contratado = filter_input(INPUT_POST, 'valor_contratado', FILTER_VALIDATE_FLOAT);

$prazo_acordado = filter_input(INPUT_POST, 'prazo_acordado', FILTER_DEFAULT);
$data_inicio_real = filter_input(INPUT_POST, 'data_inicio_real', FILTER_DEFAULT);

$status_execucao = filter_input(INPUT_POST, 'status_execucao', FILTER_DEFAULT);
$status_pagamento = filter_input(INPUT_POST, 'status_pagamento', FILTER_DEFAULT);
$forma_pagamento = filter_input(INPUT_POST, 'forma_pagamento', FILTER_DEFAULT);
$observacoes_finais = filter_input(INPUT_POST, 'observacoes_finais', FILTER_DEFAULT);

// Validação de consistência obrigatória (NOT NULL do banco)
if (!$orcamento_id || !$prestador_id || !$valor_contratado || !$prazo_acordado) {
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode('Falha ao processar os dados. Preencha todos os campos obrigatórios!'));
    exit;
}

// Prepara o mapa estruturado correto
$dadosServico = [
    'orcamento_id'       => $orcamento_id,
    'prestador_id'       => $prestador_id,
    'valor_contratado'   => $valor_contratado,
    'prazo_acordado'     => $prazo_acordado,
    'data_inicio_real'   => $data_inicio_real,
    'status_execucao'    => $status_execucao,
    'status_pagamento'   => $status_pagamento,
    'forma_pagamento'    => $forma_pagamento,
    'observacoes_finais' => $observacoes_finais
];

// Instancia o Model e executa a operação
$PrestacaoServicoModel = new PrestacaoServicoModel();
$sucesso = $PrestacaoServicoModel->cadastrarPrestacaoServico($dadosServico);

if ($sucesso) {
    header('Location: ../servicos.php?mensagem_sucesso=' . urlencode('Ordem de Serviço iniciada e registrada com sucesso!'));
    exit;
} else {
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode('Erro interno ao tentar salvar a ordem de serviço no banco de dados.'));
    exit;
}