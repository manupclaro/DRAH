<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Novo Pedido | DRAH</title>

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

  /* CONTAINER */
  .container {
    width: 100%;
    flex: 1;
    padding: 30px 18px;
    display: flex;
    justify-content: center;
  }

  .form-card {
    width: 90%;
    max-width: 520px;
    background: white;
    border-radius: 16px;
    padding: 24px;
  }

  h2 {
    text-align: center;
    color: #006d77;
    font-size: 23px;
    font-weight: 800;
    margin-bottom: 20px;
  }

  /* Imagem do componente */
  .preview {
    width: 140px;
    aspect-ratio: 1/1;
    border: 2px dashed #00c2c7;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin: 0 auto 6px auto;
    background: #e5fffa;
  }

  .preview img {
    width: 100%;
    height: 100%;
    object-fit: contain;
  }

  /* Quantidade */
  .qty-box {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 10px 0 18px 0;
    gap: 14px;
  }

  .qty-btn {
    background: #006d77;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    font-size: 20px;
    border-radius: 10px;
    cursor: pointer;
  }

  .qty-btn:hover {
    background: #003F47;
  }

  .qty-num {
    font-size: 20px;
    font-weight: 700;
    color: #006d77;
  }

  /* Campos */
  label {
    font-weight: 700;
    font-size: 15px;
    color: #006d77;
    margin-top: 12px;
    display: block;
  }

  input, textarea {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 2px solid #00c2c7;
    margin-top: 6px;
    outline: none;
    font-size: 14px;
    box-sizing: border-box;
  }

  textarea {
    resize: none;
    height: 90px;
  }

  /* Botão salvar */
  .btn {
    width: 100%;
    background: #e5fffa;
    color: #003F47;
    font-size: 15px;
    font-weight: 800;
    padding: 12px 18px;
    border-radius: 20px;
    border: 2px solid #006d77;
    cursor: pointer;
    margin-top: 24px;
  }

  .btn:hover {
    background: #006d77;
    color: white;
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
      <a href="perfil_adm.php">Perfil</a>
      <a href="pedidos_adm.php">Meus Pedidos</a>
      <a href="carrinho_adm.php">Carrinho</a>
      <a href="paineladm.html">Painel ADM</a>
      <a href="logout.php">Logout</a>
    </nav>
  </header>

<!-- FORM -->
<div class="container">
  <div class="form-card">

    <h2>Novo Pedido</h2>

    <!-- imagem do componente -->
    <div class="preview">
      <img src="componentes/ledverde.png" alt="">
    </div>

    <!-- Quantidade -->
    <div class="qty-box">
      <button type="button" class="qty-btn" onclick="alterarQuantidade(-1)">−</button>
      <span class="qty-num" id="quantidade">1</span>
      <button type="button" class="qty-btn" onclick="alterarQuantidade(1)">+</button>
    </div>

    <form>

      <label>Data de retirada:</label>
      <input type="date"/>

      <label>Data de devolução:</label>
      <input type="date"/>

      <label>Justificativa:</label>
      <textarea placeholder="Explique o uso do componente..."></textarea>

      <label>Observações:</label>
      <textarea placeholder="Alguma observação extra?"></textarea>

      <button class="btn" type="submit">💾 Salvar pedido</button>
    </form>
  </div>
</div>
<footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
<script>
  function alterarQuantidade(v) {
    let q = document.getElementById("quantidade");
    let num = parseInt(q.innerText) + v;
    if (num < 1) num = 1;
    q.innerText = num;
  }
</script>
</body>
</html>
