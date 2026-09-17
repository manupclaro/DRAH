
<?php

require_once 'config.php';


// =====================================================
// ALTERAR TIPO DO USUÁRIO
// =====================================================

if (isset($_POST['alterar_tipo'])) {

    $iduser = intval($_POST['iduser']);
    $novo_tipo = intval($_POST['novo_tipo']);

    $sql = "UPDATE USUARIO 
            SET TIPOUSUA = ?
            WHERE IDUSER = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $novo_tipo, $iduser);

    if ($stmt->execute()) {

        header("Location: painel_usuarios.php");
        exit;

    } else {

        echo "Erro ao alterar o tipo do usuário.";

    }

    $stmt->close();
}


// =====================================================
// DESATIVAR USUÁRIO
// =====================================================

if (isset($_POST['desativar_usuario'])) {

    $iduser = intval($_POST['iduser']);


    // Verifica se o usuário possui pedido em andamento

    $sql = "SELECT COUNT(*) AS total
            FROM PEDIDO
            WHERE IDUSER = ?
            AND STATUSPEDIDO IN (
                'Pedido em Análise',
                'Pedido Aprovado',
                'Pedido Retirado'
            )";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $iduser);
    $stmt->execute();

    $resultado_pedido = $stmt->get_result();
    $dados_pedido = $resultado_pedido->fetch_assoc();

    $stmt->close();


    // Se houver pedido em andamento, não permite desativar

    if ($dados_pedido['total'] > 0) {

        echo "<script>
                alert('Este usuário não pode ser desativado pois possui pedido em andamento.');
                window.location.href='painel_usuarios.php';
              </script>";

        exit;
    }


    // Apenas desativa o usuário.
    // O registro continua no banco para preservar o histórico.

    $sql = "UPDATE USUARIO
            SET ATIVO = 0
            WHERE IDUSER = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $iduser);

    if ($stmt->execute()) {

        header("Location: painel_usuarios.php");
        exit;

    } else {

        echo "Erro ao desativar usuário.";

    }

    $stmt->close();
}


// =====================================================
// REATIVAR USUÁRIO
// =====================================================

if (isset($_POST['reativar_usuario'])) {

    $iduser = intval($_POST['iduser']);

    $sql = "UPDATE USUARIO
            SET ATIVO = 1
            WHERE IDUSER = ?";

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $iduser);

    if ($stmt->execute()) {

        header("Location: painel_usuarios.php");
        exit;

    } else {

        echo "Erro ao reativar usuário.";

    }

    $stmt->close();
}


// =====================================================
// BUSCAR USUÁRIOS
// =====================================================

$sql = "SELECT 
            IDUSER,
            NOME,
            EMAIL,
            TELEFONE,
            CPF,
            TIPOUSUA,
            ATIVO
        FROM USUARIO
        ORDER BY ATIVO DESC, NOME ASC";

