<?php
include_once 'model/Model.php';
$LoginModel = new LoginModel();
$LoginModel->validaSessao();

if (!$LoginModel->validaSessao()) {
  // Se a sessão for inválida ou expirou, redireciona para o login com mensagem
  $mensagem = "Sessão expirada ou acesso não autorizado. Por favor, faça login novamente.";
  header("Location: ../login.php?mensagem_erro=" . urlencode($mensagem));
  exit();
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JL Comércio e Serviços</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<nav class="main-navbar">
    <div class="nav-container">
        
        <a href="painel.php" class="nav-brand">
            <div class="nav-logo-shield">JL</div>
            <span class="nav-logo-text">Sistema de <span class="text-gold">Gestão</span></span>
        </a>

        <ul class="nav-links">
            
            <li class="nav-item dropdown">
                <a href="#" class="dropdown-trigger">
                    <span>Cadastros</span>
                    <svg class="dropdown-caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="entidade_listagem.php">Clientes</a></li>
                    <li><a href="entidade_listagem.php">Entidades</a></li>
                    <li><a href="imoveis_listagem.php">Imovéis</a></li>
                    <li><a href="servico_tipo.php">Serviços</a></li>
                    <li><a href="prestador_servico_listagem.php">Prestadores</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="dropdown-trigger">
                    <span>Movimentações</span>
                    <svg class="dropdown-caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="mov_vendas.php">Vendas / Pedidos</a></li>
                    <li><a href="mov_compras.php">Entrada de Notas</a></li>
                    <li><a href="mov_estoque.php">Controlo de Estoque</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="dropdown-trigger">
                    <span>Financeiro</span>
                    <svg class="dropdown-caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="fin_pagar.php">Contas a Pagar</a></li>
                    <li><a href="fin_receber.php">Contas a Receber</a></li>
                    <li><a href="fin_caixa.php">Fluxo de Caixa</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="dropdown-trigger">
                    <span>Relatórios</span>
                    <svg class="dropdown-caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="rel_faturamento.php">Faturação Mensal</a></li>
                    <li><a href="rel_inventario.php">Inventário de Itens</a></li>
                    <li><a href="rel_erros.php">Relatórios de Erros</a></li>
                </ul>
            </li>

            <li class="nav-item dropdown">
                <a href="#" class="dropdown-trigger">
                    <span>Configurações</span>
                    <svg class="dropdown-caret" width="10" height="6" viewBox="0 0 10 6" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M1 1L5 5L9 1" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="config_usuarios.php">Utilizadores e Níveis</a></li>
                    <li><a href="config_empresa.php">Dados da Empresa</a></li>
                    <li><a href="config_sistema.php">Parâmetros Gerais</a></li>
                </ul>
            </li>

        </ul>

        <div class="nav-user-zone">
            <span class="user-greeting">Olá, <strong><?= htmlspecialchars($_SESSION['usuario_nome'] ?? 'Utilizador') ?></strong></span>
            <a href="logout.php" class="btn-nav-logout">Sair</a>
        </div>

    </div>
</nav>