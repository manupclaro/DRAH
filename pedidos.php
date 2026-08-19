<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Suporte para diferentes nomes de variáveis de sessão
$id_usuario_logado = $_SESSION['id_user'] ?? $_SESSION['iduser'] ?? $_SESSION['usuario_id'] ?? null;

// Verifica se o usuário está logado
if (!$id_usuario_logado) {
    header("Location: login.php");
    exit();
}

// Configuração de Conexão com o Banco de Dados
$host     = 'localhost';
$dbname   = 'DRAH';
$username = 'root';
$password = ''; // Coloque sua senha do MySQL se houver

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao conectar ao banco de dados: " . $e->getMessage());
}

// Consulta SQL que busca os pedidos do usuário e lista os componentes de cada um
$sql = "SELECT 
            p.*,
            GROUP_CONCAT(c.NOME SEPARATOR ', ') AS COMPONENTES_LISTA,
            SUM(pc.QUANTIDADE) AS TOTAL_ITENS
        FROM PEDIDO p
        LEFT JOIN PEDIDO_COMP pc ON p.IDPEDIDO = pc.IDPEDIDO
        LEFT JOIN COMPONENTE c ON pc.IDCOMP = c.IDCOMP
        WHERE p.IDUSER = :id_user
        GROUP BY p.IDPEDIDO
        ORDER BY p.DATA_PEDIDO DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute(['id_user' => $id_usuario_logado]);
$pedidos = $stmt->fetchAll();

// Dicionário de cores para as etiquetas de status
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

    .menu-superior a:hover {
        background: #c94415;
    }

    .menu-superior a.active {
        background: white;
        color: #ED5721;
    }

    .wrap {
        max-width: 920px;
        margin: 0 auto 40px auto;
        padding: 0 20px;
    }

    .card {
        width: 100%;
        background: #ffffff;
        border-radius: 16px;
        padding: 28px;
        display: flex;
        flex-direction: column;
        gap: 20px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.15);
    }

    h1 { 
        margin: 0; 
        font-size: 24px; 
        text-align: center;
        color: #ED5721;
        padding-bottom: 10px;
        border-bottom: 2px solid #ffb084;
    }

    .pedido {
        background: white;
        border: 1px solid #ffd8c4;
        border-radius: 14px;
        padding: 20px;
    }

    .pedido-header { 
        display: flex; 
        align-items: center; 
        margin-bottom: 14px; 
    }
    
    .pedido-header strong { 
        font-weight: 700; 
        font-size: 16px;
    }

    .badges {
        margin-left: auto;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .badges span {
        padding: 6px 12px;
        font-size: 13px;
        font-weight: 600;
        color: white;
        border-radius: 10px;
    }

    /* Cores de status */
    .status-dev   { background: #4a90e2; }
    .status-aprov { background: #2ecc71; }
    .status-recus { background: #e74c3c; }
    .status-anda  { background: #f39c12; }
    .status-ana   { background: #9b59b6; }
    .comprovante  { background: #ED5721; }

    .grid { 
        display: grid; 
        grid-template-columns: 1fr 1fr; 
        gap: 14px; 
        margin-top: 10px; 
    }
    
    label { 
        display: block; 
        font-size: 13px; 
        color: #6b6b6b; 
        margin-bottom: 4px; 
        font-weight: 600;
    }
    
    .info { 
        font-size: 14px; 
        padding: 10px; 
        border-radius: 8px; 
        background: #fafafa; 
        border: 1px solid #eee; 
        min-height: 40px; 
    }

    @media (max-width: 700px) { 
        .grid { grid-template-columns: 1fr; } 
    }

    footer {
        font-size: 12px;
        color: #333;
        text-align: center;
        margin-top: 30px;
        margin-bottom: 20px;
    }
  </style>
</head>
<body>

  <!-- HEADER -->
  <header>
    <div class="logo">
      <a href="index_padrao.php"><img src="imagens/logo_branco.png" alt="Devolução e Reserva de Aparelhos de Hardware"></a>
    </div>
    <nav class="menu-superior">
      <a href="index_padrao.php">Início</a>
      <a href="perfil.php">Perfil</a> 
      <a href="pedidos.php" class="active">Meus Pedidos</a> 
      <a href="carrinho.php">Carrinho</a> 
      <a href="logout.php">Sair</a>  
    </nav>
  </header>

  <!-- Conteúdo Principal -->
  <div class="wrap">
    <section class="card">
      <h1>Meus Pedidos</h1>

      <?php if (empty($pedidos)): ?>
          <p style="text-align:center; color: #666; padding: 40px 0;">
            Você ainda não realizou nenhum pedido ou reserva.
          </p>
      <?php else: ?>
          <?php foreach ($pedidos as $pedido): 
              $classe_status = $status_classes[$pedido['STATUSPEDIDO']] ?? 'status-ana';
          ?>
              <div class="pedido">
                <div class="pedido-header">
                  <strong>Pedido #<?= sprintf('%04d', $pedido['IDPEDIDO']) ?></strong>

                  <div class="badges">
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
                  <div style="grid-column: span 2;">
                    <label>Observações</label>
                    <div class="info"><?= htmlspecialchars($pedido['OBSERVACOES'] ?? 'Nenhuma observação registrada.') ?></div>
                  </div>
                </div>
              </div>
          <?php endforeach; ?>
      <?php endif; ?>

    </section>

    <footer>Copyright © 2026 - 2MB | DRAH - Devolução e Reserva de Aparelhos de Hardware</footer>
  </div>

</body>
</html>
