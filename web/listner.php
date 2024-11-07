<?php
include "includes/conexao.php";
// listener.php
header("Content-Type: application/json");

$data = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture o dado enviado, aqui estamos assumindo que ele vem em JSON
    $data = json_decode(file_get_contents("php://input"), true);

    // Processar os dados recebidos, se necessário
    // Aqui, você pode armazenar ou manipular $data conforme necessário
    

}
