<?php
session_start();
require_once '../model/Model.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode('Método de requisição inválido.'));
    exit();
}

$contents = $_POST;

// Validação do Token CSRF
if (!isset($contents['csrf_token']) || $contents['csrf_token'] !== $_SESSION['csrf_token']) {
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode('Token de segurança inválido ou expirado.'));
    exit();
}

// Validação dos dados obrigatórios
$id = filter_var($contents['id'], FILTER_VALIDATE_INT);
if (!$id || empty(trim($contents['custo'])) || empty(trim($contents['prazo']))) {
    header("Location: ../orcamento_avaliar.php?id={$contents['id']}&mensagem_erro=" . urlencode('Por favor, preencha o Custo e o Prazo do serviço.'));
    exit();
}

// Busca o orçamento atual para manter o anexo antigo caso um novo não seja enviado
$OrcamentosModel = new OrcamentosModel();
$orcamentoAtual = $OrcamentosModel->buscaOrcamentoPorId($id);

if (!$orcamentoAtual) {
    header('Location: ../orcamento_listagem.php?mensagem_erro=' . urlencode('Orçamento não encontrado.'));
    exit();
}

$anexo_path = $orcamentoAtual['anexo']; // Mantém o atual por padrão

// Lógica para upload do arquivo de proposta técnica (PDF ou Imagem)
if (isset($_FILES['arquivo_anexo']) && $_FILES['arquivo_anexo']['error'] === UPLOAD_ERR_OK) {
    $pastaDestino = '../../assets/orcamentos/img/';

    if (!is_dir($pastaDestino)) {
        mkdir($pastaDestino, 0755, true);
    }

    $extensao = pathinfo($_FILES['arquivo_anexo']['name'], PATHINFO_EXTENSION);
    $extensoesPermitidas = ['pdf', 'jpg', 'jpeg', 'png'];

    if (in_array(strtolower($extensao), $extensoesPermitidas)) {
        $novoNome = 'prop_' . $id . '_' . uniqid() . '.' . $extensao;
        $caminhoCompleto = $pastaDestino . $novoNome;

        if (move_uploaded_file($_FILES['arquivo_anexo']['tmp_name'], $caminhoCompleto)) {
            // Caminho relativo salvo no banco para leitura correta na view
            $anexo_path = 'assets/orcamentos/img/' . $novoNome;
        }
    } else {
        header("Location: ../orcamento_avaliar.php?id={$id}&mensagem_erro=" . urlencode('Formato de arquivo inválido! Envie PDF, JPG ou PNG.'));
        exit();
    }
}

// Montagem dos dados tratados para salvar
$dadosAtualizacao = [
    'id'                   => $id,
    'custo'                => trim(htmlspecialchars($contents['custo'])),
    'prazo'                => trim(htmlspecialchars(strtoupper($contents['prazo']))),
    'observacao_orcamento' => trim(htmlspecialchars(strtoupper($contents['observacao_orcamento']))),
    'status_atendimento'   => 'ORÇAMENTO ENVIADO', // Definido conforme sua solicitação
    'anexo'                => $anexo_path,
    'responsavel_id'       => trim($contents['responsavel_id'])
];

// Persistência no banco de dados
if ($OrcamentosModel->atualizarAvaliacao($dadosAtualizacao)) {
    $mensagem = 'Orçamento avaliado e enviado com sucesso!';
    header('Location: ../orcamento_listagem.php?mensagem_sucesso=' . urlencode($mensagem));
    exit();
} else {
    $mensagem = 'Erro interno ao tentar salvar a avaliação. Verifique os relatórios de erros.';
    header("Location: ../orcamento_avaliar.php?id={$id}&mensagem_erro=" . urlencode($mensagem));
    exit();
}