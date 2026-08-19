<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

include("config.php");

// Busca os dados do usuário logado
$id = $_SESSION['iduser'];
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
      padding-top: 100px;
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
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
      transition: background 0.2s;
    }

    .menu-superior a:hover,
    .menu-superior a.active {
      background: #c94415;
    }

    /* CARD CENTRAL DO PERFIL */
    .wrap {
      max-width: 920px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .card {
      background: #ffffff;
      border-radius: 16px;
      display: flex;
      box-shadow: 0 8px 24px rgba(0,0,0,0.15);
      overflow: hidden;
    }

    /* COLUNA DA ESQUERDA (AVATAR/INFOS) */
    .profile-box {
      width: 270px;
      background: #fff8f5;
      padding: 40px 20px;
      border-right: 1px solid #ffe0d1;
      display: flex;
      flex-direction: column;
      align-items: center;
      text-align: center;
    }

    .avatar-wrap {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      overflow: hidden;
      margin-bottom: 18px;
      border: 4px solid #ED5721;
    }

    .avatar {
      width: 100%;
      height: 100%;
      object-fit: cover;
    }

    .meta h3 {
      font-size: 18px;
      color: #222;
      margin-bottom: 6px;
    }

    .meta p {
      font-size: 13px;
      color: #666;
      word-break: break-all;
    }

    /* COLUNA DA DIREITA (FORMULÁRIO) */
    main {
      flex: 1;
      padding: 35px 40px;
    }

    .form-header h2 {
      font-size: 20px;
      color: #ED5721;
      margin-bottom: 20px;
      padding-bottom: 8px;
      border-bottom: 2px solid #ffb084;
    }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px;
    }

    label {
      display: block;
      font-size: 13px;
      font-weight: 600;
      margin: 12px 0 5px;
      color: #444;
    }

    input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
      transition: border-color 0.2s;
    }

    input:focus {
      border-color: #ED5721;
    }

    input[readonly] {
      background-color: #f5f5f5;
      color: #888;
      cursor: not-allowed;
    }

    /* MENSAGENS DE FEEDBACK */
    .msg-sucesso {
      background: #e6ffed;
      border: 1px solid #4caf50;
      color: #256029;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 16px;
      text-align: center;
    }

    .msg-erro {
      background: #ffe5e5;
      border: 1px solid #ff4d4d;
      color: #b30000;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 16px;
      text-align: center;
    }

    /* AÇÕES E BOTÕES */
    .actions {
      display: flex;
      gap: 12px;
      margin-top: 28px;
    }

    .btn {
      cursor: pointer;
      font-family: inherit;
    }

    .btn-save {
      background: #ED5721;
      color: white;
      border: none;
      padding: 11px 24px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
      transition: background 0.2s;
    }

    .btn-save:hover {
      background: #c94415;
    }

    .btn-cancel {
      background: #e0e0e0;
      color: #333;
      padding: 11px 24px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none !important;
      display: inline-block;
      transition: background 0.2s;
    }

    .btn-cancel:hover {
      background: #d0d0d0;
    }

    /* RODAPÉ */
    footer {
      font-size: 12px;
      color: #666;
      text-align: center;
      margin-top: 30px;
      margin-bottom: 20px;
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
