<?php
session_start();

$conn = new mysqli(
    "localhost",
    "root",
    "",
    "DRAH"
);

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

if (isset($_POST['remover'])) {

    $idCarrinho = $_POST['idcarrinho'];

    $sql = "DELETE FROM CARRINHO
            WHERE IDCARRINHO = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $idCarrinho);
    $stmt->execute();
}

$idUser = $_SESSION['iduser'];

$sql = "SELECT *
        FROM CARRINHO C
        INNER JOIN COMPONENTE CP
        ON C.IDCOMP = CP.IDCOMP
        WHERE C.IDUSER = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $idUser);
$stmt->execute();

$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho | DRAH</title>

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

    .menu-superior a.active {
        background: white;
        color: #ED5721;
    }

    /* CONTAINER */
.main-container {
    max-width: 1200px;
    margin: auto;
    padding: 20px;
    flex: 1;
}

/* SEARCH */
.search-container {
    background: white;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 20px;
}

.search-box {
    display: flex;
    gap: 10px;
}

.search-box input {
    flex: 1;
    padding: 12px;
    border-radius: 20px;
    border: 1px solid #999;
}
.search-box button {
    padding: 12px 20px;
    border-radius: 20px;
    border: none;
    background: #ff7f50;
    color: white;
    cursor: pointer;
}
.search-box button:hover {
    padding: 12px 20px;
    border-radius: 20px;
    border: none;
    background: #ED5721;
    color: white;
    cursor: pointer;
}

/* GRID */
.products-grid {
    display: grid;
    gap: 20px;
}

/* CARD */
.horizontal-card {
    display: flex;
    gap: 20px;
    padding: 20px;
    border-radius: 12px;
    border: 2px solid #ED5721;
    background: white;
    position: relative;
}

.horizontal-card.selected {
    border-color: green;
}

/* IMAGEM */
.product-image-horizontal img {
    width: 120px;
}

/* BOTÃO REMOVER */
.product-x {
    position: absolute;
    right: 10px;
    top: 10px;
    background: red;
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
}

/* CHECKOUT */
.checkout-container {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    gap: 15px;
}

.checkout-button {
    padding: 15px 30px;
    border-radius: 25px;
    border: none;
    background: green;
    color: white;
    cursor: pointer;
}

.checkout-button:disabled {
    background: #333;
}

    /* RODAPÉ */
    footer {
        bottom: 15px;
        font-size: 12px;
        color: #333;
        text-align: center;
        margin-top: 25px;
        margin-bottom: 25px;
    }
</style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="logo">
            <a href="index_padrao.php"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
        </div>
        <nav class="menu-superior">
            <a href="index_padrao.php">Início</a>
            <a href="perfil.php">Perfil</a> 
            <a href="pedidos.php">Meus Pedidos</a> 
            <a href="carrinho.php" class="active">Carrinho</a> 
            <a href="logout.php">Logout</a>  
        </nav>
      </header>

    <!-- CONTAINER PRINCIPAL -->
    <div class="main-container">
        
        <!-- CONTEÚDO -->
        <main class="content">
            
            <!-- BARRA DE PESQUISA -->
            <div class="search-container">
                <div class="search-box">
                    <input type="text" id="searchInput" placeholder="🔍 Buscar componentes, equipamentos...">
                    <button onclick="buscar()">Pesquisar</button>
                </div>
            </div>

            <!-- GRID DE PRODUTOS -->
            <form action="novopedido.php" method="POST">
            <div class="products-grid">
            <?php while($componente = $result->fetch_assoc()) { ?>
                <div class="product-card horizontal-card"
                    data-id="<?= $componente['IDCOMP'] ?>">

                    <input type="checkbox" name="carrinho[]" value="<?= $componente['IDCARRINHO'] ?>">
                    <button type="submit" name="remover" class="product-x">X</button>

                    <div class="product-image-horizontal">
                        <img
                            src="<?= $componente['IMAGEM'] ?>"
                            alt="<?= $componente['NOME'] ?>">
                    </div>

                    <div class="product-content-horizontal">
                        <span class="product-category">
                            <?= $componente['CATEGORIA'] ?>
                        </span>
                        <div class="product-title">
                            <?= $componente['NOME'] ?>
                        </div>
                        <div class="product-description">
                            <?= $componente['DESCRICAO'] ?>
                        </div>
                        <div>
                            Estoque:
                            <b><?= $componente['QUANTIDADE'] ?></b>
                        </div>
                    </div>
                </div>
            <?php } ?>
            </div>

            <!-- BOTÃO DE FINALIZAR PEDIDO -->
            <div class="checkout-container">
                <div class="checkout-info">
                    <span id="selectedCount">0</span> itens selecionados
                </div>
                <!-- lista de componentes -->
                <button type="submit" class="checkout-button" id="checkout">
                    🛒 Novo Pedido
                </button>
            </form>
            <form method="POST">
                <input type="hidden" name="idcarrinho" value="<?= $componente['IDCARRINHO'] ?>">
                <button type="submit" name="remover" class="product-x">X</button>
            </form>
            </div>
                <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
        </main>
    </div>

    <script>
        function toggleSelection(checkbox) {
            const card = checkbox.closest('.horizontal-card');
            if (checkbox.checked) {
                card.classList.add('selected');
            } else {
                card.classList.remove('selected');
            }
            updateCheckoutButton();
        }

        function updateCheckoutButton() {
            const checkboxes = document.querySelectorAll('.product-checkbox:checked');
            const count = checkboxes.length;
            const btn = document.getElementById('checkout');
            const countDisplay = document.getElementById('selectedCount');
            
            countDisplay.textContent = count;
            btn.disabled = count === 0;
        }

        function removeItem(button) {
            if (confirm('Deseja realmente remover este item do carrinho?')) {
                const card = button.closest('.horizontal-card');
                setTimeout(() => {
                    card.remove();
                    updateCheckoutButton();
                }, 300);
            }
        }

        function finalizarPedido() {
            const selected = document.querySelectorAll('.product-checkbox:checked');

            if (selected.length === 0) {
                alert('Selecione pelo menos um item!');
                return;
            }

            const itens = [];

            selected.forEach(checkbox => {
                const card = checkbox.closest('.horizontal-card');

                itens.push({
                    id: card.dataset.id,
                    nome: card.querySelector('.product-title').textContent
                });
            });

            localStorage.setItem("itensPedido", JSON.stringify(itens));

            window.location.href = "novopedido.php";
        }

        function buscar() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            const cards = document.querySelectorAll('.horizontal-card');
            
            cards.forEach(card => {
                const title = card.querySelector('.product-title').textContent.toLowerCase();
                const description = card.querySelector('.product-description').textContent.toLowerCase();
                const category = card.querySelector('.product-category').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm) || category.includes(searchTerm)) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        }
        document.head.appendChild(style);
    </script>
</body>
</html>
