<?php
define('BD_USER', 'root');
define('BD_PASS', '');
define('BD_NAME', 'drah');

$conexao = mysqli_connect('localhost', BD_USER, BD_PASS);

if (!$conexao) {
    die("Erro na conexão: " . mysqli_connect_error());
}

mysqli_select_db($conexao, BD_NAME);
?>