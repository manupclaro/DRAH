<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

include("config.php");

// Busca os dados do usuário logado
$id = $_SESSION['usuario_id'];
$sql = "SELECT NOME, CPF, EMAIL, TELEFONE FROM USUARIO WHERE IDUSER = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

// Processar atualização
$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome     = trim($_POST['fullName'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['phone'] ?? '');
    $senha    = $_POST['password'] ?? '';

    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios.";
    } else {
        if (!empty($senha)) {
            $hash = password_hash($senha, PASSWORD_BCRYPT);
            $upd = "UPDATE USUARIO SET NOME=?, EMAIL=?, TELEFONE=?, SENHA=? WHERE IDUSER=?";
            $stmt = $conexao->prepare($upd);
            $stmt->bind_param("ssssi", $nome, $email, $telefone, $hash, $id);
        } else {
            $upd = "UPDATE USUARIO SET NOME=?, EMAIL=?, TELEFONE=? WHERE IDUSER=?";
            $stmt = $conexao->prepare($upd);
            $stmt->bind_param("sssi", $nome, $email, $telefone, $id);
        }

        if ($stmt->execute()) {
            $_SESSION['usuario'] = $nome;
            $sucesso = "Perfil atualizado com sucesso!";
            // Recarrega os dados atualizados
            $usuario['NOME']     = $nome;
            $usuario['EMAIL']    = $email;
            $usuario['TELEFONE'] = $telefone;
        } else {
            $erro = "Erro ao atualizar perfil.";
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Perfil — DRAH</title>
  <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #ffb084;
        color: #333;
        min-height: 100vh;
        padding-top: 80px;
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
        background: #ED5721;
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
        background: #ff7f50;
        color: white;
        border: none;
        padding: 10px 22px;
        border-radius: 20px;
        font-weight: 600;
        text-decoration: none !important;
    }

    .menu-superior a:hover {
        background: #ED5721;
    }
    /* Adicione apenas isso para as mensagens: */
    .msg-sucesso {
      background: #e6ffed;
      border: 1px solid #4caf50;
      color: #256029;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 14px;
      text-align: center;
    }
    .msg-erro {
      background: #ffe5e5;
      border: 1px solid #ff4d4d;
      color: #b30000;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 14px;
      text-align: center;
    }
    /* Habilitar cursor nos botões */
    .btn { cursor: pointer; }

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
    <a href="index_padrao.php" class="back-arrow" title="Voltar"></a>
    <div class="logo">
      <a href="index_padrao.php">
        <img src="imagens/logo_branco.png" alt="Logo DRAH">
      </a>
    </div>
    <nav class="menu-superior">
      <a href="perfil.php" class="active">Perfil</a>
      <a href="pedidos.php">Meus Pedidos</a>
      <a href="carrinho.php">Carrinho</a>
      <a href="logout.php">Sair</a>
    </nav>
  </header>

  <div class="wrap">
    <section class="card">

      <!-- Coluna esquerda -->
      <aside class="profile-box">
        <div class="avatar-wrap">
          <img class="avatar" src="imagens/fotoperfil.jpg" alt="Foto de perfil">
        </div>
        <div class="meta">
          <h3><?= htmlspecialchars($usuario['NOME']) ?></h3>
          <p><?= htmlspecialchars($usuario['EMAIL']) ?></p>
        </div>
      </aside>

      <!-- Coluna direita -->
      <main>
        <form method="POST" action="perfil.php">

          <div class="form-header">
            <h2>Informações do perfil</h2>
          </div>

          <?php if (!empty($sucesso)): ?>
            <div class="msg-sucesso">✅ <?= htmlspecialchars($sucesso) ?></div>
          <?php endif; ?>

          <?php if (!empty($erro)): ?>
            <div class="msg-erro">⚠️ <?= htmlspecialchars($erro) ?></div>
          <?php endif; ?>

          <div class="grid-2">
            <div>
              <label for="fullName">Nome completo</label>
              <input id="fullName" name="fullName" type="text"
                value="<?= htmlspecialchars($usuario['NOME']) ?>" required>
            </div>
            <div>
              <label for="cpf">CPF</label>
              <!-- CPF não editável -->
              <input id="cpf" type="text"
                value="<?= htmlspecialchars($usuario['CPF']) ?>" readonly>
            </div>
          </div>

          <div class="grid-2">
            <div>
              <label for="email">Email</label>
              <input id="email" name="email" type="email"
                value="<?= htmlspecialchars($usuario['EMAIL']) ?>" required>
            </div>
            <div>
              <label for="phone">Telefone</label>
              <input id="phone" name="phone" type="tel"
                value="<?= htmlspecialchars($usuario['TELEFONE']) ?>">
            </div>
          </div>

          <div>
            <label for="password">Nova senha (deixe em branco para não alterar)</label>
            <input id="password" name="password" type="password" placeholder="••••••••">
          </div>

          <div class="actions">
            <button class="btn btn-save" type="submit">Salvar alterações</button>
            <a href="index_padrao.php" class="btn btn-cancel">Cancelar</a>
          </div>

        </form>
      </main>

    </section>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
  </div>
</body>
</html>
