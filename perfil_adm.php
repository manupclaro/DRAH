<?php
session_start();

if (!isset($_SESSION['logado']) || $_SESSION['logado'] !== true) {
    header("Location: login.php");
    exit();
}

include("config.php");

// Oculta o CPF, mostrando só os últimos dígitos: ***.***.789-00
function mascararCpf($cpf) {
    $d = preg_replace('/\D/', '', (string)$cpf);
    if (strlen($d) === 11) {
      return substr($d, 0, 1) . '**.***.***-*' . substr($d, -1);
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

// Busca os dados do usuário logado
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

// Processar atualização
$sucesso = "";
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome     = trim($_POST['fullName'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $telefone = preg_replace('/\D/', '', $_POST['phone'] ?? ''); // só números
    $senha    = $_POST['password'] ?? '';

    if (empty($nome) || empty($email)) {
        $erro = "Nome e e-mail são obrigatórios.";
    } elseif (!preg_match('/^[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)*(iffar|iffarroupilha)\.edu\.br$/i',$email)) {
      $erro = "Use um e-mail institucional válido que termine com iffar.edu.br ou iffarroupilha.edu.br.";
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
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil | DRAH</title>
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

    /* CARD CENTRAL DO PERFIL */
    .wrap {
      max-width: 720px;
      margin: 0 auto;
      padding: 0 20px;
    }

    .card {
      background: white;
      border-radius: 16px;
      box-shadow: 0 8px 24px #00171A;
      overflow: hidden;
    }

    main {
      padding: 35px 40px;
    }

    .form-header h2 {
      font-size: 20px;
      color: #006d77;
      margin-bottom: 20px;
      padding-bottom: 8px;
      border-bottom: 2px solid #b7edea;
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
      color: #333;
    }

    input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid white;
      border-radius: 8px;
      font-size: 14px;
      outline: none;
    }

    input:focus {
      border-color: #006d77;
    }

    /* CPF não editável */
    input[readonly] {
      background-color: lightgray;
      color: #999;
      cursor: not-allowed;
    }

    input[readonly]:focus {
      border-color: #999;
    }

    /* MENSAGENS DE FEEDBACK */
    .msg-sucesso {
      background: honeydew;
      border: 1px solid green;
      color: green;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 16px;
      text-align: center;
    }

    .msg-erro {
      background: mistyrose;
      border: 1px solid red;
      color: red;
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
      background: #006d77;
      color: white;
      border: none;
      padding: 11px 24px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
    }

    .btn-save:hover {
      background: #00c2c7;
    }

    .btn-cancel {
      background: lightgray;
      color: #333;
      padding: 11px 24px;
      border-radius: 20px;
      font-weight: 600;
      font-size: 14px;
      text-decoration: none !important;
      display: inline-block;
    }

    .btn-cancel:hover {
      background: #999;
    }

    /* RODAPÉ */
    footer {
      font-size: 12px;
      color: #666;
      text-align: center;
      margin-top: 30px;
      margin-bottom: 20px;
    }

    @media (max-width: 600px) {
      .grid-2 { grid-template-columns: 1fr; gap: 0; }
      main { padding: 25px 20px; }
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
              <!-- CPF oculto (só últimos dígitos) e não editável -->
              <input id="cpf" type="text"
                value="<?= htmlspecialchars(mascararCpf($usuario['CPF'])) ?>" readonly>
            </div>
          </div>

          <div class="grid-2">
            <div>
              <label for="email">E-mail institucional</label>
              <input id="email" name="email" type="email"
                  value="<?= htmlspecialchars($usuario['EMAIL']) ?>"
                  pattern="[A-Za-z0-9._%+-]+@([A-Za-z0-9-]+\.)*(iffar\.edu\.br|iffarroupilha\.edu\.br)"
                  title="Use um e-mail institucional terminado em iffar.edu.br ou iffarroupilha.edu.br" required>
              <small id="emailAviso" style="color: red; display:none; font-size:13px;">
                  O e-mail deve terminar em iffar.edu.br ou iffarroupilha.edu.br
              </small>

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
    <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
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