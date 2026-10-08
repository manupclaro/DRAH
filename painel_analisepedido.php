<?php
require_once "config.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/* =========================================================
   VERIFICA SE O ID DO PEDIDO FOI INFORMADO
   ========================================================= */

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Pedido não informado.");
}

$idPedido = intval($_GET['id']);

$mensagem = "";
$tipoMensagem = "";

/* =========================================================
   PROCESSA AS AÇÕES DO ADMINISTRADOR
   ========================================================= */

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $acao = $_POST["acao"] ?? "";

    /* -----------------------------------------------------
       ACEITAR PEDIDO
       ----------------------------------------------------- */

    if ($acao === "aceitar") {

        /*
         * Primeiro verificamos se o pedido existe.
         */
        $sqlPedido = "
            SELECT STATUSPEDIDO
            FROM PEDIDO
            WHERE IDPEDIDO = ?
        ";

        $stmtPedido = mysqli_prepare($conexao, $sqlPedido);
        mysqli_stmt_bind_param($stmtPedido, "i", $idPedido);
        mysqli_stmt_execute($stmtPedido);

        $resultadoPedido = mysqli_stmt_get_result($stmtPedido);
        $pedidoAtual = mysqli_fetch_assoc($resultadoPedido);

        mysqli_stmt_close($stmtPedido);

        if (!$pedidoAtual) {

            $mensagem = "Pedido não encontrado.";
            $tipoMensagem = "erro";

        } else {

       if (
    $pedidoAtual["STATUSPEDIDO"] !== "Pendente" &&
    $pedidoAtual["STATUSPEDIDO"] !== "Pedido em Análise"
) {

    $mensagem = "Este pedido não está mais aguardando análise.";
    $tipoMensagem = "erro";

} else {  

               
                $sqlComponentes = "
                    SELECT
                        PC.IDCOMP,
                        PC.QUANTIDADE,
                        C.NOME,
                        C.QUANTIDADE AS ESTOQUE
                    FROM PEDIDO_COMP PC
                    INNER JOIN COMPONENTE C
                        ON C.IDCOMP = PC.IDCOMP
                    WHERE PC.IDPEDIDO = ?
                ";

                $stmtComp = mysqli_prepare($conexao, $sqlComponentes);
                mysqli_stmt_bind_param($stmtComp, "i", $idPedido);
                mysqli_stmt_execute($stmtComp);

                $resultadoComp = mysqli_stmt_get_result($stmtComp);

                $estoqueOK = true;
                $componenteSemEstoque = "";

                while ($comp = mysqli_fetch_assoc($resultadoComp)) {

                    if ($comp["QUANTIDADE"] > $comp["ESTOQUE"]) {
                        $estoqueOK = false;
                        $componenteSemEstoque =
                            $comp["NOME"] .
                            " (solicitado: " . $comp["QUANTIDADE"] .
                            ", disponível: " . $comp["ESTOQUE"] . ")";
                        break;
                    }
                }

                mysqli_stmt_close($stmtComp);

                /*
                 * Não deixa aprovar se não houver estoque.
                 */
                if (!$estoqueOK) {

                    $mensagem =
                        "Não é possível aprovar o pedido. " .
                        "Estoque insuficiente para: " .
                        $componenteSemEstoque . ".";

                    $tipoMensagem = "erro";

                } else {

                    /*
                     * TRANSACTION
                     *
                     * Garante que a aprovação e a baixa
                     * do estoque aconteçam juntas.
                     */
                    mysqli_begin_transaction($conexao);

                    try {

                        /*
                         * Busca novamente os componentes.
                         */
                        $sqlComponentes = "
                            SELECT IDCOMP, QUANTIDADE
                            FROM PEDIDO_COMP
                            WHERE IDPEDIDO = ?
                        ";

                        $stmtComp = mysqli_prepare(
                            $conexao,
                            $sqlComponentes
                        );

                        mysqli_stmt_bind_param(
                            $stmtComp,
                            "i",
                            $idPedido
                        );

                        mysqli_stmt_execute($stmtComp);

                        $resultadoComp =
                            mysqli_stmt_get_result($stmtComp);

                        $componentes = [];

                        while ($comp = mysqli_fetch_assoc($resultadoComp)) {
                            $componentes[] = $comp;
                        }

                        mysqli_stmt_close($stmtComp);

                        /*
                         * Baixa o estoque e registra no histórico.
                         */
                        foreach ($componentes as $comp) {

                            $idComp = $comp["IDCOMP"];
                            $quantidade = $comp["QUANTIDADE"];

                            $sqlEstoque = "
                                UPDATE COMPONENTE
                                SET QUANTIDADE = QUANTIDADE - ?
                                WHERE IDCOMP = ?
                            ";

                            $stmtEstoque = mysqli_prepare(
                                $conexao,
                                $sqlEstoque
                            );

                            mysqli_stmt_bind_param(
                                $stmtEstoque,
                                "ii",
                                $quantidade,
                                $idComp
                            );

                            if (!mysqli_stmt_execute($stmtEstoque)) {
                                throw new Exception(
                                    "Erro ao atualizar estoque."
                                );
                            }

                            mysqli_stmt_close($stmtEstoque);

                            /*
                             * Registra a saída no histórico.
                             */
                            $tipo = "SAIDA";
                            $justificativa =
                                "Saída referente ao pedido #" .
                                $idPedido;

                            $sqlHistorico = "
                                INSERT INTO HISTORICO_COMPONENTE
                                (
                                    IDCOMP,
                                    TIPO,
                                    QUANTIDADE,
                                    JUSTIFICATIVA
                                )
                                VALUES (?, ?, ?, ?)
                            ";

                            $stmtHist = mysqli_prepare(
                                $conexao,
                                $sqlHistorico
                            );

                            mysqli_stmt_bind_param(
                                $stmtHist,
                                "isis",
                                $idComp,
                                $tipo,
                                $quantidade,
                                $justificativa
                            );

                            if (!mysqli_stmt_execute($stmtHist)) {
                                throw new Exception(
                                    "Erro ao registrar histórico."
                                );
                            }

                            mysqli_stmt_close($stmtHist);
                        }

                        /*
                         * Atualiza o status do pedido.
                         */
                        $novoStatus = "Pedido Aprovado";

                        $sqlStatus = "
                            UPDATE PEDIDO
                            SET STATUSPEDIDO = ?
                            WHERE IDPEDIDO = ?
                        ";

                        $stmtStatus = mysqli_prepare(
                            $conexao,
                            $sqlStatus
                        );

                        mysqli_stmt_bind_param(
                            $stmtStatus,
                            "si",
                            $novoStatus,
                            $idPedido
                        );

                        if (!mysqli_stmt_execute($stmtStatus)) {
                            throw new Exception(
                                "Erro ao atualizar o pedido."
                            );
                        }

                        mysqli_stmt_close($stmtStatus);

                        mysqli_commit($conexao);

                        /*
                         * Redireciona para evitar reenvio do POST.
                         */
                        header(
                            "Location: painel_pedidos.php?id=" .
                            $idPedido .
                            "&sucesso=aprovado"
                        );

                        exit;

                    } catch (Exception $e) {

                        mysqli_rollback($conexao);

                        $mensagem =
                            "Não foi possível aprovar o pedido. " .
                            $e->getMessage();

                        $tipoMensagem = "erro";
                    }
                }
            }
        }
    }


    /* -----------------------------------------------------
       REJEITAR PEDIDO
       ----------------------------------------------------- */

    elseif ($acao === "rejeitar") {

        $novoStatus = "Pedido Recusado";

        $sql = "
            UPDATE PEDIDO
            SET STATUSPEDIDO = ?
            WHERE IDPEDIDO = ?
        ";

        $stmt = mysqli_prepare($conexao, $sql);

        mysqli_stmt_bind_param(
            $stmt,
            "si",
            $novoStatus,
            $idPedido
        );

        if (mysqli_stmt_execute($stmt)) {

            mysqli_stmt_close($stmt);

            header(
                "Location: analise_pedido.php?id=" .
                $idPedido .
                "&sucesso=recusado"
            );

            exit;

        } else {

            $mensagem = "Erro ao recusar o pedido.";
            $tipoMensagem = "erro";

            mysqli_stmt_close($stmt);
        }
    }


    /* -----------------------------------------------------
       SOLICITAR ALTERAÇÃO
       ----------------------------------------------------- */

    elseif ($acao === "alterar") {

        $alteracao = trim($_POST["alteracao"] ?? "");

        if ($alteracao === "") {

            $mensagem =
                "Digite o que precisa ser alterado no pedido.";

            $tipoMensagem = "erro";

        } else {

            /*
             * Recupera as observações atuais para não apagar
             * informações que já estavam no pedido.
             */
            $sqlBusca = "
                SELECT OBSERVACOES
                FROM PEDIDO
                WHERE IDPEDIDO = ?
            ";

            $stmtBusca = mysqli_prepare(
                $conexao,
                $sqlBusca
            );

            mysqli_stmt_bind_param(
                $stmtBusca,
                "i",
                $idPedido
            );

            mysqli_stmt_execute($stmtBusca);

            $resultadoBusca =
                mysqli_stmt_get_result($stmtBusca);

            $dadosPedido =
                mysqli_fetch_assoc($resultadoBusca);

            mysqli_stmt_close($stmtBusca);

            $observacoesAntigas =
                $dadosPedido["OBSERVACOES"] ?? "";

            /*
             * Monta a nova observação.
             */
            $novaObservacao =
                $observacoesAntigas;

            if ($novaObservacao !== "") {
                $novaObservacao .= "\n\n";
            }

            $novaObservacao .=
                "[SOLICITAÇÃO DE ALTERAÇÃO DO ADMINISTRADOR]\n" .
                $alteracao;

            $novoStatus = "Pedido em Alteração";

            $sql = "
                UPDATE PEDIDO
                SET
                    STATUSPEDIDO = ?,
                    OBSERVACOES = ?
                WHERE IDPEDIDO = ?
            ";

            $stmt = mysqli_prepare(
                $conexao,
                $sql
            );

            mysqli_stmt_bind_param(
                $stmt,
                "ssi",
                $novoStatus,
                $novaObservacao,
                $idPedido
            );

            if (mysqli_stmt_execute($stmt)) {

                mysqli_stmt_close($stmt);

                header(
                    "Location: analise_pedido.php?id=" .
                    $idPedido .
                    "&sucesso=alteracao"
                );

                exit;

            } else {

                $mensagem =
                    "Erro ao solicitar alteração.";

                $tipoMensagem = "erro";

                mysqli_stmt_close($stmt);
            }
        }
    }
}


