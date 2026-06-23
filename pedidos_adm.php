<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Meus Pedidos | DRAH</title>
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

    .wrap{
      min-height:100vh;
      display:flex;
      align-items:center;
      justify-content:center;
      padding:32px;
    }

    .card{
      width:100%;
      max-width:920px;
      background:var(--card);
      border-radius:16px;
      padding:28px;
      display:flex;
      flex-direction:column;
      gap:20px;
    }

    h1{margin:0;font-size:24px;text-align:center}

    .pedido{
      background:white;
      border: 1px solid #e5fffa;
      border-radius:14px;
      padding:20px;
    }

    /* header com badges agrupados à direita */
    .pedido-header{display:flex;align-items:center;margin-bottom:14px}
    .pedido-header strong{font-weight:700}

    /* badges separadas, alinhadas à direita e com divisória */
.badges {
  margin-left: auto;
  display: inline-flex;
  align-items: center;
  gap: 6px; /* distância entre etiquetas */
}

.badges span {
  padding: 8px 12px;
  font-size: 13px;
  font-weight: 600;
  color: white;
  border-radius: 10px;   /* cada etiqueta arredondada */
  position: relative;
}

/* divisória entre as labels */
.badges span:not(:last-child)::after {
  content: "";
  position: absolute;
  right: -4px;
  top: 50%;
  width: 2px;
  height: 18px;
  background: #999;
  border-radius: 2px;
}

    /* cores específicas */
    .status-dev{background: cornflowerblue}
    .status-aprov{background: mediumseagreen}
    .status-recus{background: tomato}
    .status-anda{background: rgb(255, 185, 99)}
    .status-ana{background: violet}
    .comprovante{background: #ED5721}

    .grid{display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-top:10px}
    label{display:block;font-size:13px;color:var(--muted);margin-bottom:4px}
    .info{font-size:15px;padding:10px;border-radius:10px;background:#fafafa;border:1px solid #eee}

    @media(max-width:700px){.grid{grid-template-columns:1fr}}
    .menu-superior a.active {
        background: white;
        color: #006d77;
    }

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
      <a href="perfil_adm.php">Perfil</a> 
      <a href="pedidos_adm.php" class="active">Meus Pedidos</a> 
      <a href="carrinho_adm.php">Carrinho</a> 
      <a href="logout.php">Logout</a>  
    </nav>
  </header>
  
  <div class="wrap">
    <section class="card">
      <h1>Meus Pedidos</h1>

      <div class="pedido">
        <div class="pedido-header">
          <strong>Pedido #1029</strong>

          <!-- container que segura as etiquetas, sem espaço entre elas -->
          <div class="badges" aria-hidden="true">
            <span class="status-aprov">Aprovado</span>
          </div>
        </div>

        <div class="grid">
          <div>
            <label>Componentes</label>
            <div class="info">Resistor 10kΩ, Arduino Uno, Fonte 12V</div>
          </div>
          <div>
            <label>Quantidade total</label>
            <div class="info">7 itens</div>
          </div>
          <div>
            <label>Data de retirada</label>
            <div class="info">14/12/2025</div>
          </div>
          <div>
            <label>Data de devolução</label>
            <div class="info">20/12/2025</div>
          </div>
          <div>
            <label>Estado do componente devolvido</label>
            <div class="info"></div>
          </div>
          <div>
            <label>Justificativa</label>
            <div class="info">Mostra de Ciências.</div>
          </div>
          <div>
            <label>Observações</label>
            <div class="info">Nenhuma observação registrada.</div>
          </div>
        </div>
      </div>

      <div class="pedido">
        <div class="pedido-header">
          <strong>Pedido #0666</strong>

          <!-- container que segura as etiquetas, sem espaço entre elas -->
          <div class="badges" aria-hidden="true">
            <span class="status-dev">Devolvido</span>
            <span class="comprovante">Comprovante</span>
          </div>
        </div>

        <div class="grid">
          <div>
            <label>Componentes</label>
            <div class="info">Resistor 10kΩ, Arduino Uno, Fonte 12V</div>
          </div>
          <div>
            <label>Quantidade total</label>
            <div class="info">7 itens</div>
          </div>
          <div>
            <label>Data de retirada</label>
            <div class="info">14/11/2025</div>
          </div>
          <div>
            <label>Data de devolução</label>
            <div class="info">21/11/2025</div>
          </div>
          <div>
            <label>Estado do componente devolvido</label>
            <div class="info">Perfeito estado</div>
          </div>
          <div>
            <label>Observações</label>
            <div class="info">Nenhuma observação registrada.</div>
          </div>
        </div>
      </div>

      <div class="pedido">
        <div class="pedido-header">
          <strong>Pedido #1000</strong>

          <!-- container que segura as etiquetas, sem espaço entre elas -->
          <div class="badges" aria-hidden="true">
            <span class="status-anda">Retirado</span>
          </div>
        </div>

        <div class="grid">
          <div>
            <label>Componentes</label>
            <div class="info">Cabo SATA</div>
          </div>
          <div>
            <label>Quantidade total</label>
            <div class="info">2 itens</div>
          </div>
          <div>
            <label>Data de retirada</label>
            <div class="info">10/11/2025</div>
          </div>
          <div>
            <label>Data de devolução</label>
            <div class="info">11/11/2025</div>
          </div>
          <div>
            <label>Estado do componente devolvido</label>
            <div class="info"> </div>
          </div>
          <div>
            <label>Justificativas</label>
            <div class="info">Mostra de Ciências.</div>
          </div>
          <div>
            <label>Observações</label>
            <div class="info">Nenhuma observação registrada.</div>
          </div>
        </div>
      </div>

      <div class="pedido">
        <div class="pedido-header">
          <strong>Pedido #0666</strong>

          <!-- container que segura as etiquetas, sem espaço entre elas -->
          <div class="badges" aria-hidden="true">
            <span class="status-ana">Em Análise</span>
          </div>
        </div>

        <div class="grid">
          <div>
            <label>Componentes</label>
            <div class="info">Resistor 10kΩ, Arduino Uno, Fonte 12V</div>
          </div>
          <div>
            <label>Quantidade total</label>
            <div class="info">7 itens</div>
          </div>
          <div>
            <label>Data de retirada</label>
            <div class="info">14/11/2025</div>
          </div>
          <div>
            <label>Data de devolução</label>
            <div class="info">21/11/2025</div>
          </div>
          <div>
            <label>Estado do componente devolvido</label>
            <div class="info"></div>
          </div>
          <div>
            <label>Observações</label>
            <div class="info">Nenhuma observação registrada.</div>
          </div>
        </div>
      </div>

      <div class="pedido">
        <div class="pedido-header">
          <strong>Pedido #0666</strong>

          <!-- container que segura as etiquetas, sem espaço entre elas -->
          <div class="badges" aria-hidden="true">
            <span class="status-recus">Recusado</span>
          </div>
        </div>

        <div class="grid">
          <div>
            <label>Componentes</label>
            <div class="info">Resistor 10kΩ, Arduino Uno, Fonte 12V</div>
          </div>
          <div>
            <label>Quantidade total</label>
            <div class="info">7 itens</div>
          </div>
          <div>
            <label>Data de retirada</label>
            <div class="info">14/11/2025</div>
          </div>
          <div>
            <label>Data de devolução</label>
            <div class="info">21/11/2025</div>
          </div>
          <div>
            <label>Estado do componente devolvido</label>
            <div class="info"></div>
          </div>
          <div>
            <label>Observações</label>
            <div class="info">Nenhuma observação registrada.</div>
          </div>
        </div>
      </div>
    </section>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</body>
</html>