$resultado = $conexao->query($sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>

<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width, initial-scale=1.0"/>

<title>Gerenciar Usuários</title>

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

  h2 {
    text-align:center;
    color:#006d77;
    font-size:24px;
    font-weight:800;
  }

  /* PESQUISA */
  .search-bar input {
    width: 90%;
    max-width: 500px;
    padding: 10px 14px;
    border-radius: 30px;
    border: 2px solid #006d77;
    font-size: 16px;
    outline: none;
  }

  /* LISTAGEM DE USUÁRIOS */
  .users-list {
    margin-top: 25px;
    width: 95%;
    max-width: 900px;
    display: flex;
    flex-direction: column;
    gap: 18px;
  }

 .user-card {
    background: white;
    border-left: 6px solid #00c2c7;
    border-radius: 14px;
    padding: 16px;
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.user-card.adm {
    border-left-color: #00c2c7;
}

.user-card.padrao {
    border-left-color: #ff7f50;
}

.user-card.inativo {
    border-left-color: #999;
}

  .user-info span {
    display: block;
    font-size: 15px;
    color: #003F47;
    font-weight: 600;
  }
.btn-reativar {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 2px solid #006d77;
    background: #E5FFFA;
    color: #006d77;
    font-weight: bold;
    cursor: pointer;
}

.btn-reativar:hover {
    background: #006d77;
    color: white;
}
  /* BOTÕES */
  .btn-group {
    margin-top: 10px;
    display: flex;
    gap: 12px;
  }

  .btn-edit, .btn-del {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 2px solid;
    font-weight: bold;
    cursor: pointer;
  }

  .btn-edit {
    border-color: #006d77;
    background: #E5FFFA;
    color: #006d77;
  }
  .btn-edit:hover {
    background: #006d77;
    color: white;
  }
  .btn-tipo {
    flex: 1;
    padding: 10px;
    border-radius: 20px;
    border: 2px solid #006d77;
    background: #E5FFFA;
    color: #006d77;
    font-weight: bold;
    cursor: pointer;
}

.btn-tipo:hover {
    background: #006d77;
    color: white;
}

  .btn-del {
    border-color: #ff7f50;
    background: #fff2e5;
    color: red;
  }
  .btn-del:hover {
    background: #ff7f50;
    color: white;
  }

.btn-eye {
    border: none;
    background: transparent;
    cursor: pointer;
    font-size: 16px;
    padding: 2px 5px;
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
            <a href="painel_usuarios.php" class="active">Usuários</a>
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
  </header>

<div class="container">


    <h2>Gerenciar Usuários</h2>


    <!-- PESQUISA -->

    <div class="search-bar" style="margin-top:18px;">

        <input
            type="text"
            id="pesquisa"
            placeholder="🔎 Pesquisar usuários..."
            onkeyup="pesquisarUsuarios()"
        >

    </div>


    <!-- LISTAGEM -->

    <div class="users-list" id="listaUsuarios">


        <?php if ($resultado && $resultado->num_rows > 0): ?>


            <?php while ($usuario = $resultado->fetch_assoc()): ?>


                <?php

                /*
                 * DEFINIR A COR DA LINHA
                 *
                 * Inativo = cinza
                 * ADM = ciano
                 * Padrão = laranja
                 */

                if ($usuario['ATIVO'] == 0) {

                    $classeCard = "inativo";

                    $tipoTexto = "Usuário inativo";

                    $tipoClasse = "tipo-inativo";

                } elseif ($usuario['TIPOUSUA'] == 1) {

                    $classeCard = "adm";

                    $tipoTexto = "Administrador";

                    $tipoClasse = "tipo-adm";

                } else {

                    $classeCard = "padrao";

                    $tipoTexto = "Usuário padrão";

                    $tipoClasse = "tipo-padrao";

                }


                /*
                 * MASCARAR CPF
                 */

                $cpf = $usuario['CPF'];

                if (!empty($cpf)) {

                    $cpfMascarado =
                        substr($cpf, 0, 3) .
                        ".***.***-" .
                        substr($cpf, -2);

                } else {

                    $cpfMascarado = "Não informado";

                }

                ?>


                <!-- CARD DO USUÁRIO -->

                <div
                    class="user-card <?php echo $classeCard; ?>"
                    data-nome="<?php echo strtolower($usuario['NOME']); ?>"
                    data-email="<?php echo strtolower($usuario['EMAIL']); ?>"
                    data-telefone="<?php echo strtolower($usuario['TELEFONE']); ?>"
                >


                    <div class="user-info">


                        <!-- NOME -->

                        <span>

                            <strong>Nome:</strong>

                            <?php
                            echo htmlspecialchars($usuario['NOME']);
                            ?>

                        </span>


                        <!-- EMAIL -->

                        <span>

                            <strong>Email:</strong>

                            <?php
                            echo htmlspecialchars($usuario['EMAIL']);
                            ?>

                        </span>


                        <!-- TELEFONE -->

                        <span>

                            <strong>Telefone:</strong>

                            <?php
                            echo htmlspecialchars($usuario['TELEFONE']);
                            ?>

                        </span>


                        <!-- CPF -->

                        <span>

                            <strong>CPF:</strong>

                            <span class="cpf-container">

                                <span
                                    class="cpf"
                                    data-real="<?php echo htmlspecialchars($cpf); ?>"
                                    data-masked="<?php echo htmlspecialchars($cpfMascarado); ?>"
                                >

                                    <?php
                                    echo htmlspecialchars($cpfMascarado);
                                    ?>

                                </span>


                                <?php if (!empty($cpf)): ?>

                                  

                                <?php endif; ?>


                            </span>

                        </span>


                        <!-- TIPO -->

                        <span>

                            <strong>Tipo:</strong>

                            <span class="tipo <?php echo $tipoClasse; ?>">

                                <?php echo $tipoTexto; ?>

                            </span>

                        </span>


                    </div>


                    <!-- BOTÕES -->

                    <div class="btn-group">


                        <?php if ($usuario['ATIVO'] == 1): ?>


          


                            <!-- ALTERAR TIPO -->

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="iduser"
                                    value="<?php echo $usuario['IDUSER']; ?>"
                                >


                                <?php if ($usuario['TIPOUSUA'] == 1): ?>


                                    <input
                                        type="hidden"
                                        name="novo_tipo"
                                        value="0"
                                    >


                                    <button
                                        type="submit"
                                        name="alterar_tipo"
                                        class="btn-tipo"
                                        onclick="return confirm('Deseja transformar este administrador em usuário padrão?')"
                                    >

                                        ↔ Tornar padrão

                                    </button>


                                <?php else: ?>


                                    <input
                                        type="hidden"
                                        name="novo_tipo"
                                        value="1"
                                    >


                                    <button
                                        type="submit"
                                        name="alterar_tipo"
                                        class="btn-tipo"
                                        onclick="return confirm('Deseja transformar este usuário em administrador?')"
                                    >

                                        ↔ Tornar ADM

                                    </button>


                                <?php endif; ?>


                            </form>


                            <!-- DESATIVAR -->

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="iduser"
                                    value="<?php echo $usuario['IDUSER']; ?>"
                                >


                                <button
                                    type="submit"
                                    name="desativar_usuario"
                                    class="btn-del"
                                    onclick="return confirm('Tem certeza que deseja desativar este usuário?')"
                                >

                                    🗑 Desativar

                                </button>

                            </form>


                        <?php else: ?>


                            <!-- REATIVAR -->

                            <form method="POST">

                                <input
                                    type="hidden"
                                    name="iduser"
                                    value="<?php echo $usuario['IDUSER']; ?>"
                                >


                                <button
                                    type="submit"
                                    name="reativar_usuario"
                                    class="btn-reativar"
                                    onclick="return confirm('Deseja reativar este usuário?')"
                                >

                                    ↻ Reativar

                                </button>

                            </form>


                        <?php endif; ?>


                    </div>


                </div>


            <?php endwhile; ?>


        <?php else: ?>


            <div style="text-align:center; padding:30px;">

                Nenhum usuário cadastrado no sistema.

            </div>


        <?php endif; ?>


    </div>


    <footer>

        Copyright © 2026 - 2MB |
        DRAH - Devolução e Reserva de Aparelhos de Hardware

    </footer>


</div>


<script>



// =====================================================
// PESQUISA
// =====================================================

function pesquisarUsuarios() {

    const pesquisa =
        document
        .getElementById("pesquisa")
        .value
        .toLowerCase();


    const usuarios =
        document.querySelectorAll(".user-card");


    usuarios.forEach(function(usuario) {


        const nome =
            usuario.getAttribute("data-nome");


        const email =
            usuario.getAttribute("data-email");


        const telefone =
            usuario.getAttribute("data-telefone");


        if (
            nome.includes(pesquisa) ||
            email.includes(pesquisa) ||
            telefone.includes(pesquisa)
        ) {

            usuario.style.display = "flex";

        } else {

            usuario.style.display = "none";

        }

    });

}

</script>


</body>

</html>

