<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar-se | DRAH</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #fff2e5;
      min-height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }

    .container {
      width: 90%;
      max-width: 420px;
      background: white;
      padding: 28px;
      border-radius: 14px;
      box-shadow: 0 4px 10px #751F00;
    }

    .logo {
      text-align: center;
      margin-bottom: 18px;
    }

    .logo img {
      height: 85px;
      max-width: 100%;
      object-fit: contain;
    }

    input {
      width: 100%;
      padding: 14px;
      margin: 8px 0;
      border: 2px solid #ffb084;
      border-radius: 8px;
      font-size: 16px;
      box-sizing: border-box;
    }

    input:focus {
      border-color: #ff7f50;
      outline: none;
    }

    button {
      width: 100%;
      padding: 14px;
      background: #ff7f50;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 17px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 6px;
    }

    button:hover {
      background: #ED5721;
    }

    p {
      text-align: center;
    }
    a {
      text-decoration: none;
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="logo">
      <img src="imagens/logo_laranja.png" alt="Devolução e Reserva de Aparelhos de Hardware"/>
    </div>

    <form action="criar_usuario.php" method="post">
        <label for="nome">Nome completo</label>
        <input type="text" id="nome" name="nome" required>

        <label for="cpf">CPF</label>
        <input type="text" id="cpf" name="cpf" placeholder="000.000.000-00" pattern="\d{3}\.\d{3}\.\d{3}-\d{2}" required>

        <label for="email">E-mail</label>
        <input type="email" id="email" name="email" placeholder="xxxx@xxx.xxx" required>

        <label for="telefone">Telefone</label>
        <input type="tel" id="telefone" name="telefone" placeholder="55987654321" pattern="\d{10,11}" required>

        <label for="senha">Senha</label>
        <input type="password" id="senha" name="senha" required>

        <button type="submit" class="btn">Finalizar Cadastro</button>
    </form>
    <p>Já tem uma conta? <a href="login.php"><b>Faça o Login</b></a></p>
  </div>
</body>
</html>