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

    .menu-superior a.active {
        background: white;
        color: #006d77;
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
    background: #00c2c7;
    color: white;
    cursor: pointer;
}
.search-box button:hover {
    padding: 12px 20px;
    border-radius: 20px;
    border: none;
    background: #006d77;
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
    border: 2px solid #006d77;
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
.product-badge {
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
        color: #666;
        text-align: center;
        margin-top: 25px;
    }
</style>
</head>
<body>
    <!-- HEADER -->
    <header>
        <div class="logo">
        <a href="index_adm.php"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
        </div>
        <nav class="menu-superior">
            <a href="index_adm.php">Início</a>
            <a href="perfil_adm.html">Perfil</a>
            <a href="seuspedidos_adm.html">Meus Pedidos</a>
            <a href="carrinho_adm.html" class="active">Carrinho</a>
            <a href="paineladm.html">Painel ADM</a>
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
            <div class="products-grid" id="productsGrid">
                
                <!-- CARD LATERAL 1 -->
                <div class="product-card horizontal-card" data-id="1">
                    <div class="product-checkbox-container">
                        <input type="checkbox" class="product-checkbox" onchange="toggleSelection(this)">
                    </div>
                    <button class="product-badge" onclick="removeItem(this)">×</button>
                    <div class="product-image-horizontal">
                        <img src="c:\Users\breno\Downloads\ledverde (1).png"
                             alt="Imagem do LED"
                             style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>
                    <div class="product-content-horizontal">
                        <span class="product-category">Eletrônicos</span>
                        <div class="product-title">LED - Verde</div>
                        <div class="product-description">Led verde.</div>
                        <div class="product-meta">
                            <div>Estoque: <b>2</b></div>
                        </div>
                    </div>
                </div>

                <!-- CARD LATERAL 2 -->
                <div class="product-card horizontal-card" data-id="2">
                    <div class="product-checkbox-container">
                        <input type="checkbox" class="product-checkbox" onchange="toggleSelection(this)">
                    </div>
                    <button class="product-badge" onclick="removeItem(this)">×</button>
                    <div class="product-image-horizontal">
                        <img src="c:\Users\breno\Downloads\cabosata (1).jpeg" 
                             alt="Imagem do Cabo SATA"
                             style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>
                    <div class="product-content-horizontal">
                        <span class="product-category">Cabos</span>
                        <div class="product-title">Cabo Sata Serial Ata, Sata 3gb/s Cor Vermelho</div>
                        <div class="product-description">Cabo sata.</div>
                        <div class="product-meta">
                            <div>Estoque: <b>1</b></div>
                        </div>
                    </div>
                </div>

                <!-- CARD LATERAL 3 -->
                <div class="product-card horizontal-card" data-id="3">
                    <div class="product-checkbox-container">
                        <input type="checkbox" class="product-checkbox" onchange="toggleSelection(this)">
                    </div>
                    <button class="product-badge" onclick="removeItem(this)">×</button>
                    <div class="product-image-horizontal">
                        <img src="c:\Users\breno\Downloads\arduino (1).png" 
                             alt="Imagem do Arduíno"
                             style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>
                    <div class="product-content-horizontal">
                        <span class="product-category">Eletrônicos</span>
                        <div class="product-title">Arduino Uno R3 Smd</div>
                        <div class="product-description">
                            O Arduino Uno R3 é uma placa de prototipagem eletrônica baseada no microcontrolador ATmega328P, amplamente utilizada para projetos de eletrônica e programação.
                        </div>
                        <div class="product-meta">
                            <div>Estoque: <b>3</b></div>
                        </div>
                    </div>
                </div>

                <!-- CARD LATERAL 4 -->
                <div class="product-card horizontal-card" data-id="4">
                    <div class="product-checkbox-container">
                        <input type="checkbox" class="product-checkbox" onchange="toggleSelection(this)">
                    </div>
                    <button class="product-badge" onclick="removeItem(this)">×</button>
                    <div class="product-image-horizontal">
                        <img src="c:\Users\breno\Downloads\processador.png" 
                             alt="Imagem do Processador"
                             style="width: 100%; height: 100%; object-fit: contain; border-radius: 12px;">
                    </div>
                    <div class="product-content-horizontal">
                        <span class="product-category">Processadores</span>
                        <div class="product-title">Intel Core i7-12700K</div>
                        <div class="product-description">
                            Processador de 12ª geração com 12 núcleos e 20 threads, ideal para workstations.
                        </div>
                        <div class="product-meta">
                            <div>Estoque: <b>1</b></div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- BOTÃO DE FINALIZAR PEDIDO -->
            <div class="checkout-container">
                <div class="checkout-info">
                    <span id="selectedCount">0</span> itens selecionados
                </div>
                <button class="checkout-button" id="checkoutBtn" onclick="finalizarPedido()" disabled>
                    🛒 Fazer Pedido
                </button>
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
            const btn = document.getElementById('checkoutBtn');
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
                alert('Selecione pelo menos um item para fazer o pedido!');
                return;
            }

            const items = [];
            selected.forEach(checkbox => {
                const card = checkbox.closest('.horizontal-card');
                const title = card.querySelector('.product-title').textContent;
                items.push(title);
            });

            alert(`Pedido realizado com sucesso!\n\nItens selecionados:\n${items.map((item, i) => `${i + 1}. ${item}`).join('\n')}`);
            
            // Remover itens selecionados após o pedido
            selected.forEach(checkbox => {
                checkbox.closest('.horizontal-card').remove();
            });
            updateCheckoutButton();
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
    </script>
</body>
</html>
