<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pedido #1024 | DRAH</title>

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
        padding-top: 140px;
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

  /* CONTAINER DO PEDIDO */
  .container {
    width: 100%;
    flex: 1;
    padding: 8px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .pedido-card {
    width: 90%;
    max-width: 650px;
    background: white;
    border-left: 6px solid #006d77;
    border-radius: 16px;
    padding: 22px;
    margin-top: 18px;
  }

  .status {
    font-size: 16px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 10px;
    background: #006d77;
    color: #E5FFFA;
    text-align: center;
    margin-bottom: 18px;
    border: 2px solid #006d77;
  }

  .pedido-card img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #006d77;
    margin: 0 auto 10px auto;
    display: block;
  }

  .nome {
    text-align: center;
    font-size: 20px;
    font-weight: 700;
    color: #003F47;
    margin-bottom: 14px;
  }

  .detalhes p {
    font-size: 16px;
    margin: 8px 0 14px 0;
    color: #333;
  }
  .label {
    font-weight: 700;
    color: #006d77;
  }

  /* BOTÕES */
  .botoes {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 26px;
  }

  .btn {
    background: #E5FFFA;
    color: #003F47;
    font-size: 16px;
    font-weight: 700;
    padding: 10px 26px;
    border-radius: 12px;
    border: 2px solid #006d77;
    cursor: pointer;
  }

  .btn:hover {
    background: #006d77;
    color: #E5FFFA;
  }

  /* BOX ALTERAÇÃO (inicia escondido) */
  .box-alterar {
    width: 90%;
    max-width: 650px;
    background: #f0fcfc;
    border-left: 6px solid #006d77;
    border-radius: 16px;
    padding: 22px;
    margin-top: 22px;
    display: none; /* começa invisível */
  }

  textarea {
    width: 100%;
    height: 120px;
    padding: 12px;
    border-radius: 12px;
    border: 2px solid #006d77;
    font-size: 16px;
    resize: none;
  }

  .btn-enviar {
    margin-top: 18px;
    background: #E5FFFA;
    color: #006d77;
    font-size: 16px;
    font-weight: 700;
    padding: 10px 26px;
    border-radius: 12px;
    border: 2px solid #006d77;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  .btn-enviar:hover {
    background: #006d77;
    color: #E5FFFA;
  }

  /* RODAPÉ */
  footer {
    bottom: 15px;
    font-size: 12px;
    color: #666;
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
        <a href="index_adm.php"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
        </div>
        <nav class="menu-superior">
            <a href="painel_pedidos.php">Pedidos</a>
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

<div class="container">
  <h2 style="text-align:center; color:#006d77; font-size:24px; font-weight:800;">📦 Detalhes do Pedido</h2>

  <!-- CARD INFORMAÇÕES -->
  <div class="pedido-card">
    <div class="status">📌 Status: Pendente de análise</div>
    <img src="componentes/fotoperfil.jpg" alt="Perfil usuário"/>
    <div class="nome">Lucas Almeida</div>

    <div class="detalhes">
      <p><span class="label">Email:</span> lucas@email.com</p>
      <p><span class="label">Telefone:</span> (48) 99988-1234</p>
      <p><span class="label">Data de retirada:</span> 15-12-2025</p>
      <p><span class="label">Data de devolução:</span> 23-12-2025</p>
      <p><span class="label">Componentes:</span> Arduino UNO</p>
      <p><span class="label">Quantidade:</span> 2</p>
      <p><span class="label">Justificativa:</span> Projeto escolar de automação</p>
      <p><span class="label">Observações:</span> Revisar antes da entrega</p>
    </div>
  </div>

  <!-- BOTÕES DE AÇÃO -->
  <div class="botoes">
    <button class="btn adm" onclick="alert('Pedido aceito com sucesso! ✅'); history.back();">✅ Aceitar</button>
    <button class="btn adm" onclick="mostrarAlterar()">✏ Alterar</button>
    <button class="btn adm" onclick="alert('Pedido rejeitado! ❌'); history.back();">⛔ Rejeitar</button>
  </div>

  <!-- BOX PARA ALTERAR PEDIDO -->
  <div class="box-alterar" id="alterarBox">
    <p style="text-align:center; font-size:18px; font-weight:800; color:#006d77; margin-bottom:14px;">
      Descreva a alteração solicitada:
    </p>
    <textarea placeholder="Ex: alterar a quantidade para 5, modificar data de devolução..."></textarea>

    <button class="btn-enviar" onclick="alert('Alteração enviada! ✏✅'); document.getElementById('alterarBox').style.display='none';">
      📥 Enviar alteração
    </button>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>

<script>
  function mostrarAlterar() {
    document.getElementById("alterarBox").style.display = "block";
  }
</script>
</body>
</html>
