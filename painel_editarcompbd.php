<?php

include("config.php");

if ($_SERVER["REQUEST_METHOD"] != "POST") {
    echo "Acesso inválido!";
    exit();
}


/* PEGAR DADOS DO FORMULÁRIO */

$id = intval($_POST['id']);

$nome = $_POST['nome'];
$descricao = $_POST['descricao'];
$categoria = $_POST['categoria'];

$entrada = intval($_POST['entrada']);
$saida = intval($_POST['saida']);

$justificativa = $_POST['justificativa'];


/* VERIFICAR SE O COMPONENTE EXISTE */

$sql = "SELECT * FROM COMPONENTE WHERE IDCOMP = $id";

$resultado = $conexao->query($sql);

if ($resultado->num_rows == 0) {
    echo "Componente não encontrado!";
    exit();
}

$dados = $resultado->fetch_assoc();

$quantidadeAtual = intval($dados['QUANTIDADE']);


/* VERIFICAR OS VALORES */

if ($entrada < 0 || $saida < 0) {
    echo "A entrada e a saída não podem ser negativas!";
    exit();
}

if ($saida > $quantidadeAtual) {
    echo "A quantidade retirada é maior que a quantidade disponível!";
    exit();
}


/* CALCULAR NOVA QUANTIDADE */

$novaQuantidade = $quantidadeAtual + $entrada - $saida;


/* VERIFICAR IMAGEM */

$nomeImagem = $dados['IMAGEM'];

if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {

    $nomeArquivo = $_FILES['imagem']['name'];

    $extensao = strtolower(
        pathinfo($nomeArquivo, PATHINFO_EXTENSION)
    );

    $extensoesPermitidas = array(
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp'
    );

    if (!in_array($extensao, $extensoesPermitidas)) {
        echo "Formato de imagem não permitido!";
        exit();
    }

    $nomeImagem = uniqid() . "." . $extensao;

    $caminho = "componentes/" . $nomeImagem;

    if (!move_uploaded_file(
        $_FILES['imagem']['tmp_name'],
        $caminho
    )) {
        echo "Erro ao enviar a imagem!";
        exit();
    }
}


/* PROTEGER OS TEXTOS */

$nome = $conexao->real_escape_string($nome);
$descricao = $conexao->real_escape_string($descricao);
$categoria = $conexao->real_escape_string($categoria);
$nomeImagem = $conexao->real_escape_string($nomeImagem);
$justificativa = $conexao->real_escape_string($justificativa);


/* ATUALIZAR COMPONENTE */

$sqlUpdate = "
    UPDATE COMPONENTE
    SET
        NOME = '$nome',
        DESCRICAO = '$descricao',
        QUANTIDADE = $novaQuantidade,
        IMAGEM = '$nomeImagem',
        CATEGORIA = '$categoria'
    WHERE IDCOMP = $id
";

if (!$conexao->query($sqlUpdate)) {

    echo "Erro ao atualizar componente: "
         . $conexao->error;

    exit();
}


/* REGISTRAR ENTRADA */

if ($entrada > 0) {

    if (empty($justificativa)) {
        $justificativaEntrada =
            "Adição de componentes ao estoque.";
    } else {
        $justificativaEntrada = $justificativa;
    }

    $justificativaEntrada =
        $conexao->real_escape_string($justificativaEntrada);

    $sqlEntrada = "
        INSERT INTO HISTORICO_COMPONENTE
        (
            IDCOMP,
            TIPO,
            QUANTIDADE,
            JUSTIFICATIVA
        )
        VALUES
        (
            $id,
            'ENTRADA',
            $entrada,
            '$justificativaEntrada'
        )
    ";

    if (!$conexao->query($sqlEntrada)) {

        echo "Erro ao registrar entrada no histórico: "
             . $conexao->error;

        exit();
    }
}


/* REGISTRAR SAÍDA */

if ($saida > 0) {

    if (empty($justificativa)) {
        $justificativaSaida =
            "Retirada de componentes do estoque.";
    } else {
        $justificativaSaida = $justificativa;
    }

    $justificativaSaida =
        $conexao->real_escape_string($justificativaSaida);

    $sqlSaida = "
        INSERT INTO HISTORICO_COMPONENTE
        (
            IDCOMP,
            TIPO,
            QUANTIDADE,
            JUSTIFICATIVA
        )
        VALUES
        (
            $id,
            'SAIDA',
            $saida,
            '$justificativaSaida'
        )
    ";

    if (!$conexao->query($sqlSaida)) {

        echo "Erro ao registrar saída no histórico: "
             . $conexao->error;

        exit();
    }
}


/* VOLTAR PARA A PÁGINA DE EDIÇÃO */

header("Location: painel_componentes.php?id=" . $id);
exit();

?>
