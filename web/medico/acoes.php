<?php 

error_reporting(0);

include "../includes/conexao.php";

$dialogo        = $_POST['conteudo'];
$acao           = $_POST['acao'];
$atendimento    = $_POST['atendimento'];

if($acao == "atender"){

$query_verifica = "SELECT ativa FROM conversas WHERE id = $atendimento";
$result_verifica = mysqli_query($conect, $query_verifica);
$dados_verifica = mysqli_fetch_array($result_verifica);
$ativo = $dados_verifica['ativa'];

    if($ativo == 's'){
        $query_insere = "UPDATE conversas SET conversa = '$dialogo' WHERE id = $atendimento";
        $result = mysqli_query($conect, $query_insere);

        print $dialogo;
        exit();
    }
}

if($acao == 'criar'){

    $query_criar = "INSERT INTO conversas (conversa, ativa) VALUES ('', 's')";
    $result_criar = mysqli_query($conect, $query_criar);
    $id_criado = mysqli_insert_id($conect);

    print "sucesso&$id_criado";
    exit();
    
}

if($acao == 'encerrar'){

    $query_encerra = "UPDATE conversas SET ativa = 'n' WHERE id = $atendimento";
    $result_encerra = mysqli_query($conect, $query_encerra);

    print "encerrado";

};