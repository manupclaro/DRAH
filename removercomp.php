<?php
include("config.php");

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    $sql = "DELETE FROM COMPONENTE WHERE IDCOMP = $id";

    if ($conexao->query($sql)) {
        header("Location: painel_componentes.php?msg=removido");
        exit();
    } else {
        echo "Erro ao remover: " . $conexao->error;
    }

} else {
    echo "ID inválido.";
}
?>
