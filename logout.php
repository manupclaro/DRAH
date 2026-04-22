<?php
    session_start();

    // Destroi todas as variáveis de sessão
    session_unset();

    // Destrói a sessão completamente
    session_destroy();

    // Redireciona para a página de login ou outra página inicial
    header("Location: login.php");
    exit(); // Garante que o script pare após o redirecionamento
?>