<?php
session_start();
require_once '../model/Model.php';

// Limita o número de requisições para evitar abusos ou scripts maliciosos de spam no cadastro
$rateLimit = new RateLimitModel();
$limiter = $rateLimit->getLimiter();
$key = 'entity_registration:' . $_SERVER['REMOTE_ADDR']; // Chave de segurança baseada no IP do cliente

// Verificar se o IP já está bloqueado por excesso de tentativas (Limite de 5 tentativas)
if ($limiter->tooManyAttempts($key, 5)) {
    $seconds = $limiter->availableIn($key);
    $mensagem = "Muitas tentativas de cadastro detectadas. Tente novamente em {$seconds} segundos.";
    header('Location: ../entidade_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Valida se o método de envio é estritamente POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $mensagem = 'Método de requisição não permitido.';
    header('Location: ../entidade_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Valida o Token CSRF para mitigar ataques de falsificação de requisição cross-site
if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    $mensagem = 'Erro de validação de segurança (CSRF Token Inválido ou Expirado).';
    header('Location: ../entidade_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

// Recebe os dados enviados pelo formulário
$contents = filter_input_array(INPUT_POST, FILTER_DEFAULT);

// Validação de preenchimento dos campos essenciais obrigatórios
if (empty($contents['entidade_nome']) || empty($contents['cnpj']) || empty($contents['responsavel']) || empty($contents['email'])) {
    // FALHA: Incrementa o contador de erros do Rate Limit
    $limiter->hit($key, 60);
    
    $mensagem = 'Por favor, preencha todos os campos obrigatórios para prosseguir.';
    header('Location: ../entidade_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}

$EntidadeModel = new EntidadeModel();

// Tenta executar a inserção dos dados na tabela 'entidade'
$validacao = $EntidadeModel->inserirEntidade($contents);

if ($validacao) {
    // SUCESSO: Limpa o histórico de tentativas e bloqueios para este IP
    $limiter->clear($key);
    
    $mensagem = 'Entidade cadastrada com sucesso!';
    header('Location: ../entidade_cadastro.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    // FALHA: Caso ocorra um erro de banco ou infraestrutura, incrementa o limite por precaução
    $limiter->hit($key, 60);
    
    $mensagem = 'Erro interno ao cadastrar a entidade. Por favor, tente novamente mais tarde.';
    header('Location: ../entidade_cadastro.php?mensagem_erro=' . urlencode($mensagem));
    exit();
}