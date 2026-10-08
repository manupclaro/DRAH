<?php
define('BD_HOST', 'mysql-drah.alwaysdata.net');
define('BD_USER', 'drah');
define('BD_PASS', 'Suportedrah');
define('BD_NAME', 'drah_drah');


mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conexao = new mysqli(BD_HOST, BD_USER, BD_PASS, BD_NAME);
    $conexao->set_charset('utf8mb4');
} catch (mysqli_sql_exception $e) {
    
    error_log('Erro de conexão com o banco: ' . $e->getMessage());

  
    http_response_code(500);
    die('Não foi possível conectar ao banco de dados.');
}
