<?php
session_start();
include("config.php");

if ($conexao->connect_error) {
    die("Erro: " . $conexao->connect_error);
}

$mensagem = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $idComp = intval($_POST['idcomp'] ?? 0);
    $acao = $_POST['acao'] ?? '';

    if ($acao === 'desativar') {

        $justificativa = trim($_POST['justificativa'] ?? '');

        if ($idComp > 0 && !empty($justificativa)) {

            $sql = "UPDATE COMPONENTE
                    SET STATUS = 'DESATIVADO',
                        JUSTIFICATIVA = ?,
                        DATA_DESATIVACAO = NOW()
                    WHERE IDCOMP = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("si", $justificativa, $idComp);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $_SESSION['mensagem'] = "Componente desativado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Não foi possível desativar o componente.";
            }

            $stmt->close();

        } else {
            $_SESSION['mensagem'] = "Informe uma justificativa.";
        }

    } elseif ($acao === 'reativar') {

        if ($idComp > 0) {

            $sql = "UPDATE COMPONENTE
                    SET STATUS = 'ATIVO'
                    WHERE IDCOMP = ?";

            $stmt = $conexao->prepare($sql);
            $stmt->bind_param("i", $idComp);

            if ($stmt->execute() && $stmt->affected_rows > 0) {
                $_SESSION['mensagem'] = "Componente reativado com sucesso!";
            } else {
                $_SESSION['mensagem'] = "Não foi possível reativar o componente.";
            }

            $stmt->close();
        }
    }

    header("Location: painel_componentes.php");
    exit();
}

$mensagem = $_SESSION['mensagem'] ?? '';
unset($_SESSION['mensagem']);
?>
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

  .menu-superior a.active {
        background: white;
        color: #006d77;
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
    grid-template-columns: repeat(auto-fit, minmax(250px, 280px));
    justify-content: center;
    gap: 22px;
    width: 100%;
    max-width: 900px;
    align-items: start;
}

  /* CARD */
  .component-card img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    object-position: center;

    display: block;

    border-radius: 12px;
    border: 2px solid #00c2c7;
    margin-bottom: 12px;
    background: #f5f5f5;
    padding: 8px;
}

  /* IMAGEM */
  .component-card img {
    width: 100%;
    height: 180px;
    object-fit: contain;
    object-position: center;

    display: block;

    border-radius: 12px;
    border: 2px solid #00c2c7;
    margin-bottom: 12px;
    background: #f5f5f5;
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
    margin-top: 10px;
    background: #b7edea;
    color: #006d77;
    font-size: 15px;
    font-weight: 700;
    padding: 8px;
    border-radius: 20px;
    border: 2px solid #006d77;
    cursor: pointer;
}

