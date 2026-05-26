<?php
include("config.php");

$sql = "
SELECT 
    p.IDPEDIDO,
    p.STATUSPEDIDO,
    p.DATA_PREVIADEV,

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

WHERE p.STATUSPEDIDO = 'RETIRADO'

GROUP BY p.IDPEDIDO

ORDER BY p.DATA_PREVIADEV ASC
";

$result = mysqli_query($conexao, $sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Devolver Pedidos | DRAH</title>

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

  .container {
    width: 100%;
    padding: 8px 18px;
    flex: 1;
  }

  h2 {
    text-align: center;
    color: #006d77;
    font-size: 24px;
    margin-bottom: 20px;
    font-weight: 800;
  }

  .pedido-card {
    width: 90%;
    max-width: 750px;
    background: white;
    border-left: 6px solid #006d77;
    border-radius: 14px;
    padding: 18px;
    margin: 18px auto;
    cursor: pointer;
    position: relative;
  }
  .pedido-card:hover {
    border: 3px solid #00c2c7;
    border-left: 6px solid #006d77;
  }

  .status-prazo {
    font-size: 14px;
    font-weight: 700;
    padding: 6px 10px;
    border-radius: 8px;
    text-align: center;
    margin-bottom: 10px;
  }

  .dentro {background:#b7f5c2; color:#0a4d1e;}
  .atrasado {background:#ffbaba; color:#5a0000;}

  .label {font-weight:700; color:#006d77;}
  p {font-size:15px; color:#333; margin:5px 0;}

  .form-box {
    width: 90%;
    max-width: 750px;
    background: #E5FFFA;
    border-left: 6px solid #006d77;
    border-radius: 14px;
    padding: 20px;
    margin: 12px auto 26px auto;
    display: none;
  }

  select, textarea {
    width:100%;
    padding:10px;
    border-radius:10px;
    border:2px solid #006d77;
    margin-top:6px;
    font-size:15px;
  }

  .btn-confirmar {
    margin-top:14px;
    background:#006d77;
    color:white;
    font-size:16px;
    font-weight:700;
    padding:10px 20px;
    border:none;
    border-radius:10px;
    cursor:pointer;
    width:100%;
  }
  .btn-confirmar:hover {background:#003F47;}

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

<div class="container">
 <h2>↩ Devolver Pedidos</h2>

<div class="search-bar" style="display:flex; justify-content:center; margin-bottom:22px;">
    <input 
        type="text" 
        id="pesquisa"
        placeholder="🔍 Pesquisar pedidos..." 

        style="
            width:90%;
            max-width:500px;
            padding:12px;
            border-radius:12px;
            border:2px solid #006d77;
            font-size:15px;
            outline:none;
        "

        onkeyup="filtrarPedidos()"
    />
</div>

<?php

if(mysqli_num_rows($result) > 0){

while($pedido = mysqli_fetch_assoc($result)){

$dataPrevista = strtotime($pedido['DATA_PREVIADEV']);
$hoje = strtotime(date('Y-m-d'));

$classePrazo = ($dataPrevista < $hoje) ? "atrasado" : "dentro";

$statusTexto = ($dataPrevista < $hoje)
? "🔴 Atrasado"
: "🟢 Dentro do prazo";
?>

<div 
    class="pedido-card pedido-item"
    id="pedido<?php echo $pedido['IDPEDIDO']; ?>"
    onclick="mostrarForm('<?php echo $pedido['IDPEDIDO']; ?>')"
>

    <div class="status-prazo <?php echo $classePrazo; ?>">
        <?php echo $statusTexto; ?>
    </div>

    <p>
        <span class="label">Usuário:</span>
        <?php echo $pedido['NOME']; ?>
    </p>

    <p>
        <span class="label">Email:</span>
        <?php echo $pedido['EMAIL']; ?>
    </p>

    <p>
        <span class="label">Telefone:</span>
        <?php echo $pedido['TELEFONE']; ?>
    </p>

    <p>
        <span class="label">Devolução prevista:</span>

        <?php
        echo date(
            'd/m/Y',
            strtotime($pedido['DATA_PREVIADEV'])
        );
        ?>
    </p>

    <p>
        <span class="label">Componentes:</span>
        <?php echo $pedido['COMPONENTES']; ?>
    </p>

    <p>
        <span class="label">Quantidades:</span>
        <?php echo $pedido['QUANTIDADES']; ?>
    </p>

</div>

<div 
    class="form-box"
    id="form<?php echo $pedido['IDPEDIDO']; ?>"
></div>

<?php
}

}else{

echo "

<div class='sem-pedidos'>

    <h3>🎉 Nenhuma devolução pendente</h3>

    <p>
        Todos os pedidos já foram devolvidos.
    </p>

    <img src='imagens/sem_pedidos.png'>

</div>

";

}
?>
<footer>
Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware
</footer>
</div>

<script>

function mostrarForm(id){

    document
    .querySelectorAll('.form-box')
    .forEach(form => form.style.display = 'none');

    let formBox = document.getElementById("form" + id);

    formBox.innerHTML = `

        <p style="
            text-align:center;
            font-size:18px;
            font-weight:800;
            color:#006d77;
            margin-bottom:14px;
        ">
            Registrar devolução do pedido #${id}
        </p>

        <label class="label">
            Condição de devolução:
        </label>

        <select id="cond${id}">
            <option>Perfeito estado ✅</option>
            <option>Pequenos danos ⚠</option>
            <option>Danos graves ❗</option>
            <option>Faltando itens ⛔</option>
        </select>

        <label class="label">
            Observações:
        </label>

        <textarea 
            id="obs${id}"
            placeholder="Ex: veio sem caixa..."
        ></textarea>

        <button 
            class="btn-confirmar"
            onclick="confirmar(${id})"
        >
            ✅ Confirmar devolução
        </button>

    `;

    formBox.style.display = "block";

    formBox.scrollIntoView({
        behavior:'smooth',
        block:'center'
    });
}

function confirmar(id){

    alert("Pedido devolvido com sucesso! 🎉✅");

    document
    .getElementById("form" + id)
    .style.display = "none";
}

function filtrarPedidos(){

    let input = document
    .getElementById("pesquisa")
    .value
    .toLowerCase();

    let pedidos = document
    .querySelectorAll(".pedido-item");

    pedidos.forEach(pedido => {

        let texto = pedido.innerText.toLowerCase();

        if(texto.includes(input)){
            pedido.style.display = "block";
        }else{
            pedido.style.display = "none";
        }

    });
}

</script>


</body>
</html>
