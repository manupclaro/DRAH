<?php
session_start();

// Se já estiver logado, redireciona para a página principal
if (isset($_SESSION['logado']) && $_SESSION['logado'] === true) {
    header("Location: index_padrao.php");
    exit();
}

// ─── Configuração do banco ───────────────────────────────────────────────────
$servername = "localhost";
$username   = "root";
$password   = "";
$dbname     = "DRAH";

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Erro na conexão: " . $conn->connect_error);
}

$erro = "";

// ─── PROCESSAR LOGIN (só quando vier POST) ───────────────────────────────────
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $cpf   = trim($_POST["usuario"] ?? ""); // o campo HTML chama "usuario" mas guarda CPF
    $senha = $_POST["senha"] ?? "";

    if ($cpf === "" || $senha === "") {
        $erro = "Preencha o CPF e a senha.";
    } else {
        // Busca o usuário pelo CPF na tabela USUARIO
        $query = "SELECT IDUSER, NOME, SENHA, TIPOUSUA FROM USUARIO WHERE CPF = ? LIMIT 1";
        $stmt  = $conn->prepare($query);
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $dados = $result->fetch_assoc();

            // Verificar senha com hash bcrypt (password_hash / password_verify)
            if (password_verify($senha, $dados["SENHA"])) {

                // ── Sessões ──────────────────────────────────────────────────
                $_SESSION["logado"]      = true;
                $_SESSION["usuario_id"]  = $dados["IDUSER"];
                $_SESSION["usuario"]     = $dados["NOME"];
                $_SESSION["tipo"]        = $dados["TIPOUSUA"]; // útil para controle de acesso

                // Redireciona admin (TIPOUSUA = 1) ou usuário comum separadamente, se quiser:
                // if ($dados["TIPOUSUA"] == 1) { header("Location: admin.php"); exit(); }

                header("Location: index_padrao.php"); // página central DRAH
                exit();

            } else {
                $erro = "Senha incorreta.";
            }
        } else {
            $erro = "CPF não encontrado.";
        }

        $stmt->close();
    }

    // Se houver erro, volta para o HTML com a mensagem na URL
    if (!empty($erro)) {
        header("Location: LoginDRAH.php?erro=" . urlencode($erro));
        exit();
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Login – DRAH</title>

  <style>
    /* ── Reset & base ──────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

    body {
      font-family: Arial, sans-serif;
      background-color: #fff2e5;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    /* ── Card ──────────────────────────────────────────────────── */
    .container {
      width: 90%;
      max-width: 420px;
      background: #fff;
      padding: 32px 28px;
      border-radius: 14px;
      box-shadow: 0 4px 16px rgba(117, 31, 0, .25);
    }

    /* ── Logo ──────────────────────────────────────────────────── */
    .logo {
      display: flex;
      justify-content: center;
      margin-bottom: 24px;
    }
    .logo img {
      height: 85px;
      max-width: 100%;
      object-fit: contain;
    }

    /* ── Mensagem de erro ──────────────────────────────────────── */
    .erro {
      background: #ffe5e5;
      border: 1px solid #ff4d4d;
      color: #b30000;
      border-radius: 8px;
      padding: 10px 14px;
      font-size: 14px;
      margin-bottom: 14px;
      text-align: center;
    }

    /* ── Inputs ────────────────────────────────────────────────── */
    input {
      display: block;
      width: 100%;
      padding: 14px;
      margin: 10px 0;
      border: 2px solid #ffb084;
      border-radius: 8px;
      font-size: 16px;
      background: #fff2e5;
      color: #333;
      transition: border-color .2s;
    }
    input:focus {
      border-color: #ff7f50;
      outline: none;
      background: #fff;
    }

    /* ── Botão ─────────────────────────────────────────────────── */
    button {
      display: block;
      width: 100%;
      padding: 14px;
      margin-top: 10px;
      background: #ff7f50;
      border: none;
      border-radius: 8px;
      font-size: 17px;
      font-weight: bold;
      color: #fff;
      cursor: pointer;
      transition: background .25s;
    }
    button:hover { background: #ed5721; }

    /* ── Link criar conta ──────────────────────────────────────── */
    .criarConta {
      text-align: center;
      margin-top: 20px;
      font-size: 15px;
    }
    .criarConta a {
      color: #ff7f50;
      font-weight: bold;
      text-decoration: none;
      transition: color .2s;
    }
    .criarConta a:hover { color: #ed5721; }
  </style> 
</head>

<body>
  <div class="container">

    <!-- Logo -->
    <div class="logo">
      <img src="imagens/logo_laranja.png" alt="Logo DRAH" />
    </div>

    <!-- Mensagem de erro vinda da URL (?erro=...) -->
    <?php if (!empty($_GET['erro'])): ?>
      <div class="erro">⚠️ <?= htmlspecialchars($_GET['erro']) ?></div>
    <?php endif; ?>

    <!-- Formulário de login -->
    <form method="POST" action="LoginDRAH.php">
      <input
        type="text"
        name="usuario"
        placeholder="Digite seu CPF"
        value="<?= htmlspecialchars($_POST['usuario'] ?? '') ?>"
        required
      />
      <input
        type="password"
        name="senha"
        placeholder="Digite sua senha"
        required
      />
      <button type="submit">Entrar</button>
    </form>

    <!-- Link para cadastro -->
    <div class="criarConta">
      <a href="cadastro_tipo.html">Criar conta</a>
    </div>

  </div>
</body>
</html>