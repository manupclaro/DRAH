<?php
include("config.php");


$sql = "
SELECT 
    p.IDPEDIDO,
    p.STATUSPEDIDO,
    p.JUSTIFICATIVA,
    p.OBSERVACOES,
    p.DATA_RETIRADA,
    p.DATA_PREVIADEV,
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

WHERE 
    p.STATUSPEDIDO IN ('APROVADO', 'RETIRADO')

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
<title>Painel de Pedidos | DRAH</title>

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

    /* SUB NAV */
    .subnav {
        position: fixed;
        top: 80px;
        left: 0;
        width: 100%;

        display: flex;
        justify-content: center;
        gap: 18px;
        padding: 10px 0;

        background: #b7edea;
        z-index: 999;
    }


    .subnav a {
        background: #e5ffff;
        color: #006d77;
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 700;
    }

    .subnav a:hover {
        background: #006d77;
        color: white;
    }

    /* CONTAINER PRINCIPAL */
    .container {
        width: 100%;
        background: LightCyan;
        padding: 26px 18px;
        flex: 1;
    }

    h2 {
        text-align: center;
        color: #006d77;
        font-size: 24px;
        margin-bottom: 22px;
        font-weight: 700;
    }

    /* CARDS */
    .pedido-card {
        width: 90%;
        max-width: 700px;
        background: MintCream;
        border-left: 6px solid #006d77;
        border-radius: 14px;
        padding: 18px;
        margin: 22px auto;
    }

    /* STATUS */
    .status {
        font-size: 15px;
        font-weight: 800;
        padding: 6px 12px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 14px;
    }

    .aproveitado {
        background: #a8e6e9;
        color: #03484d;
    }

    .pendente {
        background: #ffddc1;
        color: #6b2f00;
    }

    .proximo {
        background: #fff3e6;
        color: #b35a00;
    }

    .label {
        font-weight: 700;
        color: #006d77;
    }

    p {
        font-size: 15px;
        color: #333;
        margin: 6px 0;
    }

    /* RODAPÉ */
    footer {
        bottom: 15px;
        font-size: 12px;
        color: #999;
        text-align: center;
        margin-top: 25px;
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

<!-- SUB NAV -->
<div class="subnav">
    <a href="painel_avaliarpedidos.php">✅ Avaliar Pedidos</a>
    <a href="painel_historicopedidos.php">📜 Histórico de Pedidos</a>
    <a href="painel_devolverpedidos.php">↩ Devolver Pedidos</a>
</div>

<!-- CONTEÚDO -->
<div class="container">
   <h2>📦 Pedidos em andamento</h2>

<?php
while($pedido = mysqli_fetch_assoc($result)){

    $statusClass = "pendente";
    $statusTexto = "⏳ A preparar para retirada";

    // verifica se já retirou e está aguardando devolução
    if($pedido['STATUSPEDIDO'] == 'RETIRADO'){
        $statusClass = "proximo";
        $statusTexto = "⚠ Aguardando devolução";
    }
?>

<div class="pedido-card">

    <div class="status <?php echo $statusClass; ?>">
        <?php echo $statusTexto; ?>
    </div>

    <p>
        <span class="label">Código do Pedido:</span>
        #<?php echo $pedido['IDPEDIDO']; ?>
    </p>

    <p>
        <span class="label">Usuário:</span>
        <?php echo $pedido['NOME']; ?>
    </p>

    <p>
        <span class="label">Contato:</span>
        <?php echo $pedido['EMAIL']; ?>
        |
        <?php echo $pedido['TELEFONE']; ?>
    </p>

    <p>
        <span class="label">Retirada:</span>
        <?php echo date('d/m/Y', strtotime($pedido['DATA_RETIRADA'])); ?>
    </p>

    <p>
        <span class="label">Devolução prevista:</span>
        <?php echo date('d/m/Y', strtotime($pedido['DATA_PREVIADEV'])); ?>
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
        <span class="label">Justificativa:</span>
        <?php echo $pedido['JUSTIFICATIVA']; ?>
    </p>

    <p>
        <span class="label">Observações:</span>
        <?php echo $pedido['OBSERVACOES']; ?>
    </p>

    <?php if($pedido['STATUSPEDIDO'] == 'APROVADO'){ ?>

        <button>
            ✅ Confirmar entrega do pedido
        </button>

    <?php } ?>

    <?php if($pedido['STATUSPEDIDO'] == 'RETIRADO'){ ?>

        <button>
            ♻ Confirmar devolução do pedido
        </button>

    <?php } ?>

</div>

<?php } ?>

</div>
<footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</body>
</html>