/* =========================================================
   MENSAGENS DE SUCESSO
   ========================================================= */

if (isset($_GET["sucesso"])) {

    switch ($_GET["sucesso"]) {

        case "aprovado":
            $mensagem = "Pedido aprovado com sucesso! ";
            $tipoMensagem = "sucesso";
            break;

        case "recusado":
            $mensagem = "Pedido recusado com sucesso! ";
            $tipoMensagem = "sucesso";
            break;

        case "alteracao":
            $mensagem =
                "Solicitação de alteração enviada com sucesso!";
            $tipoMensagem = "sucesso";
            break;
    }
}


/* =========================================================
   BUSCA OS DADOS COMPLETOS DO PEDIDO
   ========================================================= */

$sql = "
    SELECT
        P.IDPEDIDO,
        P.STATUSPEDIDO,
        P.JUSTIFICATIVA,
        P.OBSERVACOES,
        P.DATA_PEDIDO,
        P.DATA_RETIRADA,
        P.DATA_PREVIADEV,
        P.DATA_DEVOLUCAO,

        U.IDUSER,
        U.NOME,
        U.EMAIL,
        U.TELEFONE

    FROM PEDIDO P

    INNER JOIN USUARIO U
        ON U.IDUSER = P.IDUSER

    WHERE P.IDPEDIDO = ?
