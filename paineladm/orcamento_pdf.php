<?php
session_start();
require_once 'model/Model.php';
require_once '../vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

// Inicializa e valida o ID do orçamento solicitado
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    die("ID de orçamento inválido.");
}

$OrcamentosModel = new OrcamentosModel();
$orcamento = $OrcamentosModel->buscaOrcamentoPorId($id);

// Proteção extra: Só emite o PDF se o registro existir e estiver ENVIADO
if (!$orcamento || $orcamento['status_atendimento'] !== 'ORÇAMENTO ENVIADO') {
    die("Este orçamento ainda não foi avaliado ou enviado para emissão de documento oficial.");
}

// Configurações do Dompdf para renderização correta de fontes e imagens
$options = new Options();
$options->set('defaultFont', 'Helvetica');
$options->set('isHtml5ParserEnabled', true);
$options->set('isRemoteEnabled', true);

$dompdf = new Dompdf($options);

// Formatação do valor monetário
$custoFormatado = 'R$ ' . number_format((float)$orcamento['custo'], 2, ',', '.');

// Construção do Layout HTML / inline CSS para o PDF
$html = '
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: "Helvetica Neue", Helvetica, Arial, sans-serif; color: #232B38; margin: 0; padding: 0; font-size: 13px; line-height: 1.5; }
        .header-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; border-bottom: 2px solid #11223F; padding-bottom: 15px; }
        .company-title { font-size: 22px; font-weight: bold; color: #11223F; }
        .doc-title { font-size: 18px; text-align: right; color: #A61C1E; font-weight: bold; }
        .section-title { background-color: #11223F; color: #ffffff; padding: 6px 10px; font-size: 14px; font-weight: bold; margin-top: 20px; margin-bottom: 10px; border-radius: 3px; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 6px; vertical-align: top; border-bottom: 1px solid #edf2f7; }
        .label { font-weight: bold; color: #5A6578; width: 25%; }
        .value { color: #232B38; }
        .text-box { background-color: #f9f6f0; border: 1px solid #e2e8f0; padding: 12px; border-radius: 5px; min-height: 50px; white-space: pre-wrap; }
        .price-tag { font-size: 18px; font-weight: bold; color: #11223F; }
        .footer { position: fixed; bottom: -10px; left: 0; right: 0; text-align: center; font-size: 10px; color: #5A6578; border-top: 1px solid #e2e8f0; padding-top: 5px; }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td>
                <span class="company-title">JL - SISTEMA DE MANUTENÇÃO</span><br>
                <span style="color:#5A6578; font-size:11px;">Controle Centralizado de Reparos e Chamados</span>
            </td>
            <td class="doc-title">
                PROPOSTA DE ORÇAMENTO<br>
                <span style="font-size:12px; color:#232B38; font-weight:normal;">Nº #' . str_pad($orcamento['id'], 5, '0', STR_PAD_LEFT) . '</span>
            </td>
        </tr>
    </table>

    <div class="section-title">Dados da Solicitação</div>
    <table class="info-table">
        <tr>
            <td class="label">Entidade / Unidade:</td>
            <td class="value">' . htmlspecialchars($orcamento['entidade_nome']) . '</td>
        </tr>
        <tr>
            <td class="label">Imóvel / Prédio:</td>
            <td class="value">' . htmlspecialchars($orcamento['nome_locacao']) . '</td>
        </tr>
        <tr>
            <td class="label">Endereço:</td>
            <td class="value">' . htmlspecialchars($orcamento['endereco']) . '</td>
        </tr>
        <tr>
            <td class="label">Solicitante Técnico:</td>
            <td class="value">' . htmlspecialchars($orcamento['nome_solicitante'] ?? 'Não Identificado') . ' (' . htmlspecialchars($orcamento['email_solicitante'] ?? 'N/A') . ')</td>
        </tr>
    </table>

    <div class="section-title">Serviços Solicitados</div>
    <div class="text-box">' . nl2br(htmlspecialchars($orcamento['servicos'])) . '</div>

';

// Observações de campo (se houver)
if (!empty($orcamento['observacoes'])) {
    $html .= '<div style="margin-top: 10px; font-size: 12px; color: #5A6578;">'
        . '<strong>Observações de Campo:</strong> ' . $orcamento['observacoes'] . '</div>';
}

$html .= '

    <div class="section-title">Avaliação e Proposta Técnica</div>
    <table class="info-table">
        <tr>
            <td class="label">Prazo de Execução:</td>
            <td class="value" style="font-weight: bold; color: #B89047;">' . htmlspecialchars($orcamento['prazo']) . '</td>
        </tr>
        <tr>
            <td class="label">Valor Total Estimado:</td>
                <td class="value"><span class="price-tag">' . $custoFormatado . '</span></td>
        </tr>
    </table>

    <div class="section-title">Considerações Técnicas / Escopo do Orçamento</div>
    <div class="text-box">' . (!empty($orcamento['observacao_orcamento']) ? nl2br(htmlspecialchars($orcamento['observacao_orcamento'])) : 'Nenhuma observação técnica adicional registrada.') . '</div>

    <div class="footer">
        Documento gerado automaticamente via Plataforma de Manutenção em ' . date('d/m/Y H:i:s') . ' - Página 1 de 1
    </div>

</body>
</html>';

// Carrega o HTML processado e renderiza o arquivo final
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Envia o arquivo diretamente para visualização no navegador em uma nova aba
$dompdf->stream("orcamento_" . str_pad($id, 5, '0', STR_PAD_LEFT) . ".pdf", array("Attachment" => false));
exit();