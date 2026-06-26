<?php
session_start();
include 'controladores/header.php';
require_once 'model/Model.php';

// Filtra e valida o ID enviado via GET
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) {
    echo "<div class='container mt-5'><div class='alert alert-danger shadow-sm rounded-3'>
            <i class='bi bi-exclamation-triangle-fill me-2'></i> Por favor, insira um ID válido para consultar! 
            <a class='btn btn-sm btn-danger float-end' href='orcamento_listagem.php'>Voltar</a>
          </div></div>";
    include_once("controladores/footer.php");
    exit;
}

?>










<?php include 'controladores/footer.php'; ?>