";

$stmt = mysqli_prepare($conexao, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $idPedido
);

mysqli_stmt_execute($stmt);

$resultado = mysqli_stmt_get_result($stmt);

$pedido = mysqli_fetch_assoc($resultado);

mysqli_stmt_close($stmt);


if (!$pedido) {
    die("Pedido não encontrado.");
}


/* =========================================================
   BUSCA OS COMPONENTES DO PEDIDO
   ========================================================= */

$sqlComponentes = "
    SELECT
        C.IDCOMP,
        C.NOME,
        C.IMAGEM,
        PC.QUANTIDADE

    FROM PEDIDO_COMP PC

    INNER JOIN COMPONENTE C
        ON C.IDCOMP = PC.IDCOMP

    WHERE PC.IDPEDIDO = ?
";

$stmtComp = mysqli_prepare(
    $conexao,
    $sqlComponentes
);

mysqli_stmt_bind_param(
    $stmtComp,
    "i",
    $idPedido
);

mysqli_stmt_execute($stmtComp);

$resultadoComp =
    mysqli_stmt_get_result($stmtComp);

$componentes = [];

while ($comp = mysqli_fetch_assoc($resultadoComp)) {
    $componentes[] = $comp;
}

mysqli_stmt_close($stmtComp);


/* =========================================================
   FORMATA DATAS
   ========================================================= */

