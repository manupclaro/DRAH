<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Início | DRAH</title>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #ffb084;
        color: #333;
        min-height: 100vh;
        padding-top: 80px;
    }

     /* IMAGEM CLICÁVEL */
.imagem-produto {
    cursor: pointer;
    transition: transform 0.3s;
}

.imagem-produto:hover {
    transform: scale(1.05);
}

/* FUNDO DO POPUP */
.modal-detalhes {
    display: none;
    position: fixed;
    z-index: 3000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.65);
    justify-content: center;
    align-items: center;
}

/* CAIXA DO POPUP */
.modal-conteudo {
    background: white;
    width: 90%;
    max-width: 750px;
    padding: 30px;
    border-radius: 18px;
    display: flex;
    gap: 30px;
    position: relative;
    align-items: center;
    animation: abrirModal 0.3s ease;
}

@keyframes abrirModal {
    from {
        opacity: 0;
        transform: scale(0.8);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

/* IMAGEM GRANDE */
.modal-imagem {
    width: 280px;
    height: 280px;
    object-fit: contain;
    border-radius: 12px;
    background: #fff2e5;
}

/* INFORMAÇÕES */
.modal-informacoes {
    flex: 1;
}

.modal-informacoes h2 {
    color: #ED5721;
    font-size: 25px;
    margin-bottom: 15px;
}

.modal-informacoes p {
    margin-bottom: 15px;
    font-size: 16px;
    color: #444;
    line-height: 1.5;
}

/* BOTÃO FECHAR */
.fechar-modal {
    position: absolute;
    top: 12px;
    right: 18px;
    font-size: 30px;
    color: #ED5721;
    cursor: pointer;
    border: none;
    background: none;
}

.fechar-modal:hover {
    color: #ff7f50;
}

/* RESPONSIVIDADE */
@media (max-width: 650px) {
    .modal-conteudo {
        flex-direction: column;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-imagem {
        width: 200px;
        height: 200px;
    }
}

    /* HEADER */
    header {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 32px;
        background: #ED5721;
        z-index: 1000;
    }

    .logo {
        display: flex;
        align-items: center;
        gap: 15px;
    }  

    .logo img {
        height: 50px;
        width: auto;
        display: block;
    }

    .menu-superior {
        display: flex;
        gap: 15px;
        align-items: center;
    }

    .menu-superior a {
        background: #ff7f50;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .menu-superior a:hover {
        background: #ED5721;
    }

    /* LAYOUT PRINCIPAL */
    .main-container {
        display: flex;
        max-width: 1800px;
        margin: 0 auto;
        gap: 30px;
        padding: 30px;
        margin-left: 260px;
    }

    /* SIDEBAR */
    .sidebar {
        position: fixed;
        top: 80px;
        left: 0;
        width: 260px;
        height: calc(100vh - 80px);
        background:white;
        border-right: 2px solid #ED5721;
        padding: 20px;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }  

    .sidebar h2 {
        font-size: 22px;
        margin-bottom: 25px;
        color: #ED5721;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .category-list {
        list-style: none;
    }

    .category-list li {
        margin-bottom: 12px;
    }

    .category-list a {
        color: #333;
        text-decoration: none;
        font-size: 16px;
        font-weight: 500;
        padding: 12px 18px;
        border-radius: 8px;
        display: block;
        background: #fff2e5;
        border: 2px solid #ffb084;
    }

    .category-list a:hover {
        background: #ff7f50;
        border: 2px solid #ff7f50;
        color: white;
    }

    /* ÁREA DE CONTEÚDO */
    .content {
        flex: 1;
    }

    /* SEARCH BAR */
    .search-container {
        background: white;
        padding: 25px;
        border-radius: 14px;
        margin-bottom: 30px;
        border: 2px solid #ED5721;
    }

    .search-box {
        display: flex;
        gap: 15px;
    }

    .search-box input {
        flex: 1;
        padding: 16px 25px;
        border: 2px solid #ffb084;
        border-radius: 25px;
        font-size: 16px;
        background: #fff2e5;
        color: #333;
    }

    .search-box input::placeholder {
        color: #999;
    }

    .search-box input:focus {
        outline: none;
        border-color: #ff7f50;
        background: #fff2e5;
    }

    .search-box button {
        padding: 16px 35px;
        background: #ff7f50;
        color: white;
        border: 2px solid #ff7f50;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .search-box button:hover {
        background: #ED5721;
        border-color: #ED5721;
    }

    /* GRID DE PRODUTOS */
    .comp-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(650px, 1fr));
        gap: 25px;
    }

    .comp-image {
        width: 100%;
        height: 220px;
        background: linear-gradient(135deg, #ffb084 0%, #ff7f50 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 60px;
        position: relative;
        overflow: hidden;
    }

    .comp-image-horizontal {
        width: 150px;
        height: 150px;
        border-radius: 12px;
        background: linear-gradient(135deg, #ffb084, #ff7f50);
        font-size: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: white;
    }

    .comp-content {
        padding: 25px;
    }

    .comp-content-horizontal {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .comp-category {
        background: #ffb084;
        padding: 5px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 700;
        width: fit-content;
        margin-bottom: 8px;
    }

    .comp-title {
        font-size: 20px;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
        line-height: 1.3;
    }

    .comp-description {
        font-size: 14px;
        color: #999;
        margin-bottom: 12px;
    }

    .comp-meta {
        display: flex;
        justify-content: space-between;
        padding-top: 12px;
        border-top: 2px solid #ffb084;
        margin-bottom: 14px;
    }

    /* BOTÕES */
    .comp-actions {
        display: flex;
        gap: 12px;
    }

    .comp-actions-horizontal {
        display: flex;
        gap: 12px;
    }

    .btn {
        flex: 1;
        padding: 14px;
        border: 2px solid #ff7f50;
        border-radius: 25px;
        font-size: 14px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* RESPONSIVIDADE */
    @media (max-width: 1200px) {
        .main-container {
            margin-left: 0;
        }
        
        .sidebar {
            width: 100%;
            position: static;
        }
    }

    .card {
        width: 100%;
        height: 170px;            
        background: white;
        display: flex;
        align-items: center;
        gap: 25px;
        padding: 20px;
        border-radius: 14px;
        margin-bottom: 25px;
    }
    .card img {
        height: 100%; 
        width: auto;
        border-radius: 12px;
        object-fit: cover;
    }
    .card .texto {
        flex: 1;     
    }
    .card .texto h3 {
        margin: 0 0 8px 0;
        font-size: 22px;
    }
    .card .texto p {
        margin: 0 0 12px 0;
        font-size: 15px;
        color: #333;
    }

    .horizontal-card {
        width: 100%;
        display: flex;
        flex-direction: row;
        padding: 20px;
        gap: 25px;
        background: white;
        border-radius: 14px;
        border: 2px solid #ff7f50;
        height: 220px;
        overflow: hidden;
        position: relative;
    }
    
    .comp-image-horizontal img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    border-radius: 12px;
}
</style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="logo">
            <a href="#"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
        </div>
        <nav class="menu-superior">
          <a href="login.php">Entrar</a>  
        </nav>
      </header>

     <!-- CONTAINER PRINCIPAL -->
    <div class="main-container">
        
        <!-- SIDEBAR -->
      <aside class="sidebar">
    <h2>📦 Categorias</h2>
    <ul class="category-list">
        <li><a href="?categoria=">Todos</a></li>
        <li><a href="?categoria=Arduino">Arduino</a></li>
        <li><a href="?categoria=Atuadores">Atuadores</a></li>
        <li><a href="?categoria=Componentes eletrônicos">Componentes eletrônicos</a></li>
        <li><a href="?categoria=ESP32">ESP32</a></li>
        <li><a href="?categoria=Sensores">Sensores</a></li>
        <li><a href="?categoria=Shields">Shields</a></li>
        <li><a href="?categoria=Outros">Outros</a></li>
    </ul>
</aside>

        <!-- CONTEÚDO -->
        <main class="content">
             <!-- PESQUISA -->
<div class="search-container">
    <form method="GET" class="search-box">

        <input 
            type="text" 
            name="busca" 
            placeholder="Buscar componentes..." 
            value="<?php echo isset($_GET['busca']) ? htmlspecialchars($_GET['busca']) : ''; ?>"
        >

        <!-- MANTÉM A CATEGORIA SELECIONADA -->
        <input 
            type="hidden" 
            name="categoria" 
            value="<?php echo isset($_GET['categoria']) ? htmlspecialchars($_GET['categoria']) : ''; ?>"
        >

        <button type="submit">Pesquisar</button>
    </form>
</div>
  <!-- GRID -->
<div class="comp-grid">

<?php
require_once __DIR__ . "/config.php";

// RECEBE OS DADOS DA PESQUISA
$busca = isset($_GET['busca']) ? $_GET['busca'] : '';
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// CONSULTA AO BANCO DE DADOS
$sql = "SELECT * FROM COMPONENTE WHERE 1=1";

$parametros = [];
$tipos = "";

// FILTRO DE PESQUISA
if (!empty($busca)) {
    $sql .= " AND (NOME LIKE ? OR DESCRICAO LIKE ?)";

    $parametros[] = "%$busca%";
    $parametros[] = "%$busca%";
    $tipos .= "ss";
}

// FILTRO DE CATEGORIA
if (!empty($categoria)) {
    $sql .= " AND CATEGORIA = ?";

    $parametros[] = $categoria;
    $tipos .= "s";
}

// PREPARA E EXECUTA A CONSULTA
$stmt = $conexao->prepare($sql);

if (!empty($parametros)) {
    $stmt->bind_param($tipos, ...$parametros);
}

$stmt->execute();
$result = $stmt->get_result();

// EXIBE OS COMPONENTES
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>
  <div class="comp-card horizontal-card">
  <div class="comp-image-horizontal">
    <img 
        src="componentes/<?php echo htmlspecialchars($row['IMAGEM']); ?>"
        alt="<?php echo htmlspecialchars($row['NOME']); ?>"
        class="imagem-produto"
        onclick="abrirDetalhes(this)"

        data-nome="<?php echo htmlspecialchars($row['NOME'], ENT_QUOTES); ?>"
        data-categoria="<?php echo htmlspecialchars($row['CATEGORIA'], ENT_QUOTES); ?>"
        data-descricao="<?php echo htmlspecialchars($row['DESCRICAO'], ENT_QUOTES); ?>"
        data-estoque="<?php echo htmlspecialchars($row['QUANTIDADE'], ENT_QUOTES); ?>"
    >
</div>

    <!-- CONTEÚDO -->
    <div class="comp-content-horizontal">
        <span class="comp-category">
            <?php echo $row['CATEGORIA']; ?>
        </span>

        <div class="comp-title">
            <?php echo $row['NOME']; ?>
        </div>

        <div class="comp-description">
            <?php echo $row['DESCRICAO']; ?>
        </div>

        <div class="comp-meta">
            <div>Estoque: <b><?php echo $row['QUANTIDADE']; ?></b></div>
        </div>
    </div>

</div>

<?php
    }
} else {
    echo "<p>Nenhum componente encontrado.</p>";
}

$stmt->close();
?>
</div>
</div>

</div>
<div class="modal-detalhes" id="modalDetalhes">

    <div class="modal-conteudo">

        <button class="fechar-modal" onclick="fecharDetalhes()">
            &times;
        </button>

        <img src="" id="modalImagem" class="modal-imagem">

        <div class="modal-informacoes">

            <h2 id="modalNome"></h2>
 <p>
                <strong>Estoque disponível:</strong>
                <span id="modalEstoque"></span>
            </p>
            <p>
                <strong>Categoria:</strong>
                <span id="modalCategoria"></span>
            </p>

            <p>
                <strong>Descrição:</strong>
                <span id="modalDescricao"></span>
            </p>

           

        </div>

    </div>

</div>

<script>

function abrirDetalhes(imagem) {

    let modal = document.getElementById("modalDetalhes");

    document.getElementById("modalImagem").src = imagem.src;

    document.getElementById("modalNome").textContent =
        imagem.dataset.nome;

    document.getElementById("modalCategoria").textContent =
        imagem.dataset.categoria;

    document.getElementById("modalDescricao").textContent =
        imagem.dataset.descricao;

    document.getElementById("modalEstoque").textContent =
        imagem.dataset.estoque;

    modal.style.display = "flex";
}

function fecharDetalhes() {
    document.getElementById("modalDetalhes").style.display = "none";
}

// Fecha ao clicar fora da janela
document.getElementById("modalDetalhes").addEventListener("click", function(event) {

    if (event.target === this) {
        fecharDetalhes();
    }

});

// Fecha apertando ESC
document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        fecharDetalhes();
    }

});

</script>
</body>
</html>