.btn-delete {
    margin-top: 8px;
    background: #fff;
    color: #b3261e;
    border: 2px solid #b3261e;
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

    /* STATUS DO COMPONENTE */
.status {
    display: inline-block;
    padding: 6px 12px;
    border-radius: 15px;
    font-size: 12px;
    font-weight: bold;
    margin-top: 10px;
}

.status-ativo {
    background: #d4edda;
    color: #155724;
}

.status-desativado {
    background: #f8d7da;
    color: #721c24;
}

/* BOTÃO DESATIVAR */
.btn-delete {
    background: #fff;
    color: #b3261e;
    border: 2px solid #b3261e;
}

.btn-delete:hover {
    background: #b3261e;
    color: white;
}

/* BOTÃO REATIVAR */
.btn-reativar {
    background: #006d77;
    color: white;
    border: 2px solid #006d77;
}

/* POPUP */
.modal-desativar {
    display: none;
    position: fixed;
    z-index: 3000;
    inset: 0;
    background: rgba(0,0,0,0.6);
    justify-content: center;
    align-items: center;
}

.modal-box {
    background: white;
    padding: 30px;
    border-radius: 15px;
    width: 90%;
    max-width: 450px;
}

.modal-box h2 {
    color: #006d77;
    margin-bottom: 15px;
}

.modal-box textarea {
    width: 100%;
    min-height: 110px;
    padding: 12px;
    border: 2px solid #b7edea;
    border-radius: 10px;
    resize: vertical;
    font-family: inherit;
}

.modal-botoes {
    display: flex;
    gap: 10px;
}

.btn-cancelar {
    background: white;
    color: #333;
    border: 2px solid #999;
}

</style>


</head>

<body>

<!-- HEADER -->
<header>
    <div class="logo">
        <a href="index_adm.php">
            <img src="imagens/logo_branco.png" alt="DRAH">
        </a>
    </div>

    <nav class="menu-superior">
        <a href="index_adm.php">Início</a>
        <a href="painel_componentes.php" class="active">Componentes</a>
        <a href="painel_historiccomp.php">Histórico</a>
        <a href="paineladm.html">Painel ADM</a>
        <a href="logout.php">Logout</a>
    </nav>
</header>

<!-- CONTAINER PRINCIPAL -->
<div class="container">

    <!-- MENSAGEM DE RETORNO -->
    <?php if (!empty($mensagem)) { ?>

        <div style="
            background:#d4edda;
            color:#155724;
            padding:15px;
            border-radius:10px;
            margin-bottom:20px;
            font-weight:bold;
            width:100%;
            max-width:900px;
            text-align:center;
        ">
            <?php echo htmlspecialchars($mensagem); ?>
        </div>

    <?php } ?>

    <h2>Componentes Disponíveis</h2>

    <!-- CADASTRAR COMPONENTE -->
    <a href="painel_cadastrarcomp.php"
       style="
           margin-top:15px;
           background:#006d77;
           color:white;
           padding:10px 22px;
           border-radius:30px;
           text-decoration:none;
       ">
        Criar novo componente
    </a>

    <!-- PESQUISA -->
    <div class="search-bar">
        <form method="GET">
            <input
                type="text"
                name="busca"
                placeholder="Pesquisar componentes..."
                value="<?php echo htmlspecialchars($_GET['busca'] ?? ''); ?>"
            >
        </form>
    </div>

    <!-- GRID DE COMPONENTES -->
    <div class="grid-quadrantes">

        <?php

        $busca = $_GET['busca'] ?? '';

        $sql = "SELECT * FROM COMPONENTE WHERE 1=1";

        if (!empty($busca)) {
            $sql .= " AND (NOME LIKE ? OR DESCRICAO LIKE ? OR CATEGORIA LIKE ?)";
        }

        $stmt = $conexao->prepare($sql);

        if (!empty($busca)) {
            $pesquisa = "%$busca%";
            $stmt->bind_param("sss", $pesquisa, $pesquisa, $pesquisa);
        }

        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows > 0) {

            while ($row = $result->fetch_assoc()) {

                $status = $row['STATUS'] ?? 'ATIVO';

        ?>

        <!-- CARD DO COMPONENTE -->
        <div class="component-card">

            <!-- IMAGEM -->
            <img
                src="componentes/<?php echo htmlspecialchars($row['IMAGEM'] ?? ''); ?>"
                alt="<?php echo htmlspecialchars($row['NOME'] ?? ''); ?>"
            >

            <!-- NOME -->
            <div class="component-title">
                <?php echo htmlspecialchars($row['NOME'] ?? ''); ?>
            </div>

            <!-- QUANTIDADE -->
            <div class="component-qty">
                Quantidade:
                <?php echo (int)$row['QUANTIDADE']; ?>
            </div>

            <!-- DESCRIÇÃO -->
            <div class="component-desc">
                <?php echo htmlspecialchars($row['DESCRICAO'] ?? ''); ?>
            </div>

            <!-- CATEGORIA -->
            <div style="font-size:12px; color:#666;">
                Categoria:
                <?php echo htmlspecialchars($row['CATEGORIA'] ?? ''); ?>
            </div>

            <!-- STATUS DO COMPONENTE -->
            <?php if ($status === 'ATIVO') { ?>

                <span class="status status-ativo">
                    ● ATIVO
                </span>

            <?php } else { ?>

                <span class="status status-desativado">
                    ● DESATIVADO
                </span>

                <!-- JUSTIFICATIVA -->
                <div style="font-size:13px; margin-top:10px;">
                    <strong>Justificativa:</strong><br>

                    <?php
                    echo htmlspecialchars(
                        $row['JUSTIFICATIVA'] ?? 'Não informada'
                    );
                    ?>
                </div>

                <!-- DATA -->
                <div style="font-size:12px; color:#666; margin-top:5px;">
                    Desativado em:

                    <?php
                    echo !empty($row['DATA_DESATIVACAO'])
                        ? date('d/m/Y H:i', strtotime($row['DATA_DESATIVACAO']))
                        : 'Não informado';
                    ?>
                </div>

            <?php } ?>

            <!-- BOTÃO EDITAR -->
            <button
                type="button"
                class="btn"
                onclick="window.location.href='painel_editarcomp.php?id=<?php echo (int)$row['IDCOMP']; ?>'">

                Alterar componente

            </button>

            <!-- BOTÕES DE DESATIVAÇÃO -->
            <?php if ($status === 'ATIVO') { ?>

                <button
                    type="button"
                    class="btn btn-delete"
                    onclick="abrirModal(<?php echo (int)$row['IDCOMP']; ?>)">

                    Desativar componente

                </button>

            <?php } else { ?>

                <!-- REATIVAR -->
                <form method="POST">

                    <input
                        type="hidden"
                        name="idcomp"
                        value="<?php echo (int)$row['IDCOMP']; ?>"
                    >

                    <input
                        type="hidden"
                        name="acao"
                        value="reativar"
                    >

                    <button
                        type="submit"
                        class="btn btn-reativar">

                        Reativar componente

                    </button>

                </form>

            <?php } ?>

        </div>

        <?php

            }

        } else {

            echo "<p>Nenhum componente encontrado.</p>";

        }

        $stmt->close();

        ?>

    </div>