function formatarData($data)
{
    if (empty($data)) {
        return "Não informado";
    }

    return date("d/m/Y", strtotime($data));
}


/* =========================================================
   DEFINE CLASSE DO STATUS
   ========================================================= */

$status = $pedido["STATUSPEDIDO"];

$statusClasse = "status";

if ($status === "Pedido Aprovado") {
    $statusClasse .= " aprovado";
} elseif ($status === "Pedido Recusado") {
    $statusClasse .= " recusado";
} elseif ($status === "Pedido em Alteração") {
    $statusClasse .= " alteracao";
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>
<title>Pedido #1024 | DRAH</title>

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

  /* CONTAINER DO PEDIDO */
  .container {
    width: 100%;
    flex: 1;
    padding: 8px 18px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }

  .pedido-card {
    width: 90%;
    max-width: 650px;
    background: white;
    border-left: 6px solid #006d77;
    border-radius: 16px;
    padding: 22px;
    margin-top: 18px;
  }

  .status {
    font-size: 16px;
    font-weight: 700;
    padding: 8px 14px;
    border-radius: 10px;
    background: #006d77;
    color: #E5FFFA;
    text-align: center;
    margin-bottom: 18px;
    border: 2px solid #006d77;
  }

  .pedido-card img {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #006d77;
    margin: 0 auto 10px auto;
    display: block;
  }

  .nome {
    text-align: center;
    font-size: 20px;
    font-weight: 700;
    color: #003F47;
    margin-bottom: 14px;
  }

  .detalhes p {
    font-size: 16px;
    margin: 8px 0 14px 0;
    color: #333;
  }
  .label {
    font-weight: 700;
    color: #006d77;
  }

  /* BOTÕES */
  .botoes {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-top: 26px;
  }

  .btn {
    background: #E5FFFA;
    color: #003F47;
    font-size: 16px;
    font-weight: 700;
    padding: 10px 26px;
    border-radius: 12px;
    border: 2px solid #006d77;
    cursor: pointer;
  }

  .btn:hover {
    background: #006d77;
    color: #E5FFFA;
  }

  /* BOX ALTERAÇÃO (inicia escondido) */
  .box-alterar {
    width: 90%;
    max-width: 650px;
    background: #f0fcfc;
    border-left: 6px solid #006d77;
    border-radius: 16px;
    padding: 22px;
    margin-top: 22px;
    display: none; /* começa invisível */
  }
  .box-alterar.aberto {
    display: block;
}

  textarea {
    width: 100%;
    height: 120px;
    padding: 12px;
    border-radius: 12px;
    border: 2px solid #006d77;
    font-size: 16px;
    resize: none;
  }

  .btn-enviar {
    margin-top: 18px;
    background: #E5FFFA;
    color: #006d77;
    font-size: 16px;
    font-weight: 700;
    padding: 10px 26px;
    border-radius: 12px;
    border: 2px solid #006d77;
    cursor: pointer;
    display: block;
    margin-left: auto;
    margin-right: auto;
  }

  .btn-enviar:hover {
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
<body>
  <!-- HEADER -->
    <header>
        <div class="logo">
        <a href="index_adm.php"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
        </div>
        <nav class="menu-superior">
            <a href="index_adm.php">Início</a>
            <a href="painel_pedidos.php">Pedidos</a>
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>



<div class="container">

    <h2 class="titulo">
         Detalhes do Pedido #<?= htmlspecialchars($pedido["IDPEDIDO"]) ?>
    </h2>


    <!-- MENSAGEM -->

    <?php if ($mensagem !== ""): ?>

        <div class="mensagem <?= $tipoMensagem ?>">
            <?= htmlspecialchars($mensagem) ?>
        </div>

    <?php endif; ?>


    <!-- CARD DO PEDIDO -->

    <div class="pedido-card">

        <div class="<?= $statusClasse ?>">

             Status:
            <?= htmlspecialchars($status) ?>

        </div>



        <div class="nome">

            <?= htmlspecialchars($pedido["NOME"]) ?>

        </div>


        <div class="detalhes">

            <p>
                <span class="label">Email:</span>
                <?= htmlspecialchars($pedido["EMAIL"] ?? "Não informado") ?>
            </p>


            <p>
                <span class="label">Telefone:</span>
                <?= htmlspecialchars($pedido["TELEFONE"] ?? "Não informado") ?>
            </p>


            <p>
                <span class="label">Data do pedido:</span>
                <?= formatarData($pedido["DATA_PEDIDO"]) ?>
            </p>


            <p>
                <span class="label">Data de retirada:</span>
                <?= formatarData($pedido["DATA_RETIRADA"]) ?>
            </p>


            <p>
                <span class="label">Data prévia de devolução:</span>
                <?= formatarData($pedido["DATA_PREVIADEV"]) ?>
            </p>


            <p>
                <span class="label">Data de devolução:</span>
                <?= formatarData($pedido["DATA_DEVOLUCAO"]) ?>
            </p>


            <p>
                <span class="label">Justificativa:</span>
                <?= htmlspecialchars(
                    $pedido["JUSTIFICATIVA"] ?? "Não informado"
                ) ?>
            </p>


            <p>
                <span class="label">Observações:</span>
                <?= nl2br(
                    htmlspecialchars(
                        $pedido["OBSERVACOES"] ?? "Nenhuma"
                    )
                ) ?>
            </p>

        </div>


        <!-- COMPONENTES -->

        <div class="componentes">

            <h3>
                Componentes solicitados
            </h3>


            <?php if (count($componentes) > 0): ?>

                <?php foreach ($componentes as $comp): ?>

                    <div class="componente">

                        <strong>
                            <?= htmlspecialchars($comp["NOME"]) ?>
                        </strong>

                        — Quantidade:
                        <strong>
                            <?= htmlspecialchars($comp["QUANTIDADE"]) ?>
                        </strong>

                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <p>
                    Nenhum componente encontrado.
                </p>

            <?php endif; ?>

        </div>

    </div>


    <!-- BOTÕES -->

    <?php if ($status === "Pendente"): ?>

        <div class="botoes">

            <!-- ACEITAR -->

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="aceitar"
                >

                <button
                    type="submit"
                    class="btn adm"
                    onclick="
                        return confirm(
                            'Tem certeza que deseja aceitar este pedido?'
                        );
                    "
                >
                     Aceitar
                </button>

            </form>


            <!-- ALTERAR -->

            <button
                type="button"
                class="btn adm"
                onclick="mostrarAlterar()"
            >
                Alterar
            </button>


            <!-- REJEITAR -->

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="rejeitar"
                >

                <button
                    type="submit"
                    class="btn adm"
                    onclick="
                        return confirm(
                            'Tem certeza que deseja rejeitar este pedido?'
                        );
                    "
                >
                    Rejeitar
                </button>

            </form>

        </div>


        <!-- BOX ALTERAÇÃO -->

        <div
            class="box-alterar"
            id="alterarBox"
        >

            <p
                style="
                    text-align:center;
                    font-size:18px;
                    font-weight:800;
                    color:#006d77;
                    margin-bottom:14px;
                "
            >
                Descreva a alteração solicitada:
            </p>


            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="alterar"
                >


                <textarea
                    name="alteracao"
                    required
                    placeholder="Ex: alterar a quantidade para 5, modificar a data de devolução..."
                ></textarea>


                <button
                    type="submit"
                    class="btn-enviar"
                >
                     Enviar alteração
                </button>

            </form>

        </div>

    <?php else: ?>

        <div
            style="
                margin-top:25px;
                text-align:center;
                color:#006d77;
                font-weight:700;
            "
        >
            Este pedido já foi analisado e não possui ações disponíveis.
        </div>

    <?php endif; ?>


    <footer>
        Copyright © 2026 - 2MB |
        DRAH - Devolução e Reserva de Aparelhos de Hardware
    </footer>

</div>


<script>

function mostrarAlterar() {

    const box =
        document.getElementById("alterarBox");

    if (box.classList.contains("aberto")) {

        box.classList.remove("aberto");

    } else {

        box.classList.add("aberto");

        box.scrollIntoView({
            behavior: "smooth",
            block: "center"
        });
    }
}

</script>

</body>

</html>
