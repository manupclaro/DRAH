<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Cadastrar-se - ADM | DRAH</title>

  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background: #c1e0e0;
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
      box-shadow: 0 4px 10px #003F47;
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
      border: 2px solid #b7edea;
      border-radius: 8px;
      font-size: 16px;
      box-sizing: border-box;
    }

    input:focus {
      border-color: #00c2c7;
      outline: none;
    }

    button {
      width: 100%;
      padding: 14px;
      background: #00c2c7;
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 17px;
      font-weight: bold;
      cursor: pointer;
      margin-top: 6px;
    }

    button:hover {
        background: #006d77;
    }

    p {
      text-align: center;
    }
    a {
      text-decoration: none;
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
    .termos-box {
    width: 100%;
    max-width: 600px;
    margin: 20px auto;
}

.termos-box h3 {
    color: #006d77;
    margin-bottom: 10px;
}

.termos-texto {
    height: 180px;
    overflow-y: auto;
    border: 2px solid #006d77;
    border-radius: 10px;
    background: #fff;
    padding: 15px;
    text-align: justify;
    line-height: 1.5;
}

.aceite {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-top: 12px;
    color: #006d77;
    font-weight: bold;
    cursor: pointer;

    flex-wrap: wrap; /* permite quebrar linha só se precisar */
}
.aceite input {
    margin: 0;
    width: 16px;
    height: 16px;
    flex-shrink: 0;
}
  </style>
</head>

<body>
  <div class="container">
    <div class="logo">
      <img src="imagens/logo_ciano.png" alt="Devolução e Reserva de Aparelhos de Hardware"/>
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

        <label for="codigoadm">Código de Validação (Administrador)</label>
        <input type="text" id="codigoadm" name="codigoadm" required>

        <div class="termos-box">
    <h3>Termos de Uso e Responsabilidade</h3>

<div class="termos-texto">

  

        <p>
            Ao realizar o cadastro e utilizar o sistema de devolução e reserva de aparelhos de hardware,
            desenvolvido pela equipe 2MB, o usuário declara estar ciente e concorda com os seguintes termos:
        </p>

        <p><strong>Uso adequado dos componentes</strong></p>
        <p>
            Os materiais disponibilizados devem ser utilizados exclusivamente para fins educacionais,
            acadêmicos ou previamente declarados e autorizados pelo administrador responsável.
        </p>

        <p><strong>Responsabilidade pela conservação</strong></p>
        <p>
            O usuário se compromete a zelar pela integridade dos componentes retirados,
            mantendo-os em bom estado de uso durante todo o período em que estiver sob sua responsabilidade.
        </p>

        <p><strong>Prazo de devolução</strong></p>
        <p>
            Os itens reservados devem ser devolvidos dentro do prazo estabelecido no momento do pedido da reserva.
            O não cumprimento do prazo poderá resultar em penalidades definidas pela administração do sistema.
        </p>

        <p><strong>Perda, dano ou extravio</strong></p>
        <p>
            Em caso de dano, perda ou extravio de qualquer componente, o usuário deverá comunicar os responsáveis
            e poderá ser obrigado a reparar o dano, substituir o item, arcar com os custos correspondentes
            ou alguma outra penalidade estabelecida pelo administrador responsável.
        </p>

        <p><strong>Proibição de empréstimo a terceiros</strong></p>
        <p>
            Os componentes são de uso individual e intransferível. É proibido repassar, emprestar ou ceder os itens
            a terceiros sem autorização. Caso realize essa ação, fica sob responsabilidade do usuário que reservou
            os componentes arcar com os possíveis danos e prejuízos.
        </p>

        <p><strong>Penalidades</strong></p>
        <p>
            O descumprimento de qualquer um dos termos poderá resultar na suspensão ou bloqueio do acesso ao sistema,
            além de outras medidas administrativas cabíveis definidas pelo administrador responsável.
        </p>

        <p><strong>Aceite dos termos</strong></p>
        <p>
            Ao marcar a opção de aceite no momento do cadastro, o usuário confirma que leu,
            compreendeu e concorda integralmente com este Termo de Responsabilidade.
        </p>

    </div>

    <label class="aceite">
    <input type="checkbox" name="aceitou_termos" required>
    Li e concordo com os Termos de Responsabilidade
</label>
</div>

        <input type="hidden" name="tipo_cadastro" value="adm">
        <button type="submit" class="btn">Finalizar Cadastro</button>
    </form>
    <p>Já tem uma conta? <a href="login.php"><b>Faça o Login</b></a></p>
    <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
  </div>
</body>
</html>