</div>

<!-- POPUP DE DESATIVAÇÃO -->
<div class="modal-desativar" id="modalDesativar">

    <div class="modal-box">

        <h2>Desativar componente</h2>

        <p style="margin-bottom:15px;">
            Informe o motivo pelo qual deseja desativar este componente:
        </p>

        <form method="POST">

            <!-- ID DO COMPONENTE -->
            <input
                type="hidden"
                name="idcomp"
                id="idCompModal"
            >

            <!-- AÇÃO -->
            <input
                type="hidden"
                name="acao"
                value="desativar"
            >

            <!-- JUSTIFICATIVA -->
            <textarea
                name="justificativa"
                placeholder="Digite a justificativa..."
                required
                maxlength="255"
            ></textarea>

            <div class="modal-botoes">

                <button
                    type="submit"
                    class="btn btn-delete">

                    Confirmar desativação

                </button>

                <button
                    type="button"
                    class="btn btn-cancelar"
                    onclick="fecharModal()">

                    Cancelar

                </button>

            </div>

        </form>

    </div>

</div>

<!-- RODAPÉ -->
<footer>
    Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware
</footer>

<!-- JAVASCRIPT -->
<script>

function abrirModal(id) {

    document.getElementById("idCompModal").value = id;

    document.getElementById("modalDesativar").style.display = "flex";

}

function fecharModal() {

    document.getElementById("modalDesativar").style.display = "none";

}

// FECHAR CLICANDO FORA DO POPUP
document.getElementById("modalDesativar").addEventListener("click", function(event) {

    if (event.target === this) {
        fecharModal();
    }

});

// FECHAR COM ESC
document.addEventListener("keydown", function(event) {

    if (event.key === "Escape") {
        fecharModal();
    }

});

</script>

</body>
</html>
