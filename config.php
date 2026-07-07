<?php
define('BD_USER', 'root');
define('BD_PASS', '');
define('BD_NAME', 'drah');

$conexao = new mysqli(
    'localhost',
    BD_USER,
    BD_PASS,
    BD_NAME
);

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

mysqli_select_db($conexao, BD_NAME);
?>
