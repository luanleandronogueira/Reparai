<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$PrestadoresServicoModel = new PrestadoresServicoModel();
$ServicosModel = new ServicosModel();

$prestador = $PrestadoresServicoModel->buscaPrestadorPorId($id);
$listaServicos = $ServicosModel->listarServicos();

if (!$prestador) {
    header('Location: prestadores_listagem.php?mensagem_erro=' . urlencode('Prestador não encontrado.'));
    exit;
}
?>

<div class="content-page-container">
    <div class="form-card-wrapper">
        <div class="form-card-header">
            <h2 class="form-card-title">Editar Prestador de Serviço</h2>
        </div>

        <form action="provedores/prestador_servico_editar.php" method="POST" class="modern-form-layout">
            <input type="hidden" name="id" value="<?= $prestador['id'] ?>">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?? '' ?>">

            <div class="form-grid-layout">
                <div class="form-group-modern full-width">
                    <label>Nome do Prestador</label>
                    <input type="text" name="nome" class="form-control-modern" value="<?= htmlspecialchars($prestador['nome']) ?>" required>
                </div>

                <div class="form-group-modern">
                    <label>Telefone</label>
                    <input type="text" name="contato" class="form-control-modern" value="<?= htmlspecialchars($prestador['contato']) ?>" required>
                </div>

                <div class="form-group-modern">
                    <label>Cidade</label>
                    <input type="text" name="cidade" class="form-control-modern" value="<?= htmlspecialchars($prestador['cidade']) ?>" required>
                </div>

                <div class="form-group-modern full-width">
                    <label>Adicionar Serviço ao Portfólio</label>
                    <select id="servico_selector" class="form-control-modern">
                        <option value="" selected disabled>Selecione um serviço...</option>
                        <?php foreach ($listaServicos as $s) echo '<option value="'.$s['id'].'">'.htmlspecialchars($s['tipo']).'</option>'; ?>
                    </select>
                </div>

                <div class="form-group-modern full-width">
                    <label>Serviços Prestados</label>
                    <textarea name="servicos_prestados" id="servicos_prestados" class="form-control-modern" rows="3" required><?= htmlspecialchars($prestador['servicos_prestados']) ?></textarea>
                </div>
            </div>

            <div class="form-actions-zone">
                <a href="prestadores_listagem.php" class="btn-cancel-modern">Cancelar</a>
                <button type="submit" class="btn-submit-modern">Atualizar Registro</button>
            </div>
        </form>
    </div>
</div>

<script>
document.getElementById('servico_selector').addEventListener('change', function() {
    const selected = this.options[this.selectedIndex].text;
    const input = document.getElementById('servicos_prestados');
    if (this.value !== "" && !input.value.includes(selected)) {
        input.value += (input.value ? ", " : "") + selected;
    }
    this.selectedIndex = 0;
});
</script>

<?php include 'controladores/footer.php'; ?>