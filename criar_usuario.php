<?php
include "config.php";

$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$codigoadm = $_POST['codigoadm'];

$tipo = 0; // usuário padrão
$erro = "";

if (empty($nome) || empty($email) || empty($telefone) || empty($cpf) || empty($senha)) {
    die("Preencha todos os campos!");
}

if ($_POST['tipo_cadastro'] === "adm") {
    // validação código admin (ANTES de inserir)
    if (!empty($codigoadm)) {

        $stmt = $conexao->prepare("SELECT * FROM CODIGO_ADM WHERE CODIGO = ? AND USADO = 0");
        $stmt->bind_param("s", $codigoadm);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $tipo = 1; // vira admin

            // marcar código como usado
            $stmtUpdate = $conexao->prepare("UPDATE CODIGO_ADM SET USADO = 1 WHERE CODIGO = ?");
            $stmtUpdate->bind_param("s", $codigoadm);
            $stmtUpdate->execute();
        } else {
            $erro = "Código de administrador inválido ou já utilizado!";
            if (!empty($erro)) {
                include "cadastro_adm.php";
                exit;
            }
        }
    }
}

$senha_encriptada = password_hash($senha, PASSWORD_DEFAULT);

$stmt = $conexao->prepare("INSERT INTO usuario (nome, email, telefone, cpf, senha, tipousua) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssi", $nome, $email, $telefone, $cpf, $senha_encriptada, $tipo);

if ($stmt->execute()) {
    header("Location: login.php");
    exit;
} else {

    switch ($conexao->errno) {
        case 1062:
            $erro = "CPF ou e-mail já cadastrado!";
            break;
        default:
            $erro = "Erro inesperado no cadastro!";
    }

    if ($_POST['tipo_cadastro'] === "adm"){
        include "cadastro_adm.php";
    }else{
        include "cadastro.php";
    }
    exit;
}
?>
