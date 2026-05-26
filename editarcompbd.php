<?php
include("config.php");

$id = $_POST['id'];
$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$quantidade = $_POST['quantidade'];
$categoria = $_POST['categoria'];

// Upload de imagem (se tiver)
$imagemNome = "";

if(isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0){
    $pasta = "componentes/";
    $imagemNome = time() . "_" . $_FILES['imagem']['name'];
    move_uploaded_file($_FILES['imagem']['tmp_name'], $pasta . $imagemNome);

    $sql = "UPDATE COMPONENTE SET 
        NOME='$nome',
        DESCRICAO='$descricao',
        QUANTIDADE=$quantidade,
        CATEGORIA='$categoria',
        IMAGEM='$imagemNome'
        WHERE IDCOMP=$id";
} else {
    // sem imagem nova
    $sql = "UPDATE COMPONENTE SET 
        NOME='$nome',
        DESCRICAO='$descricao',
        QUANTIDADE=$quantidade,
        CATEGORIA='$categoria'
        WHERE IDCOMP=$id";
}

if ($conexao->query($sql)) {
    header("Location: componentesadm.php");
    exit();
} else {
    echo "Erro: " . $conn->error;
}
