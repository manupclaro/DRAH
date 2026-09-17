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
 <html lang="pt-BR"> 
 <head> 
  <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0"> 
   <title>Editar Componente | DRAH</title>
    <style> * { margin: 0; 
      padding: 0;
       box-sizing: border-box;
     } body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background: #b7edea; 
      color: #333; 
      min-height: 100vh; 
      padding-top: 80px; 
     } header 
   { position: fixed; 
    top: 0; 
   left: 0; 
 width: 100%; 
height: 80px; 
display: flex; 
align-items: center;
 justify-content: space-between; padding: 0 32px; background: #006d77; z-index: 1000; } .logo { display: flex; align-items: center; gap: 15px; } .logo img { height: 50px; width: auto; } .menu-superior { display: flex; gap: 15px; align-items: center; } .menu-superior a { background: #00c2c7; color: white; padding: 10px 22px; border-radius: 20px; font-weight: 600; text-decoration: none; } .menu-superior a:hover { background: #005860; } .container { width: 100%; min-height: calc(100vh - 80px); background: #E5FFFA; padding: 30px 18px; display: flex; flex-direction: column; align-items: center; } .form-card { width: 90%; max-width: 550px; background: white; border-left: 6px solid #00c2c7; border-radius: 16px; padding: 25px; margin-top: 18px; } .form-card h2 { color: #006d77; margin-bottom: 20px; } .form-card label { display: block; font-size: 15px; font-weight: 700; color: #006d77; margin-top: 14px; } .form-card input, .form-card textarea, .form-card select { width: 100%; padding: 10px 14px; margin-top: 6px; border-radius: 8px; border: 2px solid #006d77; font-size: 14px; outline: none; background-color: #E5FFFA; } .form-card input:focus, .form-card textarea:focus, .form-card select:focus { border-color: #00c2c7; } textarea { resize: vertical; min-height: 100px; } .quantidade-atual { background: #e5fffa; border: 2px solid #006d77; border-radius: 10px; padding: 15px; margin: 10px 0 5px 0; text-align: center; } .quantidade-atual span { display: block; font-size: 13px; color: #666; } .quantidade-atual strong { display: block; font-size: 30px; color: #006d77; margin-top: 4px; } .movimentacao { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 10px; } .movimentacao div { padding: 15px; border-radius: 12px; } .entrada { background: #e8f8e8; border: 2px solid #3a9d5d; } .saida { background: #fff0f0; border: 2px solid #d9534f; } .entrada label { color: #287a44; } .saida label { color: #b52b27; } .entrada input { border-color: #3a9d5d; background: white; } .saida input { border-color: #d9534f; background: white; } .preview { width: 140px; aspect-ratio: 1/1; border: 2px dashed #006d77; border-radius: 12px; display: flex; justify-content: center; align-items: center; overflow: hidden; margin: 12px auto 4px auto; background: #E5FFFA; } .preview img { width: 100%; height: 100%; object-fit: cover; } .info { margin-top: 12px; padding: 12px; background: #f4f4f4; border-radius: 8px; font-size: 13px; color: #666; } .btn { width: 100%; margin-top: 20px; background: #b7edea; color: #006d77; font-size: 15px; font-weight: 800; padding: 11px 18px; border-radius: 20px; border: 2px solid #006d77; cursor: pointer; } .btn:hover { background: #006d77; color: #E5FFFA; } .btn-historico { display: block; width: 100%; margin-top: 10px; background: white; color: #006d77; font-size: 14px; font-weight: 700; padding: 10px 18px; border-radius: 20px; border: 2px solid #006d77; text-align: center; text-decoration: none; } .btn-historico:hover { background: #006d77; color: white; } footer { font-size: 12px; color: #666; text-align: center; margin: 25px; } @media (max-width: 600px) { .movimentacao { grid-template-columns: 1fr; } header { padding: 0 15px; } .menu-superior { gap: 5px; } .menu-superior a { padding: 8px 12px; font-size: 13px; } } 
</style>
 </head>
  <body> 
    <header> 
      <div class="logo">
       <img src="logo.png" alt="Logo">
        </div> 
        <div class="menu-superior">
         <a href="index_adm.php">Início</a>
          <a href="painel_componentes.php">Componentes</a>
           </div>
            </header>
            <div class="container">
             <div class="form-card">
              <h2>Editar Componente</h2>
               <form action="painel_editarcompbd.php" method="POST" enctype="multipart/form-data"> 
                <input type="hidden" name="id" value="<?php echo $dados['IDCOMP']; ?>">
                 <label>Nome:</label>
                  <input type="text" name="nome" value="<?php echo htmlspecialchars($dados['NOME']); ?>" required> 
                 <label>Descrição:</label>
                  <textarea name="descricao" required><?php echo htmlspecialchars($dados['DESCRICAO']); ?></textarea>
                   <label>Quantidade atual:</label>
                    <div class="quantidade-atual">
                     <span>Estoque disponível</span> 
                     <strong><?php echo $dados['QUANTIDADE']; ?> </strong>
                      </div> <div class="movimentacao"> 
                        <div class="entrada"> <label>Adicionar</label> 
                          <input type="number" name="entrada" min="0" value="0" placeholder="Ex.: 10"> </div> 
                          <div class="saida"> 
                            <label>Retirar</label> 
                            <input type="number" name="saida" min="0" max="<?php echo $dados['QUANTIDADE']; ?>" value="0" placeholder="Ex.: 2"> </div> 
                          </div> <label>Justificativa:</label> 
                          <textarea name="justificativa" placeholder="Opcional. Ex.: Compra de novos componentes, componente danificado, empréstimo, etc.">  </textarea> 
                          <div class="info"> A quantidade atual será atualizada automaticamente conforme a entrada ou saída informada. </div>
                           <label>Categoria:</label> 
                           <select name="categoria" required> 
                            <option value="Arduino" <?php if($dados['CATEGORIA']=="Arduino") echo "selected"; ?>> Arduino </option> 
                            <option value="Atuadores" <?php if($dados['CATEGORIA']=="Atuadores") echo "selected"; ?>> Atuadores </option>
                             <option value="Componentes eletrônicos" <?php if($dados['CATEGORIA']=="Componentes eletrônicos") echo "selected"; ?>> Componentes eletrônicos </option> 
                             <option value="ESP32" <?php if($dados['CATEGORIA']=="ESP32") echo "selected"; ?>> ESP32 </option>
                              <option value="Sensores" <?php if($dados['CATEGORIA']=="Sensores") echo "selected"; ?>> Sensores </option>
                               <option value="Shields" <?php if($dados['CATEGORIA']=="Shields") echo "selected"; ?>> Shields </option>
                                <option value="Outros" <?php if($dados['CATEGORIA']=="Outros") echo "selected"; ?>> Outros </option> 
                              </select> <label>Imagem:</label> 
                              <input type="file" name="imagem">
                               <button type="submit" class="btn"> Salvar alterações </button>
                                <a href="historico_componentes.php?id=<?php echo $dados['IDCOMP']; ?>" class="btn-historico"> Ver histórico deste componente </a>
                                 </form>
                                  </div> 
                                  <footer> Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware </footer>
                                   </div> 
                                 </body> 
                                 </html>
