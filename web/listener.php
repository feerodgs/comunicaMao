<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('America/Sao_Paulo');
header('Content-Type: application/json;');

require 'class/FirebaseDatabase.php';
set_time_limit(0);
$destinatario = $_POST['medico'];
$referencePath = 'conversas';
$lastTimestamp = 0;
$paciente = $_POST['paciente'];

try {
    $firebase = new FirebaseDatabase(
        "db/firebase-credentials.json",
        "https://comunicamao-a541b-default-rtdb.firebaseio.com/"
    );

    // $dadosRemetente = $firebase->selectWithFilter($referencePath, "destinatario", intval($destinatario));
    $dadosRemetente = $firebase->selectWithFilter($referencePath, "destinatario", $destinatario);

    foreach ($dadosRemetente as $key => $record) {
        if (isset($record['horario'])) {

            if ($record['horario'] > $lastTimestamp) {
                $lastTimestamp = $record['horario'];
                $dataFormatada = date('d/m/Y H:i:s', $lastTimestamp);
            }

            if ($destinatario == $record['destinatario'] and $paciente == $record['remetente']) {
                echo "key={$key}&Destinatario={$record['destinatario']}&Remetente={$record['remetente']}&Mensagem={$record['mensagem']}&Timestamp={$dataFormatada}|";
                // exit();

            }
        }
    }
} catch (Exception $e) {
    echo 'Erro ao conectar no Firebase: ' . $e->getMessage();
}

/*
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
                        $dataFormatada = date("d/m/Y H:i:s", $lastTimestamp);
                    }
                    if ($destinatario == $record['destinatario']) {
                        // Processar o novo registro
                        echo "key={$key}&Destinatario={$record['destinatario']}&Remetente={$record['remetente']}&Mensagem={$record['mensagem']}&Timestamp={$dataFormatada}&";
                        // exit();
                        // Aqui você pode adicionar a lógica para processar o registro, como enviar e-mails, atualizar outros sistemas, etc.
                    }
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
*/