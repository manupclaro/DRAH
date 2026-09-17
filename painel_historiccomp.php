<?php

include("config.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {

    $sqlComponente = "SELECT NOME
                      FROM COMPONENTE
                      WHERE IDCOMP = $id";

    $resultadoComponente = $conexao->query($sqlComponente);

    if ($resultadoComponente->num_rows == 0) {
        die("Componente não encontrado.");
    }

    $componente = $resultadoComponente->fetch_assoc();

    $sql = "SELECT *
            FROM HISTORICO_COMPONENTE
            WHERE IDCOMP = $id
            ORDER BY DATAHORA DESC";

} else {

    $sqlComponente = "";

    $sql = "SELECT
                H.*,
                C.NOME
            FROM HISTORICO_COMPONENTE H
            INNER JOIN COMPONENTE C
                ON C.IDCOMP = H.IDCOMP
            ORDER BY H.DATAHORA DESC";
}

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Histórico de Componentes | DRAH</title>

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
    padding-top: 80px;
}

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
}

.menu-superior a {
    background: #00c2c7;
    color: white;

    padding: 10px 22px;

    border-radius: 20px;

    font-weight: 600;

    text-decoration: none;
}

.container {
    background: #E5FFFA;
    min-height: calc(100vh - 80px);

    padding: 30px 20px;
}

.content {
    width: 95%;
    max-width: 1100px;

    margin: auto;
}

h2 {
    color: #006d77;
    margin-bottom: 20px;
}

.tabela-container {
    background: white;

    border-left: 6px solid #00c2c7;

    border-radius: 16px;

    padding: 20px;

    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: #006d77;
    color: white;
    padding: 12px;
    text-align: left;
}

td {
    padding: 12px;
    border-bottom: 1px solid #ddd;
}

tr:hover {
    background: #f1ffff;
}

.entrada {
    color: #248a43;
    font-weight: bold;
}

.saida {
    color: #c62828;
    font-weight: bold;
}

.justificativa {
    max-width: 350px;
}

.voltar {
    display: inline-block;

    margin-bottom: 20px;

    background: #006d77;
    color: white;

    padding: 10px 20px;

    border-radius: 20px;

    text-decoration: none;
}

footer {
    text-align: center;

    font-size: 12px;

    color: #666;

    margin: 25px;
}

</style>

</head>

<body>

<header>

    <div class="logo">
        <img src="imagens/logo_branco.png" alt="Logo">
    </div>

    <div class="menu-superior">
        <a href="index_adm.php">Início</a>
        <a href="painel_componentes.php">Componentes</a>
    </div>

</header>

<div class="container">

<div class="content">

    <?php if ($id > 0): ?>

        <a
            href="editarcomp.php?id=<?php echo $id; ?>"
            class="voltar">
            ← Voltar para edição
        </a>

        <h2>
            Histórico: <?php echo htmlspecialchars($componente['NOME']); ?>
        </h2>

    <?php else: ?>

        <h2>Histórico de movimentações</h2>

    <?php endif; ?>


    <div class="tabela-container">

        <table>

            <thead>

                <tr>

                    <?php if ($id == 0): ?>
                        <th>Componente</th>
                    <?php endif; ?>

                    <th>Data</th>
                    <th>Tipo</th>
                    <th>Quantidade</th>
                    <th>Justificativa</th>

                </tr>

            </thead>

            <tbody>

            <?php if ($resultado->num_rows > 0): ?>

                <?php while ($registro = $resultado->fetch_assoc()): ?>

                    <tr>

                        <?php if ($id == 0): ?>

                            <td>
                                <?php
                                echo htmlspecialchars(
                                    $registro['NOME']
                                );
                                ?>
                            </td>

                        <?php endif; ?>

                        <td>
                            <?php
                            echo date(
                                'd/m/Y H:i',
                                strtotime($registro['DATAHORA'])
                            );
                            ?>
                        </td>

                        <td>

                            <?php if ($registro['TIPO'] == 'ENTRADA'): ?>

                                <span class="entrada">
                                    ↑ ENTRADA
                                </span>

                            <?php else: ?>

                                <span class="saida">
                                    ↓ SAÍDA
                                </span>

                            <?php endif; ?>

                        </td>

                        <td>

                            <?php
                            echo $registro['QUANTIDADE'];
                            ?>

                        </td>

                        <td class="justificativa">

                            <?php

                            if (!empty($registro['JUSTIFICATIVA'])) {

                                echo htmlspecialchars(
                                    $registro['JUSTIFICATIVA']
                                );

                            } else {

                                echo "<span style='color:#999'>
                                        Sem justificativa
                                      </span>";
                            }

                            ?>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td
                        colspan="<?php echo $id > 0 ? 4 : 5; ?>"
                        style="text-align:center;padding:30px;"
                    >
                        Nenhuma movimentação registrada.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</div>

<footer>
    Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware
</footer>

</div>

</body>
</html>
