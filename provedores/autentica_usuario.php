<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once '../model/Model.php'; 
include_once '../model/LoginModel.php';
require_once '../model/RateLimitModel.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../login.php?mensagem_erro=' . urlencode('Método não permitido.'));
    exit();
}

if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../login.php?mensagem_erro=' . urlencode('Erro de validação CSRF.'));
    exit();
}

$rateLimit = new RateLimitModel();
$limiter = $rateLimit->getLimiter();
$key = 'login_attempts:' . $_SERVER['REMOTE_ADDR'];

if ($limiter->tooManyAttempts($key, 5)) {
    $seconds = $limiter->availableIn($key);
    header('Location: ../login.php?mensagem_erro=' . urlencode("Muitas tentativas. Tente novamente em {$seconds} segundos."));
    exit();
}

// inserir aqui o google recaptcha


$email = trim(filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL));
$senha = trim(filter_input(INPUT_POST, 'senha', FILTER_DEFAULT));

if (!$email || !$senha) {
    header('Location: ../login.php?mensagem_erro=' . urlencode('Preencha os campos corretamente.'));
    exit();
}

$loginModel = new LoginModel();
$usuario_dados = $loginModel->autenticarUsuario($email, $senha);

if ($usuario_dados) {
    if ($usuario_dados['ativo'] !== 'S') {
        $loginModel->finalizaSessao();
        header('Location: ../login.php?mensagem_erro=' . urlencode('Usuário Inativo.'));
        exit();
    }

    $limiter->clear($key);
    $loginModel->criaSessaoUsuario($usuario_dados);

    if ($_SESSION['usuario_nivel'] === 'A') {
        header('Location: ../paineladm/painel.php');
        exit();
    } elseif ($_SESSION['usuario_nivel'] === 'U') {
        header('Location: ../painel.php');
        exit();
    } else {
        $limiter->hit($key, 60);
        header('Location: ../login.php?mensagem_erro=' . urlencode('Usuário sem permissão'));
        exit();
    }
} else {
    $limiter->hit($key, 60);
    header('Location: ../login.php?mensagem_erro=' . urlencode('E-mail ou senha incorretos.'));
    exit();
}