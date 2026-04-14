<?php
$nome = $_POST['nome'];
$email = $_POST['email'];
$telefone = $_POST['telefone'];
$cpf = $_POST['cpf'];
$senha = $_POST['senha'];
$codigoadm = $_POST['codigoadm'];
    if((!$nome) || (!$email) || (!$telefone) || (!$cpf) || (!$senha)){
        echo " Por favor, todos campos devem ser preenchidos! <br/><br/>";
        include "cadastro.php";
        exit;
    }

    $senha_encriptada = password_hash($senha, PASSWORD_DEFAULT);

	include "config.php";

    $sql = "INSERT INTO `usuario` (`nome`, `email`, `telefone`, `cpf`, `senha`)
    VALUES ('$nome', '$email', '$telefone', '$cpf', '$senha_encriptada')";

    if (!mysqli_query($conexao, $sql)) {
        die("Erro ao inserir: " . mysqli_error($conexao));
    }

    if(!empty($codigoadm)){
    $sqlAdm = "INSERT INTO autenticadm (codigoadm)
    VALUES ('$codigoadm')";

        if (!mysqli_query($conexao, $sqlAdm)) {
            die("Erro ao inserir adm: " . mysqli_error($conexao));
        }
    }

    header("Location: login.php");
    exit;
?>