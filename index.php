<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Styleguide & UI Components - JL Comércio e Serviços</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style-old.css">
</head>
<body>

    <header class="main-header">
        <div class="container header-grid">
            <div class="brand-mock">
                <span class="brand-jl">JL</span>
                <span class="brand-sub">COMÉRCIO E SERVIÇOS</span>
            </div>
            <nav class="nav-mock">
                <span class="badge-status">Ambiente de Componentes v1.0</span>
            </nav>
        </div>
    </header>

    <main class="container page-layout">
        
        <section class="ui-section animate-reveal">
            <h2>01. Hierarquia de Tags H & Texto</h2>
            <hr class="divider">
            <div class="demo-box">
                <h1><h1>H1 - Estilo e Conforto para seu Quarto</h1></h1>
                <h2><h2>H2 - Cama Americana Baú Camurça Nero</h2></h2>
                <h3><h3>H3 - Características do Produto</h3></h3>
                <h4><h4>H4 - Código e Medidas Disponíveis</h4></h4>
                <h5><h5>H5 - Subtítulos de Seções Menores</h5></h5>
                <h6><h6>H6 - Sob-títulos e Overlines</h6></h6>
                <p>Este é um parágrafo padrão (`p`). Estrutura de madeira de reflorestamento, <strong>ecologicamente correta</strong>, tratada com bactericida natural que a protege de cupins, fungos e brocas, garantindo maior durabilidade.</p>
                <p class="text-caption">Mais que produtos, entregamos conforto, bem-estar e qualidade de vida!</p>
            </div>
        </section>

        <section class="ui-section animate-reveal">
            <h2>02. Componentes de Ação (Buttons & Badges)</h2>
            <hr class="divider">
            <div class="demo-box flex-row">
                <button class="btn btn-primary">Fale com a Gente</button>
                <button class="btn btn-secondary">Mais de 200 Colchões</button>
                <button class="btn btn-outline">Consultar Medidas</button>
                <span class="badge-accent">Lançamento Ortobom</span>
            </div>
        </section>

        <section class="ui-section animate-reveal">
            <h2>03. Elementos de Formulário (Inputs & Validações)</h2>
            <hr class="divider">
            <form class="demo-box form-grid" onsubmit="return false;">
                <div class="form-group">
                    <label for="nome">Nome Completo</label>
                    <input type="text" id="nome" class="form-control" placeholder="Ex: Júnior Leite" required>
                    <span class="input-helper">Insira o nome completo para o cadastro.</span>
                </div>

                <div class="form-group">
                    <label for="medidas">Selecione a Medida Desejada</label>
                    <select id="medidas" class="form-control">
                        <option value="1">1,88 m x 0,88 m (Código: 1051611650)</option>
                        <option value="2">1,88 m x 1,38 m (Código: 1051611651)</option>
                        <option value="3">1,98 m x 1,58 m (Código: 1051611652)</option>
                        <option value="4">1,98 m x 1,86 m (Código: 1051611653)</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="mensagem">Mensagem de Solicitação</label>
                    <textarea id="mensagem" class="form-control" rows="3" placeholder="Olá, gostaria de saber a disponibilidade de entrega rápida para a minha região..."></textarea>
                </div>
            </form>
        </section>

        <section class="ui-section animate-reveal">
            <h2>04. Aplicação de Grid & Cards Comportamentais</h2>
            <hr class="divider">
            <div class="product-grid">
                <div class="ui-card">
                    <div class="card-badge">Camurça Nero</div>
                    <div class="card-image-placeholder">
                        <span>[ Imagem Cama Baú ]</span>
                    </div>
                    <div class="card-body">
                        <h3>Cama Americana Baú</h3>
                        <p>Sistema de amortecedores pneumáticos que permite abertura sem esforço.</p>
                        <div class="card-footer">
                            <span class="price-mock">Sob Consulta</span>
                            <button class="btn btn-primary btn-sm">Ver Detalhes</button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <footer class="main-footer">
        <p>&copy; 2026 JL Comércio e Serviços LTDA. Arquitetura de Interface de Alta Performance.</p>
    </footer>

    <script src="assets/js/script.js"></script>
</body>
</html>