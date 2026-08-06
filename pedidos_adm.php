<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verifica se há sessão ativa e se o usuário logado possui permissão de ADM
// (Ajuste o nome da chave de sessão conforme a sua tela de login do ADM)
if (!isset($_SESSION['id_user']) || empty($_SESSION['is_adm'])) {
    header("Location: logindrah.html");
    exit;
}

// Configuração de Conexão com o Banco de Dados
$host     = 'localhost';
$dbname   = 'DRAH';
$username = 'root';
$password = ''; // Sua senha do MySQL aqui

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta SQL para ADM: busca TODOS os pedidos do sistema e inclui o NOME do usuário solicitante
$sql = "SELECT 
            p.*,
            u.NOME AS NOME_USUARIO,
            GROUP_CONCAT(c.NOME SEPARATOR ', ') AS COMPONENTES_LISTA,
            SUM(pc.QUANTIDADE) AS TOTAL_ITENS
        FROM PEDIDO p
        INNER JOIN USUARIO u ON p.IDUSER = u.IDUSER
        LEFT JOIN PEDIDO_COMP pc ON p.IDPEDIDO = pc.IDPEDIDO
        LEFT JOIN COMPONENTE c ON pc.IDCOMP = c.IDCOMP
        GROUP BY p.IDPEDIDO
        ORDER BY p.DATA_PEDIDO DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$pedidos = $stmt->fetchAll();

// Dicionário de classes CSS de status
$status_classes = [
    'Aprovado'   => 'status-aprov',
    'Devolvido'  => 'status-dev',
    'Retirado'   => 'status-anda',
    'Em Análise' => 'status-ana',
    'Recusado'   => 'status-recus'
];

// Função auxiliar para formatar datas no padrão DD/MM/AAAA
function formatarDataBR($data) {
    if (empty($data) || $data === '0000-00-00 00:00:00' || $data === '0000-00-00') {
        return '—';
    }
    return date('d/m/Y', strtotime($data));
}
?>
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

    .wrap {
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 32px;
    }

    .card {
      width: 100%;
      max-width: 920px;
      background: #ffffff;
      border-radius: 16px;
      padding: 28px;
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    h1 { margin: 0; font-size: 24px; text-align: center; }

    .pedido {
      background: white;
      border: 1px solid #e5fffa;
      border-radius: 14px;
      padding: 20px;
    }

    /* header com badges agrupados à direita */
    .pedido-header { display: flex; align-items: center; margin-bottom: 14px; }
    .pedido-header strong { font-weight: 700; }

    /* badges separadas, alinhadas à direita e com divisória */
    .badges {
      margin-left: auto;
      display: inline-flex;
      align-items: center;
      gap: 6px;
    }

    .badges span {
      padding: 8px 12px;
      font-size: 13px;
      font-weight: 600;
      color: white;
      border-radius: 10px;
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
      transform: translateY(-50%);
    }

    /* cores específicas */
    .status-dev   { background: cornflowerblue; }
    .status-aprov { background: mediumseagreen; }
    .status-recus { background: tomato; }
    .status-anda  { background: rgb(255, 185, 99); }
    .status-ana   { background: violet; }
    .comprovante  { background: #ED5721; }

    .grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 10px; }
    label { display: block; font-size: 13px; color: #6b6b6b; margin-bottom: 4px; }
    .info { font-size: 15px; padding: 10px; border-radius: 10px; background: #fafafa; border: 1px solid #eee; min-height: 40px; }

    @media (max-width: 700px) { .grid { grid-template-columns: 1fr; } }

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
  
  <!-- FRONT-END-->
  <div class="wrap">
    <section class="card">
      <h1>Meus Pedidos - Administração</h1>

      <?php if (empty($pedidos)): ?>
          <p style="text-align:center; color: #666; padding: 30px;">
            Nenhum pedido foi registrado no sistema até o momento.
          </p>
      <?php else: ?>
          <?php foreach ($pedidos as $pedido): 
              $classe_status = $status_classes[$pedido['STATUSPEDIDO']] ?? 'status-ana';
          ?>
              <div class="pedido">
                <div class="pedido-header">
                  <strong>Pedido #<?= sprintf('%04d', $pedido['IDPEDIDO']) ?> &mdash; <?= htmlspecialchars($pedido['NOME_USUARIO'] ?? 'Usuário ID: ' . $pedido['IDUSER']) ?></strong>

                  <div class="badges" aria-hidden="true">
                    <span class="<?= $classe_status ?>"><?= htmlspecialchars($pedido['STATUSPEDIDO']) ?></span>
                    
                    <?php if ($pedido['STATUSPEDIDO'] === 'Devolvido'): ?>
                        <span class="comprovante">Comprovante</span>
                    <?php endif; ?>
                  </div>
                </div>

                <div class="grid">
                  <div>
                    <label>Componentes</label>
                    <div class="info"><?= htmlspecialchars($pedido['COMPONENTES_LISTA'] ?? 'Nenhum componente') ?></div>
                  </div>
                  <div>
                    <label>Quantidade total</label>
                    <div class="info"><?= (int)$pedido['TOTAL_ITENS'] ?> <?= (int)$pedido['TOTAL_ITENS'] === 1 ? 'item' : 'itens' ?></div>
                  </div>
                  <div>
                    <label>Data de retirada</label>
                    <div class="info"><?= formatarDataBR($pedido['DATA_RETIRADA'] ?? null) ?></div>
                  </div>
                  <div>
                    <label>Data de devolução</label>
                    <div class="info">
                      <?php 
                        if (!empty($pedido['DATA_DEVOLUCAO']) && $pedido['DATA_DEVOLUCAO'] !== '0000-00-00') {
                            echo formatarDataBR($pedido['DATA_DEVOLUCAO']) . " (Real)";
                        } else {
                            echo formatarDataBR($pedido['DATA_PREVIADEV'] ?? null) . " (Prevista)";
                        }
                      ?>
                    </div>
                  </div>
                  <div>
                    <label>Estado do componente devolvido</label>
                    <div class="info"><?= htmlspecialchars($pedido['ESTADO_COMPONENTE'] ?? $pedido['ESTADO_DEV'] ?? '—') ?></div>
                  </div>
                  <div>
                    <label>Justificativa</label>
                    <div class="info"><?= htmlspecialchars($pedido['JUSTIFICATIVA'] ?? '—') ?></div>
                  </div>
                  <div>
                    <label>Observações</label>
                    <div class="info"><?= htmlspecialchars($pedido['OBSERVACOES'] ?? 'Nenhuma observação registrada.') ?></div>
                  </div>
                </div>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>

    </section>
  </div>
  <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
</body>
</html>
