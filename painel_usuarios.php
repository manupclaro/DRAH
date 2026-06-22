<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Gerenciar Usuários | DRAH</title>

<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    margin: 0;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    background: #b7edea;
    color: #333;
    min-height: 100vh;
    padding-top: 80px;
    display: flex;
    flex-direction: column;
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
    background: #E5FFFA;
    flex: 1;
    padding: 30px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  h2 {
    text-align:center;
    color:#006d77;
    font-size:24px;
    font-weight:800;
  }

  /* PESQUISA */
  .search-bar input {
    width: 90%;
    max-width: 500px;
    padding: 10px 14px;
    border-radius: 30px;
    border: 2px solid #006d77;
    font-size: 16px;
    outline: none;
  }

  /* LISTAGEM DE USUÁRIOS */
  .users-list {
    margin-top: 25px;
    width: 95%;
    max-width: 900px;
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

  .user-card {
    background: white;
    border-left: 6px solid #00c2c7;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .user-info span {
    display: block;
    font-size: 15px;
    color: #003F47;
    font-weight: 600;
  }

  /* BOTÕES */
  .btn-group {
    margin-top: 10px;
    display: flex;
    gap: 12px;
  }

  .btn-edit, .btn-del {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 2px solid;
    font-weight: bold;
    cursor: pointer;
  }

  .btn-edit {
    border-color: #006d77;
    background: #E5FFFA;
    color: #006d77;
  }
  .btn-edit:hover {
    background: #006d77;
    color: white;
  }

  .btn-del {
    border-color: #ff7f50;
    background: #fff2e5;
    color: red;
  }
  .btn-del:hover {
    background: #ff7f50;
    color: white;
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
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
  </header>

<div class="container">
  <h2>Gerenciar Usuários</h2>
  <!-- PESQUISA -->
  <div class="search-bar" style="margin-top:18px;">
    <input type="text" placeholder="🔎 Pesquisar usuários...">
  </div>

  <!-- LISTAGEM DE USUÁRIOS -->
  <div class="users-list">

    <!-- CARD MODELO -->
    <div class="user-card">
      <div class="user-info">
        <span><strong>Nome:</strong> João da Silva</span>
        <span><strong>CPF:</strong> 123.456.789-00</span>
        <span><strong>Email:</strong> joao@example.com</span>
        <span><strong>Telefone:</strong> (11) 98765-4321</span>
      </div>

      <div class="btn-group">
        <button class="btn-edit" onclick="window.location.href='formeditaarusuario.html'">✏ Editar</button>
        <button class="btn-del">🗑 Excluir</button>
      </div>
    </div>

    <div class="user-card">
      <div class="user-info">
        <span><strong>Nome:</strong> Maria Andrade</span>
        <span><strong>CPF:</strong> 987.654.321-11</span>
        <span><strong>Email:</strong> maria@exemplo.com</span>
        <span><strong>Telefone:</strong> (51) 99876-1111</span>
      </div>

      <div class="btn-group">
        <button class="btn-edit" onclick="window.location.href='formeditaarusuario.html'">✏ Editar</button>
        <button class="btn-del">🗑 Excluir</button>
      </div>
    </div>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>
</body>
</html>
