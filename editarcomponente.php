<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Editar Componente | DRAH</title>

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

  /* CONTAINER FORM */
  .container {
    width: 100%;
    background: #E5FFFA;
    flex: 1;
    padding: 30px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .form-card {
    width: 90%;
    max-width: 500px;
    background: white;
    border-left: 6px solid #00c2c7;
    border-radius: 16px;
    padding: 22px;
    margin-top: 18px;
  }

  .form-card label {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: #006d77;
    margin-top: 12px;
  }

  .form-card input, .form-card textarea, .form-card select {
    width: 100%;
    padding: 10px 14px;
    margin-top: 6px;
    border-radius: 8px;
    border: 2px solid #006d77;
    font-size: 14px;
    outline: none;
    box-sizing: border-box;
    background-color: #E5FFFA;
  }

  textarea {
    resize: none;
    height: 100px;
  }

  /* imagem preview quadrada */
  .preview {
    width: 140px;
    aspect-ratio: 1/1;
    border: 2px dashed #006d77;
    border-radius: 12px;
    display: flex;
    justify-content: center;
    align-items: center;
    overflow: hidden;
    margin: 12px auto 4px auto;
    background: #E5FFFA;
  }
  .preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  /* botão */
  .btn {
    width: 100%;
    margin-top: 20px;
    background: #b7edea;
    color: #006d77;
    font-size: 15px;
    font-weight: 800;
    padding: 10px 18px;
    border-radius: 20px;
    border: 2px solid #006d77;
    cursor: pointer;
  }
  .btn:hover {
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

<?php
include("config.php");

if (!isset($_GET['id'])) {
    echo "ID não informado!";
    exit();
}

$id = intval($_GET['id']);

$sql = "SELECT * FROM COMPONENTE WHERE IDCOMP = $id";
$result = $conexao->query($sql);

if ($result->num_rows == 0) {
    echo "Componente não encontrado!";
    exit();
}

$dados = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Editar Componente</title>
</head>

<body>

<header>
  <div class="logo">
    <img src="logo.png" alt="Logo">
  </div>

  <div class="menu-superior">
    <a href="index.php">Início</a>
    <a href="listar.php">Componentes</a>
  </div>
</header>

<div class="container">

  <div class="form-card">
    <h2>Editar Componente</h2>

    <form action="editarcompbd.php" method="POST" enctype="multipart/form-data">

      <input type="hidden" name="id" value="<?php echo $dados['IDCOMP']; ?>">

      <label>Nome:</label>
      <input type="text" name="nome" value="<?php echo $dados['NOME']; ?>" required>

      <label>Descrição:</label>
      <textarea name="descricao" required><?php echo $dados['DESCRICAO']; ?></textarea>

      <label>Quantidade:</label>
      <input type="number" name="quantidade" value="<?php echo $dados['QUANTIDADE']; ?>" required>

      <label>Categoria:</label>
      <select name="categoria" required>
        <option value="Arduino" <?php if($dados['CATEGORIA']=="Arduino") echo "selected"; ?>>Arduino</option>
        <option value="Atuadores" <?php if($dados['CATEGORIA']=="Atuadores") echo "selected"; ?>>Atuadores</option>
        <option value="Componentes eletrônicos" <?php if($dados['CATEGORIA']=="Componentes eletrônicos") echo "selected"; ?>>Componentes eletrônicos</option>
        <option value="ESP32" <?php if($dados['CATEGORIA']=="ESP32") echo "selected"; ?>>ESP32</option>
        <option value="Sensores" <?php if($dados['CATEGORIA']=="Sensores") echo "selected"; ?>>Sensores</option>
        <option value="Shields" <?php if($dados['CATEGORIA']=="Shields") echo "selected"; ?>>Shields</option>
        <option value="Outros" <?php if($dados['CATEGORIA']=="Outros") echo "selected"; ?>>Outros</option>
      </select>

      <label>Imagem:</label>
      <input type="file" name="imagem">

      <button type="submit" class="btn">Salvar</button>

    </form>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>
</body>
</html>
