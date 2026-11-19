<?php
require_once __DIR__ . "/config.php";

$sql = "
SELECT
    U.NOME,
    U.CPF,
    U.EMAIL,
    U.TELEFONE
 
FROM USUARIO U

WHERE U.TIPOUSUA = 1
ORDER BY U.NOME
";

$resultado = $conexao->query($sql);
?>
