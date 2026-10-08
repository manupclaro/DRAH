<?php
define('BD_USER', 'drah');
define('BD_PASS', 'Suportedrah');
define('BD_NAME', 'drah');

$conexao = new mysqli(
    'mysql-drah.alwaysdata.net',
    BD_USER,
    BD_PASS,
    BD_NAME
);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

mysqli_select_db($conexao, BD_NAME);
?>
