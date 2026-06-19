<?php

session_start();

// Faz a incorporação
require_once "model/Model.php";

// Cria a instância
$loginModel = new LoginModel();

$loginModel->finalizaSessao();

header('Location: ../login.php?');
session_destroy();
exit();