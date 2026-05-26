<?php
include("config.php");

$sql = "

SELECT 

    p.IDPEDIDO,
    p.STATUSPEDIDO,
    p.OBSERVACOES,
    p.DATA_RETIRADA,
    p.DATA_DEVOLUCAO,

    u.NOME,
    u.EMAIL,
    u.TELEFONE,

    GROUP_CONCAT(c.NOME SEPARATOR ', ') AS COMPONENTES,
    GROUP_CONCAT(pc.QUANTIDADE SEPARATOR ', ') AS QUANTIDADES

FROM PEDIDO p

INNER JOIN USUARIO u
ON p.IDUSER = u.IDUSER

INNER JOIN PEDIDO_COMP pc
ON p.IDPEDIDO = pc.IDPEDIDO

INNER JOIN COMPONENTE c
ON pc.IDCOMP = c.IDCOMP

WHERE p.STATUSPEDIDO = 'DEVOLVIDO'

GROUP BY p.IDPEDIDO

ORDER BY p.DATA_DEVOLUCAO DESC

";

$result = mysqli_query($conexao, $sql);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Histórico de Pedidos | DRAH</title>

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

  /* CONTAINER DA LISTA */
  .content {
    width:100%;
    flex:1;
    padding:8px 18px;
    display:flex;
    flex-direction:column;
    align-items:center;
  }

  h2 {
    color:#006d77;
    font-size:24px;
    font-weight:800;
    margin-bottom:26px;
    text-align:center;
  }

  /* CARDS DOS PEDIDOS */
  .pedido-card {
    width:90%;
    max-width:750px;
    background:white;
    border-left:5px solid #006d77;
    border-radius:16px;
    padding:18px 22px;
    margin-bottom:20px;
  }

  .pedido-card img {
    width:60px;
    height:60px;
    border-radius:50%;
    object-fit:cover;
    border:3px solid #006d77;
    margin-bottom:10px;
  }

  .header-user {
    display:flex;
    align-items:center;
    gap:14px;
    margin-bottom:12px;
    justify-content:center;
  }

  .nome {
    font-size:18px;
    font-weight:800;
    color:#003F47;
  }

  .detalhes p {
    font-size:15px;
    margin:6px 0 12px 0;
    color:#333;
    text-align:center;
  }

  .label {
    font-weight:700;
    color:#006d77;
  }

  .status {
    text-align:center;
    background:#E5FFFA;
    color:#006d77;
    font-size:15px;
    font-weight:800;
    padding:6px 12px;
    border-radius:10px;
    margin-bottom:14px;
    width:fit-content;
    border:2px solid #006d77;
    margin-left:auto;
    margin-right:auto;
  }

  .obs {
    text-align:center;
    font-size:14px;
    font-style:italic;
    background:#E5FFFA;
    padding:8px;
    border-radius:8px;
    color:#222;
    margin-top:8px;
  }


.sem-historico{
    background:white;
    width:100%;
    max-width:700px;
    margin:40px auto;
    padding:40px 30px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.sem-historico h3{
    color:#006d77;
    font-size:30px;
    margin-bottom:10px;
}

.sem-historico p{
    color:#666;
    font-size:17px;
    margin-bottom:25px;
}

.sem-historico img{
    width:100%;
    max-width:320px;
    height:auto;
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

<!-- CONTEÚDO -->
<div class="content">
  <h2>📜 Histórico de Pedidos Finalizados</h2>

<?php

if(mysqli_num_rows($result) > 0){

while($pedido = mysqli_fetch_assoc($result)){

?>

<div class="pedido-card">

    <div class="status">
        🔁 Situação: Componente devolvido
    </div>

    <div class="header-user">

        <img 
            src="componentes/fotoperfil.jpg"
            alt="Foto usuário"
        />

        <div class="nome">
            <?php echo $pedido['NOME']; ?>
        </div>

    </div>

    <div class="detalhes">

        <p>
            <span class="label">Email:</span>
            <?php echo $pedido['EMAIL']; ?>
        </p>

        <p>
            <span class="label">Telefone:</span>
            <?php echo $pedido['TELEFONE']; ?>
        </p>

        <p>
            <span class="label">Componentes:</span>
            <?php echo $pedido['COMPONENTES']; ?>
        </p>

        <p>
            <span class="label">Quantidade:</span>
            <?php echo $pedido['QUANTIDADES']; ?>
        </p>

        <p>
            <span class="label">Retirada:</span>

            <?php
            echo date(
                'd/m/Y',
                strtotime($pedido['DATA_RETIRADA'])
            );
            ?>
        </p>

        <p>
            <span class="label">Devolução:</span>

            <?php
            echo date(
                'd/m/Y',
                strtotime($pedido['DATA_DEVOLUCAO'])
            );
            ?>
        </p>

        <p>
            <span class="label">Observações:</span>

            <?php
            echo $pedido['OBSERVACOES'];
            ?>
        </p>

    </div>

    <div class="obs">

        “Pedido finalizado e devolvido ao estoque.”

    </div>

</div>

<?php
}

}else{

echo "

<div class='sem-historico'>

    <h3>📭 Nenhum histórico encontrado</h3>

    <p>
        Ainda não existem pedidos finalizados.
    </p>

    <img src='imagens/sem_pedidos.png'>

</div>

";

}
?>
<footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>
</body>
</html>
