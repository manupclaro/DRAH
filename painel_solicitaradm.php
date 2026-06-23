<?php
include "listar_adm.php";
?>
<!DOCTYPE html>

<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>Solicitar Administrador | DRAH</title>

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
    color: #006d77;
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
    border-left: 6px solid #006d77;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
  }

  .user-info span {
    display: block;
    font-size: 15px;
    color: #006d77;
    font-weight: 600;
  }

  /* BOTÕES */
  .btn-group {
    margin-top: 10px;
    display: flex;
    gap: 12px;
  }

  .btn-ok, .btn-no {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 2px solid;
    font-weight: bold;
    cursor: pointer;
  }

  .btn-ok {
    border-color: #006d77;
    background: #E5FFFA;
    color: #006d77;
  }
  .btn-ok:hover {
    background: #006d77;
    color: white;
  }

  .btn-no {
    border-color: #ff7f50;
    background: #fff2e5;
    color: red;
  }
  .btn-no:hover {
    background: #ff7f50;
    color: white;
  }

  .btn-senha {
    background: #006d77;
    color: white;
    padding: 9px 18px;
    font-size: 14px;
    font-weight: bold;
    border-radius: 18px;
    border: none;
    cursor: pointer;
  }

  .senha-num {
    font-size: 18px;
    font-weight: 800;
    color: #003F47;
    background: white;
    padding: 6px 14px;
    border-radius: 10px;
    border: 2px solid #006d77;
  }

  .senha {
    padding: 8px 12px; 
    border: 2px solid #006d77; 
    border-radius: 20px;
    font-size: 15px;
    width: 120px;
    outline: none;
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
  <h2>Códigos de acesso</h2>

  <!-- PESQUISA -->
  <div class="search-bar" style="margin-top:18px;">
    <input type="text" placeholder="🔎 Pesquisar usuários...">
  </div>

<div class="senha-container" style="
margin-top:20px;
display:flex;
align-items:center;
gap:12px;
">

    <span class="senha-num" id="codigoAtual">
        Nenhum código gerado
    </span>

    <span id="statusCodigo" style="
    color:#006d77;
    font-weight:bold;
    ">
        Disponível
    </span>

    <button class="btn-senha" onclick="gerarCodigo()">
        Gerar Novo Código
    </button>

</div>


  <!-- LISTAGEM DE USUÁRIOS -->
 <div class="users-list">

<?php while($adm = $resultado->fetch_assoc()) { ?>

<div class="user-card">

    <div class="user-info">
        <span><strong>Nome:</strong> <?= htmlspecialchars($adm['NOME']) ?></span>

        <span><strong>CPF:</strong> <?= htmlspecialchars($adm['CPF']) ?></span>

        <span><strong>Email:</strong> <?= htmlspecialchars($adm['EMAIL']) ?></span>

        <span><strong>Telefone:</strong> <?= htmlspecialchars($adm['TELEFONE']) ?></span>

       
    </div>

</div>

<?php } ?>

</div>
  
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>

<script>
function gerarCodigo() {

    fetch('gerar_codigo.php')
    .then(response => response.text())
    .then(codigo => {

        document.getElementById('codigoAtual').innerText = codigo;
        document.getElementById('statusCodigo').innerText = 'Disponível';

    })
    .catch(() => {

        alert('Erro ao gerar código.');

    });

}
</script>

</body>
</html>
