<?php
include "config.php";

$codigo = bin2hex(random_bytes(4)); 

$stmt = $conexao->prepare("INSERT INTO CODIGO_ADM (CODIGO) VALUES (?)");
$stmt->bind_param("s", $codigo);
$stmt->execute();

/*mail($email, "Seu código de admin", "Código: $codigo"); PRECISA ADD EXTENSAO*/
echo $codigo;
?>
