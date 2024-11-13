<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$mensagem = $_POST['mensagem'];
$destinatario = $_POST['destinatario'];
$medico = $_POST['medico'];
$acao = $_POST['acao'];


require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

if ($acao == "inserir") {

    try {
        // Inicializar a fábrica do Firebase e passar o caminho para o arquivo JSON das credenciais
        $firebase = (new Factory)
            ->withServiceAccount(__DIR__ . "/db/firebase-credentials.json")
            ->withDatabaseUri("https://comunicamao-a541b-default-rtdb.firebaseio.com/");

        // Para acessar o banco de dados Realtime Database
        $database = $firebase->createDatabase();

        // Exemplo de inserção de dados com timestamp
        $newPost = $database
            ->getReference('conversas')
            ->push([
                'mensagem' => $mensagem,
                'destinatario' => intval($destinatario),
                'remetente' => intval($medico),
                'horario' => time()
            ]);

        echo 'succes';
    } catch (Exception $e) {
        echo 'Erro ao conectar no Firebase: ' . $e->getMessage();
    }
}
