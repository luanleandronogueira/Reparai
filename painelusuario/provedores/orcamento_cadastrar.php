<?php
session_start();
require_once '../model/Model.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require '../../vendor/autoload.php';

//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

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

$Entidade = new EntidadeModel();
$nome_entidade = $Entidade->buscaNomeEntidadePorId($contents['entidade_id']);

// echo '<pre>';
// print_r($nome_entidade);
// echo '</pre>';
// exit();

$body = "
<div style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
    <h2 style='color: #11223F;'>Novo Orçamento Recebido</h2>
    <p>Olá, recebemos uma nova solicitação de orçamento em nosso sistema.</p>
    
    <div style='background: #F9F6F0; padding: 15px; border-radius: 8px;'>
        <p><strong>Entidade:</strong> " . $nome_entidade['entidade_nome'] . "</p>
        <p><strong>Serviço:</strong> " . $contents['servicos'] . "</p>
        <p><strong>Urgência:</strong> " . ($contents['tipo_urgencia'] == 'A' ? 'Alta' : ($contents['tipo_urgencia'] == 'M' ? 'Média' : 'Baixa')) . "</p>
        <p><strong>Observações:</strong> " . (!empty($contents['observacoes']) ? $contents['observacoes'] : 'Nenhuma observação.') . "</p>
    </div>
    
    <p>Acesse o painel administrativo para analisar este pedido.</p>
    <br>
    <p style='font-size: 12px; color: #888;'>Equipe Reparai - JL Comércio e Serviços</p>
</div>";

if ($sucesso) {
    try {
        // Configurações do Servidor (Ideal levar para um arquivo .env ou config)
        $mail->isSMTP();
        $mail->Host       = 'smtp.titan.email';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'nao-responda@l3tecnologia.app.br';
        $mail->Password   = '@Itsolit';
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;
        $mail->CharSet    = 'UTF-8';

        // Destinatários
        $mail->setFrom($mail->Username, 'Novo Orçamento - Solicitado');
        $mail->addAddress('luannogueira093@gmail.com', 'Administrador');

        // Conteúdo
        $mail->isHTML(true);
        $mail->Subject = 'Nova Solicitação de Orçamento - Cliente ' . $nome_entidade['nome_entidade'];

        // Aqui você insere o layout HTML acima (pode colocar o HTML em uma variável)
        $mail->Body = $body;
        $mail->send();

        header('Location: ../orcamento_listagem.php?mensagem_sucesso=' . urlencode('Orçamento enviado com sucesso!'));

    } catch (Exception $e) {
        header('Location: ../orcamento_cadastro.php?mensagem_erro=' . urlencode('Erro ao processar o envio: ' . $e->errorMessage()));
    }

} else {
    header('Location: ../orcamento_cadastro.php?mensagem_erro=' . urlencode('Erro ao processar o envio.'));
}
exit();
