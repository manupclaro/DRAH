<?php
session_start();
include("config.php");
$carrinhoSelecionado = $_POST['carrinho'] ?? [];

if (empty($carrinhoSelecionado)) {
    die("Nenhum item selecionado.");
}
?>
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
    color: #ED5721;
    font-size: 23px;
    font-weight: 800;
    margin-bottom: 20px;
  }

  /* Imagem do componente */
  .preview {
    width: 140px;
    aspect-ratio: 1/1;
    border: 2px dashed #ff7f50;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin: 0 auto 6px auto;
    background: #fff2e5;
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
    background: #ED5721;
    color: white;
    border: none;
    width: 32px;
    height: 32px;
    font-size: 20px;
    border-radius: 10px;
    cursor: pointer;
  }

  .qty-btn:hover {
    background: #751F00;
  }

  .qty-num {
    font-size: 20px;
    font-weight: 700;
    color: #ED5721;
  }

  /* Campos */
  label {
    font-weight: 700;
    font-size: 15px;
    color: #ED5721;
    margin-top: 12px;
    display: block;
  }

  input, textarea {
    width: 100%;
    padding: 10px 14px;
    border-radius: 10px;
    border: 2px solid #ff7f50;
    margin-top: 6px;
    outline: none;
    font-size: 14px;
    box-sizing: border-box;
  }

  textarea {
    resize: none;
    height: 90px;
  }

  /* botão solicitar */
  .btn {
    width: 100%;
    background: #fff2e5;
    color: #ED5721;
    font-size: 15px;
    font-weight: 800;
    padding: 12px 18px;
    border-radius: 20px;
    border: 2px solid #ff7f50;
    cursor: pointer;
    margin-top: 24px;
  }

  .btn:hover {
    background: #ff7f50;
    color: white;
  }

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
        <a href="carrinho.php">Carrinho</a> 
        <a href="logout.php">Logout</a>  
    </nav>
  </header>

<!-- FORM -->
<div class="container">
  <div class="form-card">

  <h2>Novo Pedido</h2>
  <div id="listaItens"></div>

    <form method="POST">
    <?php
    foreach ($carrinhoSelecionado as $idCarrinho) {
        echo '<input type="hidden" name="carrinho[]" value="'.$idCarrinho.'">';
        $sql = "SELECT C.*, CP.NOME, CP.IMAGEM
        FROM CARRINHO C
        JOIN COMPONENTE CP ON C.IDCOMP = CP.IDCOMP
        WHERE C.IDCARRINHO = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("i", $idCarrinho);
        $stmt->execute();

        $item = $stmt->get_result()->fetch_assoc();

        echo '
        <div class="item-pedido">
            <img src="'.$item['IMAGEM'].'" width="100">
            <h3>'.$item['NOME'].'</h3>

            <input
                type="number"
                name="quantidade['.$idCarrinho.']"
                value="'.$item['QUANTIDADE'].'"
                min="1">

            <input
                type="hidden"
                name="carrinho[]"
                value="'.$idCarrinho.'">
        </div>';
    }
    ?>

      <label>Data de retirada:</label>
      <input type="date" name="data_retirada"/>

      <label>Data de devolução:</label>
      <input type="date" name="data_previadev"/>

      <label>Justificativa:</label>
      <textarea placeholder="Explique o uso do componente..." name="justificativa"></textarea>

      <label>Observações:</label>
      <textarea placeholder="Alguma observação extra?" name="observacoes"></textarea>

      <button class="btn" type="submit" name="solicitarPedido">💾 Solicitar Pedido</button>
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
const itens = JSON.parse(localStorage.getItem("itensPedido")) || [];

const lista = document.getElementById("listaItens");

if (itens.length > 0) {

    let html = "<h3>Itens do Pedido</h3><ul>";

    itens.forEach(item => {
        html += `<li>${item.nome}</li>`;
    });

    html += "</ul>";

    lista.innerHTML = html;
}
</script>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitarPedido'])) {

    $idUser = $_SESSION['iduser'];

    $justificativa = $_POST['justificativa'];
    $observacoes = $_POST['observacoes'];
    $dataRetirada = $_POST['data_retirada'];
    $dataPrevia = $_POST['data_previadev'];

    $carrinhoSelecionado = $_POST['carrinho'] ?? [];

    // cria pedido

    $sql = "
        INSERT INTO PEDIDO
        (
            STATUSPEDIDO,
            JUSTIFICATIVA,
            OBSERVACOES,
            DATA_PEDIDO,
            DATA_RETIRADA,
            DATA_PREVIADEV,
            IDUSER
        )
        VALUES
        (
            'Pendente',
            ?,
            ?,
            NOW(),
            ?,
            ?,
            ?
        )
    ";

    $stmt = $conexao->prepare($sql);

    $stmt->bind_param(
        "ssssi",
        $justificativa,
        $observacoes,
        $dataRetirada,
        $dataPrevia,
        $idUser
    );

    $stmt->execute();
    $idPedido = $conexao->insert_id;

    foreach ($carrinhoSelecionado as $idCarrinho){
      $sql = "SELECT * FROM CARRINHO
      WHERE IDCARRINHO = ?";

      $stmt = $conexao->prepare($sql);
      $stmt->bind_param("i", $idCarrinho);
      $stmt->execute();

      $item = $stmt->get_result()->fetch_assoc();

      $idComp = $item['IDCOMP'];
      $quantidade = $_POST['quantidade'][$idCarrinho];

      $sqlInsert = "INSERT INTO PEDIDO_COMP (IDPEDIDO, IDCOMP, QUANTIDADE)
      VALUES
      (?, ?, ?)";

      $stmtInsert = $conexao->prepare($sqlInsert);

      $stmtInsert->bind_param(
          "iii",
          $idPedido,
          $idComp,
          $quantidade
      );

      $stmtInsert->execute();
  }

  // remove do carrinho depois de fazer o pedido
  foreach ($carrinhoSelecionado as $idCarrinho) {
    $sqlDelete = "DELETE FROM CARRINHO
    WHERE IDCARRINHO = ?";

    $stmtDelete = $conexao->prepare($sqlDelete);
    $stmtDelete->bind_param("i", $idCarrinho);
    $stmtDelete->execute();
  }

    echo "Pedido cadastrado com sucesso";
}
?>
</body>
</html>
