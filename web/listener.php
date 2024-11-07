<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;

set_time_limit(0); // Impede que o script expire

// Inicializar a fábrica do Firebase
$firebase = (new Factory)
    ->withServiceAccount(__DIR__ . "/db/firebase-credentials.json")
    ->withDatabaseUri("https://comunicamao-a541b-default-rtdb.firebaseio.com/");

// Acessar o banco de dados Realtime Database
$database = $firebase->createDatabase();

// Caminho da referência que será monitorada
$referencePath = 'conversas';

// Variável para armazenar o último timestamp processado
$lastTimestamp = 0;
if ($_POST['acao'] == "atender") {
    try {
        // Buscar registros com timestamp maior que o último processado
        $newRecordsSnapshot = $database
            ->getReference($referencePath)
            ->orderByChild('horario')
            ->startAt($lastTimestamp + 1) // Buscar registros após o último timestamp
            ->getSnapshot();

        $newRecords = $newRecordsSnapshot->getValue();

        if ($newRecords) {
            foreach ($newRecords as $key => $record) {
                // Verificar se o registro possui o campo 'timestamp'
                if (isset($record['horario'])) {
                    // Atualizar o último timestamp processado
                    if ($record['horario'] > $lastTimestamp) {
                        $lastTimestamp = $record['horario'];
                    }

                    // Processar o novo registro
                    echo "key={$key}&Destinatario={$record['destinatario']}&Remetente={$record['remetente']}&Mensagem={$record['mensagem']}&Timestamp={$record['horario']}";
                    // exit();
                    // Aqui você pode adicionar a lógica para processar o registro, como enviar e-mails, atualizar outros sistemas, etc.
                }
            }
        }

        // Esperar por um intervalo antes de verificar novamente (por exemplo, 5 segundos)
        sleep(5);
    } catch (Exception $e) {
        echo 'Erro ao acessar o Firebase: ' . $e->getMessage() . "\n";
        // Opcional: Esperar antes de tentar novamente em caso de erro
        sleep(10);
    }
}
