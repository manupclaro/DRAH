<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SESSION['tipo'] != 1) {
    header("Location: index_padrao.php");
    exit();
}

include("config.php");

// Oculta o CPF, mostrando só os últimos dígitos: ***.***.789-00
function mascararCpf($cpf) {
    $d = preg_replace('/\D/', '', (string)$cpf);
    if (strlen($d) === 11) {
        return '***.***.' . substr($d, 6, 3) . '-' . substr($d, 9, 2);
    }
    return '***.***.***-**';
}

// Formata telefone: (00) 00000-0000 ou (00) 0000-0000
function formatarTelefone($tel) {
    $d = preg_replace('/\D/', '', (string)$tel);
    if (strlen($d) === 11) {
        return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 5) . '-' . substr($d, 7, 4);
    }
    if (strlen($d) === 10) {
        return '(' . substr($d, 0, 2) . ') ' . substr($d, 2, 4) . '-' . substr($d, 6, 4);
    }
    return (string)$tel;
}

$id = $_SESSION['usuario_id'] ?? $_SESSION['iduser'] ?? null;

if ($id === null) {
    header("Location: login.php");
    exit();
}

$sql = "SELECT NOME, CPF, EMAIL, TELEFONE FROM USUARIO WHERE IDUSER = ?";
$stmt = $conexao->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$usuario = $result->fetch_assoc();
$stmt->close();

if (!$usuario) {
    header("Location: login.php");
    exit();
}

$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome     = trim($_POST['fullName'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['phone'] ?? ''); // só números
    $senha    = $_POST['password'] ?? '';

    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios.";
    } elseif ($telefone !== '' && !in_array(strlen($telefone), [10, 11])) {
        $erro = "Telefone inválido. Use o formato (00) 00000-0000.";
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
  <title>Perfil ADM — DRAH</title>
  <style>
    :root {
      --orange-700: #006d77;
      --orange-500: #19a0aa;
      --orange-200: #6bd5ff;
      --card: #ffffff;
      --muted: #6b6b6b;
    }

    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #c1e0e0;
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

    .wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px;
    }

    .card {
      width: 100%;
      max-width: 720px;
      background: var(--card);
      border-radius: 16px;
      padding: 28px;
    }

    .profile-box {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 18px;
    }

    .avatar-wrap {
      position: relative;
      width: 190px;
      height: 190px;
    }

    .avatar {
      width: 100%;
      height: 100%;
      border-radius: 50%;
      object-fit: cover;
      border: 6px solid var(--orange-200);
      background: linear-gradient(135deg, var(--orange-200), #fff);
    }

    .meta { margin-top: 14px; text-align: center; }
    .meta h3 { margin: 6px 0 2px; font-size: 20px; }
    .meta p { margin: 0; color: var(--muted); font-size: 14px; }

    form { display: flex; flex-direction: column; gap: 12px; }

    .form-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .form-header h2 { margin: 0; font-size: 18px; }

    .grid-2 {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 12px;
    }

    label {
      display: block;
      font-size: 13px;
      color: var(--muted);
      margin-bottom: 6px;
    }

    input {
      width: 100%;
      padding: 12px 14px;
      border-radius: 10px;
      border: 1px solid #eee;
      background: #fafafa;
      font-size: 15px;
    }

    input[readonly] { background: #f5f5f5; color: #888; cursor: not-allowed; }

    .actions { display: flex; gap: 12px; margin-top: 8px; }

    .btn {
      padding: 10px 14px;
      border-radius: 10px;
      border: 0;
      cursor: pointer;
      font-weight: 600;
    }

    .btn-save { background: var(--orange-500); color: white; }
    .btn-cancel {
      background: transparent;
      border: 1px solid #eee;
      text-decoration: none;
      color: #333;
      display: inline-flex;
      align-items: center;
    }

    .msg-sucesso {
      background: #e6ffed;
      border: 1px solid #4caf50;
      color: #256029;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      text-align: center;
    }

    .msg-erro {
      background: #ffe5e5;
      border: 1px solid #ff4d4d;
      color: #b30000;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      text-align: center;
    }

    @media (max-width: 800px) {
      .card { max-width: 520px; }
      .grid-2 { grid-template-columns: 1fr; }
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
            <a href="perfil_adm.php" class="active">Perfil</a>
            <a href="pedidos_adm.php">Meus Pedidos</a>
            <a href="carrinho_adm.php">Carrinho</a>
            <a href="paineladm.html">Painel ADM</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>

  <div class="wrap">
    <section class="card">

      <main>
        <form method="POST" action="perfil_adm.php">

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
              <input id="cpf" type="text"
                value="<?= htmlspecialchars(mascararCpf($usuario['CPF'])) ?>" readonly>
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
                placeholder="(00) 00000-0000" maxlength="15"
                value="<?= htmlspecialchars(formatarTelefone($usuario['TELEFONE'])) ?>">
            </div>
          </div>

          <div>
            <label for="password">Nova senha (deixe em branco para não alterar)</label>
            <input id="password" name="password" type="password" placeholder="••••••••">
          </div>

          <div class="actions">
            <button class="btn btn-save" type="submit">Salvar alterações</button>
            <a href="index_adm.php" class="btn btn-cancel">Cancelar</a>
          </div>

        </form>
      </main>

    </section>
  </div>

  <script>
    // Máscara de telefone: (00) 00000-0000 / (00) 0000-0000
    const phone = document.getElementById('phone');

    function mascaraTelefone(valor) {
      const d = valor.replace(/\D/g, '').slice(0, 11);
      if (d.length > 10) return d.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, '($1) $2-$3');
      if (d.length > 6)  return d.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      if (d.length > 2)  return d.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      if (d.length > 0)  return '(' + d;
      return '';
    }

    phone.addEventListener('input', () => {
      phone.value = mascaraTelefone(phone.value);
    });
  </script>

</body>
</html>