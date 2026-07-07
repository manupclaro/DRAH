<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Componentes | DRAH</title>

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

  .logo img {
    height: 50px;
  }

  .menu-superior {
    display: flex;
    gap: 15px;
    align-items: center;
  }

  .menu-superior a {
    background: #00c2c7;
    color: white;
    padding: 10px 22px;
    border-radius: 20px;
    font-weight: 600;
    text-decoration: none;
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

  /* SEARCH */
  .search-bar {
    margin-top: 22px;
    display: flex;
    justify-content: center;
  }

  .search-bar input {
    width: 100%;
    max-width: 420px;
    padding: 10px 14px;
    border-radius: 30px;
    border: 2px solid #006d77;
    font-size: 16px;
  }

  /* GRID */
  .grid-quadrantes {
    margin-top: 25px;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 22px;
    width: 95%;
    max-width: 900px;
    align-items: start;
  }

  /* CARD */
  .component-card {
    background: white;
    border-left: 6px solid #006d77;
    border-radius: 16px;
    padding: 18px;

    display: flex;
    flex-direction: column;

    overflow: hidden;
  }

  /* IMAGEM */
  .component-card img {
    width: 100%;
    height: 180px;
    object-fit: cover;
    object-position: center;

    display: block;

    border-radius: 12px;
    border: 2px solid #00c2c7;
    margin-bottom: 12px;
  }

  .component-title {
    font-size: 18px;
    font-weight: 800;
    color: #003F47;
    margin-top: 12px;
  }

  .component-desc {
    font-size: 14px;
    color: #333;
    margin: 6px 0 10px;
    min-height: 40px;
  }

  .component-qty {
    font-size: 15px;
    font-weight: 700;
    color: #006d77;
  }

  .btn {
    width: 100%;
    margin-top: auto;
    background: #b7edea;
    color: #006d77;
    font-size: 15px;
    font-weight: 700;
    padding: 8px;
    border-radius: 20px;
    border: 2px solid #006d77;
    cursor: pointer;
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
        margin-bottom: 25px;
    }
</style>

<?php
include("config.php");

if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}
?>

</head>

<body>

<header>
  <div class="logo">
    <a href="index_adm.php">
      <img src="imagens/logo_branco.png">
    </a>
  </div>

  <nav class="menu-superior">
    <a href="paineladm.html">Painel ADM</a>
    <a href="logout.php">Logout</a>
  </nav>
</header>

<div class="container">

  <h2>Componentes Disponíveis</h2>

  <a href="painel_cadastrarcomp.php"
     style="margin-top:15px; background:#006d77; color:white; padding:10px 22px; border-radius:30px; text-decoration:none;">
    Criar novo componente
  </a>

  <div class="search-bar">
    <form method="GET">
      <input type="text" name="busca" placeholder="Pesquisar componentes...">
    </form>
  </div>

  <div class="grid-quadrantes">

<?php
$busca = $_GET['busca'] ?? '';

$sql = "SELECT * FROM COMPONENTE WHERE 1=1";

if (!empty($busca)) {
    $sql .= " AND (NOME LIKE '%$busca%' OR DESCRICAO LIKE '%$busca%' OR CATEGORIA LIKE '%$busca%')";
}

$result = $conexao->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
?>

<div class="component-card">

  <img src="componentes/<?php echo $row['IMAGEM']; ?>" alt="">

  <div class="component-title">
    <?php echo $row['NOME']; ?>
  </div>

  <div class="component-qty">
    Quantidade: <?php echo $row['QUANTIDADE']; ?>
  </div>

  <div class="component-desc">
    <?php echo $row['DESCRICAO']; ?>
  </div>

  <div style="font-size:12px; color:#666;">
    Categoria: <?php echo $row['CATEGORIA']; ?>
  </div>

 <button class="btn"
    onclick="window.location.href='painel_editarcomp.php?id=<?php echo $row['IDCOMP']; ?>'">
    Alterar componente
</button>

<button class="btn btn-delete"
   onclick="window.location.href='removercomp.php?id=<?php echo $row['IDCOMP']; ?>'">
    Remover componente
</button>

</div>

<?php
    }
} else {
    echo "<p>Nenhum componente encontrado.</p>";
}
?>

  </div>


<footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>
</body>
</html>
