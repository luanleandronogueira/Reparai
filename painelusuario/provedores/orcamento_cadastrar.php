<?php
session_start();
require_once '../model/Model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../orcamento_cadastro.php?mensagem_erro=' . urlencode('Método inválido.'));
    exit();
}

$contents = $_POST;

// Validação CSRF
if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../orcamento_cadastro.php?mensagem_erro=' . urlencode('Token inválido.'));
    exit();
}

// Lógica de Upload de Anexo
$anexo_path = null;
if (isset($_FILES['arquivo_anexo']) && $_FILES['arquivo_anexo']['error'] === UPLOAD_ERR_OK) {
    $pastaDestino = '../../assets/orcamentos/img/';
    
    // Cria pasta se não existir
    if (!is_dir($pastaDestino)) mkdir($pastaDestino, 0755, true);
    
    $extensao = pathinfo($_FILES['arquivo_anexo']['name'], PATHINFO_EXTENSION);
    $nomeArquivo = 'orc_' . $contents['entidade_id'] . '_' . uniqid() . '.' . $extensao;
    
    if (move_uploaded_file($_FILES['arquivo_anexo']['tmp_name'], $pastaDestino . $nomeArquivo)) {
        $anexo_path = 'assets/orcamentos/img/' . $nomeArquivo;
    }
}

// Sanitização seguindo seu padrão
$dados = [
    'entidade_id'    => $contents['entidade_id'],
    'imovel_id'      => $contents['imovel_id'],
    'servicos'       => trim(htmlspecialchars(strtoupper($contents['servicos']))),
    'observacoes'    => trim(htmlspecialchars(strtoupper($contents['observacoes'] ?? ''))),
    'tipo_urgencia'  => $contents['tipo_urgencia'],
    'solicitante_id' => $contents['usuario_id'],
    'anexo'     => $anexo_path
];

$OrcamentosModel = new OrcamentosModel();
$sucesso = $OrcamentosModel->inserirOrcamento($dados);

if ($sucesso) {
    header('Location: ../painel.php?mensagem_sucesso=' . urlencode('Orçamento enviado com sucesso!'));
} else {
    header('Location: ../orcamento_cadastro.php?mensagem_erro=' . urlencode('Erro ao processar o envio.'));
}
exit();