<?php
$conn = new mysqli("localhost", "root", "", "drah");

if ($conn->connect_error) {
    die("Erro: " . $conn->connect_error);
}

$nome = $_POST['nome'] ?? '';
$descricao = $_POST['descricao'] ?? '';
$quantidade = $_POST['quantidade'] ?? '';
$categoria = $_POST['categoria'] ?? '';

// imagem
$imagem = $_FILES['imagem']['name'] ?? '';

if (!is_dir("componentes")) {
    mkdir("componentes");
}

$caminho = "componentes/" . $imagem;

move_uploaded_file($_FILES['imagem']['tmp_name'], $caminho);

// validação simples
if ($nome && $descricao && $quantidade) {

   $sql = "INSERT INTO componente
(nome, descricao, quantidade, categoria, imagem)
VALUES 
('$nome', '$descricao', '$quantidade', '$categoria', '$imagem')";

    if ($conn->query($sql)) {
        echo "✅ Componente cadastrado com sucesso!";
         header("Location: componentesadm.php");
    exit();
    } else {
        echo "Erro no banco: " . $conn->error;
    }

} else {
    echo "⚠️ Preencha todos os campos!";
}

$conn->close();
?>