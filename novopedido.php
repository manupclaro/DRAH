<?php
session_start();
include("config.php");

$idUser = $_SESSION['iduser'] ?? 0;
$carrinhoSelecionado = $_POST['carrinho'] ?? [];

// PROCESSAMENTO DO FORMULÁRIO
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['solicitarPedido'])) {
  
  /* VERIFICA SE EXISTEM COMPONENTES SELECIONADOS */
  if (empty($carrinhoSelecionado)) {
    die("Erro: nenhum componente foi selecionado.");}

  $justificativa = trim($_POST['justificativa'] ?? '');
  $observacoes = trim($_POST['observacoes'] ?? '');
  $dataRetirada = $_POST['data_retirada'] ?? '';
  $dataPrevia = $_POST['data_previadev'] ?? '';
  $quantidades = $_POST['quantidade'] ?? [];

  /* VERIFICA AS DATAS */
  $dataHoje = date('Y-m-d');

  if (empty($dataRetirada) || empty($dataPrevia)) {
    die("Erro: informe as datas de retirada e devolução.");}

  if ($dataRetirada < $dataHoje) {
    die("Erro: a data de retirada não pode ser anterior à data atual.");}

  if ($dataPrevia < $dataHoje) {
    die("Erro: a data de devolução não pode ser anterior à data atual.");}

  if ($dataPrevia < $dataRetirada) {
    die("Erro: a data de devolução não pode ser anterior à data de retirada.");}

  /* VERIFICA A JUSTIFICATIVA */
  if (empty($justificativa)) {
    die("Erro: informe uma justificativa.");}

  /* BUSCA E VALIDA TODOS OS COMPONENTES PRIMEIRO */
  $itensPedido = [];

  foreach ($carrinhoSelecionado as $idCarrinho) {
      $idCarrinho = (int)$idCarrinho;

      $sql = "SELECT 
                  C.IDCARRINHO,
                  C.IDCOMP,
                  C.QUANTIDADE AS QUANTIDADE_CARRINHO,
                  CP.NOME,
                  CP.QUANTIDADE AS ESTOQUE
              FROM CARRINHO C
              INNER JOIN COMPONENTE CP 
                  ON C.IDCOMP = CP.IDCOMP
              WHERE C.IDCARRINHO = ?
              AND C.IDUSER = ?";

      $stmt = $conexao->prepare($sql);
      $stmt->bind_param("ii", $idCarrinho, $idUser);
      $stmt->execute();

      $resultado = $stmt->get_result();
      $item = $resultado->fetch_assoc();

      /* Item não pertence ao usuário ou não existe */
      if (!$item) {
          die("Erro: um dos componentes selecionados não está mais disponível no carrinho.");
      }

      /* VERIFICA A QUANTIDADE DIGITADA */
      if (!isset($quantidades[$idCarrinho])) {
          die("Erro: informe a quantidade de todos os componentes.");
      }

      $quantidade = (int)$quantidades[$idCarrinho];

      if ($quantidade < 1) {
          die("Erro: a quantidade deve ser pelo menos 1.");
      }

      /* VERIFICA O ESTOQUE */
      if ($quantidade > $item['ESTOQUE']) {
          die(
              "Erro: o componente \"" . htmlspecialchars($item['NOME']) . "\" possui apenas " . $item['ESTOQUE'] . " unidade(s) disponível(is)."
          );
      }

      /* Guarda o item para usar depois */
      $itensPedido[] = [
          'idCarrinho' => $idCarrinho,
          'idComp' => $item['IDCOMP'],
          'quantidade' => $quantidade
      ];
  }


  /* TODAS AS VALIDAÇÕES PASSARAM */
  $conexao->begin_transaction();

  try {
      /* CRIA O PEDIDO */
      $sql = "INSERT INTO PEDIDO (
                  STATUSPEDIDO,
                  JUSTIFICATIVA,
                  OBSERVACOES,
                  DATA_PEDIDO,
                  DATA_RETIRADA,
                  DATA_PREVIADEV,
                  IDUSER)
              VALUES (
                  'Pendente',
                  ?,
                  ?,
                  NOW(),
                  ?,
                  ?,
                  ?)";

      $stmt = $conexao->prepare($sql);

      $stmt->bind_param(
          "ssssi",
          $justificativa,
          $observacoes,
          $dataRetirada,
          $dataPrevia,
          $idUser
      );

      if (!$stmt->execute()) {
          throw new Exception("Erro ao criar o pedido.");
      }

      $idPedido = $conexao->insert_id;

      /* INSERE OS COMPONENTES DO PEDIDO */
      foreach ($itensPedido as $item) {
          $sqlInsert = "INSERT INTO PEDIDO_COMP (IDPEDIDO, IDCOMP, QUANTIDADE)
                        VALUES (?, ?, ?)";

          $stmtInsert = $conexao->prepare($sqlInsert);

          $stmtInsert->bind_param(
              "iii",
              $idPedido,
              $item['idComp'],
              $item['quantidade']
          );

          if (!$stmtInsert->execute()) {
              throw new Exception("Erro ao adicionar componente ao pedido.");
          }
      }

      /* REMOVE OS ITENS DO CARRINHO */
      foreach ($itensPedido as $item) {
          $sqlDelete = "DELETE FROM CARRINHO
                        WHERE IDCARRINHO = ?
                        AND IDUSER = ?";

          $stmtDelete = $conexao->prepare($sqlDelete);

          $stmtDelete->bind_param(
              "ii",
              $item['idCarrinho'],
              $idUser
          );

          if (!$stmtDelete->execute()) {
              throw new Exception("Erro ao remover item do carrinho.");
          }
      }

      /* CONFIRMA TODAS AS OPERAÇÕES */
      $conexao->commit();

      /* Redireciona para evitar que F5 envie novamente o formulário. */
      header("Location: pedidos.php?sucesso=1");
      exit;
  } catch (Exception $e) {
      /* Se alguma coisa der errado, desfaz tudo. */
      $conexao->rollback();

      die("Erro ao cadastrar o pedido: " . $e->getMessage());
  }
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

  #mensagemErro {
    display: none;
    background: #ffe0e0;
    color: #b00000;
    border: 2px solid #ff6b6b;
    border-radius: 10px;
    padding: 10px;
    margin-bottom: 15px;
    text-align: center;
    font-weight: 600;
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

      <div id="mensagemErro"></div>
      <form method="POST" id="pedidoForm" onsubmit="return validarPedido()">
        <?php foreach ($carrinhoSelecionado as $idCarrinho) { ?>
        <input type="hidden" name="carrinho[]" value="<?= $idCarrinho ?>">

        <?php
        $sql = "SELECT C.*, CP.NOME, CP.IMAGEM, CP.QUANTIDADE AS ESTOQUE
                FROM CARRINHO C
                JOIN COMPONENTE CP ON C.IDCOMP = CP.IDCOMP
                WHERE C.IDCARRINHO = ?
                AND C.IDUSER = ?";

        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("ii", $idCarrinho, $idUser);
        $stmt->execute();

        $item = $stmt->get_result()->fetch_assoc();
        ?>

          <?php if ($item) { ?>
          <div class="item-pedido">
            <img src="componentes/<?= htmlspecialchars($item['IMAGEM']) ?>" width="100">
            <h3><?= htmlspecialchars($item['NOME']) ?></h3>

            <input type="number" class="quantidade" name="quantidade[<?= $idCarrinho ?>]"
              value="<?= $item['QUANTIDADE'] ?>" min="1" max="<?= $item['ESTOQUE'] ?>"
              data-estoque="<?= $item['ESTOQUE'] ?>" required>
          </div>
          <?php } ?>
        <?php } ?>

        <?php
        $dataHoje = date('Y-m-d');
        ?>
        <label>Data de retirada:</label>
        <input type="date" name="data_retirada" id="dataRetirada" min="<?= $dataHoje ?>" required>

        <label>Data de devolução:</label>
        <input type="date" name="data_previadev" id="dataDevolucao" min="<?= $dataHoje ?>" required>

        <label>Justificativa:</label>
        <textarea placeholder="Explique o uso do componente..." name="justificativa" required></textarea>

        <label>Observações:</label>
        <textarea placeholder="Alguma observação extra?" name="observacoes"></textarea>

        <button class="btn" type="submit" name="solicitarPedido">💾 Solicitar Pedido</button>
      </form>
    </div>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>

  <script>
  const form = document.getElementById("pedidoForm");
  const mensagemErro = document.getElementById("mensagemErro");

  const dataRetirada = document.getElementById("dataRetirada");
  const dataDevolucao = document.getElementById("dataDevolucao");

  // MOSTRAR ERRO
  function mostrarErro(mensagem) {
    mensagemErro.textContent = mensagem;
    mensagemErro.style.display = "block";
  }

  // ESCONDER ERRO
  function esconderErro() {
    mensagemErro.textContent = "";
    mensagemErro.style.display = "none";
  }

  // QUANTIDADE
  document.querySelectorAll(".quantidade").forEach(function(campo) {
    campo.addEventListener("input", function() {
      esconderErro();

      const estoque = parseInt(this.dataset.estoque);
      const quantidade = parseInt(this.value);

      if (quantidade > estoque) {
        mostrarErro("A quantidade solicitada é maior que o estoque disponível.");
        this.value = estoque;
      }
    });
  });

  // DATA DE RETIRADA
  dataRetirada.addEventListener("change", function() {
    esconderErro();

    // A devolução não pode ser antes da retirada
    dataDevolucao.min = this.value;

    // Se a devolução já escolhida ficou inválida
    if (dataDevolucao.value !== "" && dataDevolucao.value < this.value) {
      dataDevolucao.value = "";
      mostrarErro("A data de devolução deve ser igual ou posterior à data de retirada.");
    }
  });

  // DATA DE DEVOLUÇÃO
  dataDevolucao.addEventListener("change", function() {
    esconderErro();

    if (dataRetirada.value !== "" && this.value < dataRetirada.value) {
      mostrarErro("A data de devolução não pode ser anterior à data de retirada.");
      this.value = "";
    }
  });

  // VALIDAÇÃO ANTES DO ENVIO
  function validarPedido() {
    esconderErro();

    // VERIFICA SE EXISTEM COMPONENTES
    const quantidades = document.querySelectorAll(".quantidade");

    if (quantidades.length === 0) {
      mostrarErro("Não é possível criar um pedido sem componentes.");
      return false;
    }

    // VERIFICA ESTOQUE
    for (const campo of quantidades) {
      const quantidade = parseInt(campo.value);
      const estoque = parseInt(campo.dataset.estoque);

      if (isNaN(quantidade) || quantidade < 1) {
        mostrarErro("A quantidade deve ser de pelo menos 1 unidade.");
        campo.focus();
        return false;
      }

      if (quantidade > estoque) {
        mostrarErro("A quantidade solicitada não pode ser maior que o estoque disponível.");
        campo.focus();
        return false;
      }

      if (estoque <= 0) {
        mostrarErro("Um dos componentes selecionados está sem estoque.");
        return false;
      }
    }

    // VERIFICA DATA DE RETIRADA
    if (dataRetirada.value === "") {
      mostrarErro("Informe a data de retirada.");
      dataRetirada.focus();
      return false;
    }

    // VERIFICA DATA DE DEVOLUÇÃO
    if (dataDevolucao.value === "") {
      mostrarErro("Informe a data de devolução.");
      dataDevolucao.focus();
      return false;
    }

    // VERIFICA RELAÇÃO ENTRE AS DATAS
    if (dataDevolucao.value < dataRetirada.value) {
      mostrarErro("A data de devolução não pode ser anterior à data de retirada.");
      dataDevolucao.focus();
      return false;
    }

    return true;
  }
  </script>
</body>
</html>
