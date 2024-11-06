<?php

include "../includes/conexao.php";

$atendimento    = $_POST['atendimento'];
$acao           = $_POST['acao'];


if ($acao == "consultar") {

    $query_verifica = "SELECT ativa FROM conversas WHERE id = $atendimento";
    $result_verifica = mysqli_query($conect, $query_verifica);
    $dados_verifica = mysqli_fetch_array($result_verifica);

    $ativa = $dados_verifica['ativa'];


    if ($ativa == 's') {
        $query_insere = "SELECT conversa FROM conversas WHERE id = $atendimento";
        $result = mysqli_query($conect, $query_insere);
        $dados = mysqli_fetch_array($result);
        print $dados['conversa'];
        exit();
    }else{
        print "encerrada";
    }
}
