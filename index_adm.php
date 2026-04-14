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
        background: #b7edea;
        color: #333;
        min-height: 100vh;
        padding-top: 80px;
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
        background: #006d77;
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
        background: #00c2c7;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .menu-superior a:hover {
        background: #006d77;
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
        border-right: 2px solid #006d77;
        padding: 20px;
        display: flex;
        flex-direction: column;
        overflow-y: auto;
    }  

    .sidebar h2 {
        font-size: 22px;
        margin-bottom: 25px;
        color: #006d77;
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
        background: #E5FFFA;
        border: 2px solid #b7edea;
    }

    .category-list a:hover {
        background: #00c2c7;
        border: 2px solid #00c2c7;
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
        border: 2px solid #006d77;
    }

    .search-box {
        display: flex;
        gap: 15px;
    }

    .search-box input {
        flex: 1;
        padding: 16px 25px;
        border: 2px solid #b7edea;
        border-radius: 25px;
        font-size: 16px;
        background: #E5FFFA;
        color: #333;
    }

    .search-box input::placeholder {
        color: #999;
    }

    .search-box input:focus {
        outline: none;
        border-color: #00c2c7;
        background: #E5FFFA;
    }

    .search-box button {
        padding: 16px 35px;
        background: #00c2c7;
        color: white;
        border: 2px solid #00c2c7;
        border-radius: 25px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .search-box button:hover {
        background: #006d77;
        border-color: #006d77;
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
        background: linear-gradient(135deg, #b7edea 0%, #00c2c7 100%);
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
        background: linear-gradient(135deg, #b7edea, #00c2c7);
        font-size: 70px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        color: white;
    }

    .comp-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background: #00c2c7;
        color: white;
        width: 35px;
        height: 35px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: bold;
        z-index: 10;
        border: none;
        cursor: pointer;
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
        background: #b7edea;
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
        border-top: 2px solid #b7edea;
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
        border: 2px solid #00c2c7;
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
        border: 2px solid #00c2c7;
        height: 220px;
        overflow: hidden;
        position: relative;
    }

    .horizontal-card .product-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 32px;
        height: 32px;
        background: #00c2c7;
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        font-weight: bold;
        z-index: 10;
        cursor: pointer;
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
            <a href="perfil_adm.html">Perfil</a>
            <a href="seuspedidos_adm.html">Seus Pedidos</a>
            <a href="carrinho_adm.html">Carrinho</a>
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

    <!-- CONTAINER PRINCIPAL -->
    <div class="main-container">
        
        <!-- SIDEBAR -->
        <aside class="sidebar">
            <h2>📦 Categorias</h2>
            <ul class="category-list">
                <li><a href="#">Arduino</a></li>
                <li><a href="#">Atuadores</a></li>
                <li><a href="#">Componentes eletrônicos</a></li>
                <li><a href="#">ESP32</a></li>
                <li><a href="#">Sensores</a></li>
                <li><a href="#">Shields</a></li>
                <li><a href="#">Outros</a></li>
            </ul>
        </aside>

        <!-- CONTEÚDO -->
        <main class="content">
            <!-- BARRA DE PESQUISA -->
            <div class="search-container">
                <div class="search-box">
                    <input type="text" placeholder="🔍 Buscar componentes, equipamentos...">
                    <button>Pesquisar</button>
                </div>
            </div>

            <!-- GRID DE PRODUTOS -->
            <div class="comp-grid"> 
                
                <!-- CARD LATERAL 1 -->
                <div class="comp-card horizontal-card">
                    <span class="comp-badge">+</span>
                    <!-- ADICIONAR junto desse span ^^ onclick="location.href='naoseicomoadicionanocarrinho' -->

                    <!-- IMAGEM LATERAL -->
                    <div class="comp-image-horizontal">
                        <img src="componentes/ledverde.png" 
                        alt="Imagem do LED"
                        style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>

                    <!-- CONTEÚDO -->
                    <div class="comp-content-horizontal">
                        <span class="comp-category">Eletrônicos</span>
                        <div class="comp-title">LED - Verde</div>
                        <div class="comp-description">
                            Led verde.
                        </div>
                        <div class="comp-meta">
                            <div>Estoque: <b>32</b></div>
                        </div>
                    </div>
                </div>


                <!-- CARD LATERAL 2 -->
                <div class="comp-card horizontal-card">
                    <span class="comp-badge">+</span>

                    <!-- IMAGEM LATERAL -->
                    <div class="comp-image-horizontal">
                        <img src="componentes/ledverde.png" 
                        alt="Imagem do LED"
                        style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>

                    <!-- CONTEÚDO -->
                    <div class="comp-content-horizontal">
                        <span class="comp-category">Eletrônicos</span>
                        <div class="comp-title">LED - Verde</div>
                        <div class="comp-description">
                            Led verde.
                        </div>
                        <div class="comp-meta">
                            <div>Estoque: <b>32</b></div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>