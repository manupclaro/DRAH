<?php
include("config.php");

$sql = "
SELECT 
    p.IDPEDIDO,
    p.STATUSPEDIDO,
    p.DATA_RETIRADA,

    u.NOME,
    u.EMAIL,
    u.TELEFONE,

    GROUP_CONCAT(c.NOME SEPARATOR ', ') AS COMPONENTES

FROM PEDIDO p

INNER JOIN USUARIO u
ON p.IDUSER = u.IDUSER

INNER JOIN PEDIDO_COMP pc
ON p.IDPEDIDO = pc.IDPEDIDO

INNER JOIN COMPONENTE c
ON pc.IDCOMP = c.IDCOMP

WHERE p.STATUSPEDIDO = 'PENDENTE'

GROUP BY p.IDPEDIDO

ORDER BY p.DATA_PEDIDO ASC
";

$result = mysqli_query($conexao, $sql);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Avaliar Pedidos | DRAH</title>

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

    h2 {
        text-align: center;
        color: #006d77;
        font-size: 24px;
        margin-bottom: 20px;
        font-weight: 800;
    }

    /* LISTA DE PEDIDOS */
    .pedido {
        width: 92%;
        max-width: 860px;
        background: white;
        border-radius: 12px;
        padding: 16px;
        margin: 16px auto;
        display: flex;
        align-items: center;
        gap: 14px;
    }

    .pedido img {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #006d77;
    }

    .info {
        display: flex;
        flex-direction: column;
    }

    .info .nome {
        font-size: 17px;
        font-weight: 700;
        color: #006d77;
    }

    .info .contato {
        font-size: 14px;
        color: #666;
    }

    .extra {
        margin-left: auto;
        text-align: right;
        font-size: 14px;
    }

    .label {
        font-weight: 700;
        color: #006d77;
    }

    .btn.adm {
    background: #00c2c7;
    color: #003F47;
    font-size: 16px;
    font-weight: 700;
    padding: 8px 22px;
    border-radius: 19px;
    border: 2px solid #00c2c7;
    cursor: pointer;
    margin-top: 14px; 

}

.btn.adm:hover {
    background: #006d77;
    color: #e5ffff;
     border: 2px solid #006d77;
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
    .sem-pedidos{
    background:white;
    width:100%;
    max-width:700px;
    margin:40px auto;
    padding:40px 30px;
    border-radius:20px;
    text-align:center;
    box-shadow:0 4px 12px rgba(0,0,0,0.08);
}

.sem-pedidos h3{
    color:#006d77;
    font-size:30px;
    margin-bottom:10px;
}

.sem-pedidos p{
    color:#666;
    font-size:17px;
    margin-bottom:25px;
}

.sem-pedidos img{
    width:100%;
    max-width:320px;
    height:auto;
    opacity:0.95;
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

<!-- LISTAGEM -->
<div class="container">
   <h2>⚠️ Pedidos Pendentes para Análise</h2>

<?php
if(mysqli_num_rows($result) > 0){

    while($pedido = mysqli_fetch_assoc($result)){
?>

<div class="pedido">

    <!-- FOTO PADRÃO -->
    <img src="Componentes/fotoperfil.jpg" alt="Foto usuário"/>

    <div class="info">

        <span class="nome">
            <?php echo $pedido['NOME']; ?>
        </span>

        <span class="contato">
            📧 <?php echo $pedido['EMAIL']; ?>
        </span>

        <span class="contato">
            📱 <?php echo $pedido['TELEFONE']; ?>
        </span>

        <button 
            class="btn"
            onclick="location.href='analise_pedido.php?id=<?php echo $pedido['IDPEDIDO']; ?>'">
            Analisar
        </button>

    </div>

    <div class="extra">

        <p>
            <span class="label">Componentes:</span>
            <?php echo $pedido['COMPONENTES']; ?>
        </p>

        <p>
            <span class="label">Retirada:</span>

            <?php
            if($pedido['DATA_RETIRADA']){
                echo date('d/m/Y', strtotime($pedido['DATA_RETIRADA']));
            } else {
                echo "Não definida";
            }
            ?>
        </p>

    </div>

</div>

<?php
    }

} else {

    echo "
<div class='sem-pedidos'>

    <h3>Nenhum pedido pendente</h3>

    <p>
        Todos os pedidos já foram analisados no momento.
    </p>

    <img src='imagens/sem_pedidos.png' alt='Nenhum pedido'>

</div>
";
}
?>
<footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</div>
</body>
</html>
