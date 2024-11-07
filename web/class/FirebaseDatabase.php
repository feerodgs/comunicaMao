<?php

require '../vendor/autoload.php';

use Kreait\Firebase\Factory;

class FirebaseDatabase
{
    private $database;

    public function __construct($serviceAccountPath, $databaseUri)
    {
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri($databaseUri);

        $this->database = $firebase->createDatabase();
    }

    public function insert($path, $data)
    {
        $newEntry = $this->database->getReference($path)->push($data);
        return $newEntry->getKey(); // Retorna o ID gerado
    }

    public function update($path, $data)
    {
        $this->database->getReference($path)->update($data);
        return true;
    }

    public function delete($path)
    {
        $this->database->getReference($path)->remove();
        return true;
    }

    public function select($path)
    {
        return $this->database->getReference($path)->getValue();
    }

    // Novo método para buscar registros com filtros (ex: remetente ou destinatario)
    public function selectWithFilter($path, $field, $value)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value)
            ->getValue();

        return $results;
    }

    // Método para buscar registros com remetente ou destinatario igual ao valor
    public function selectByRemetenteOuDestinatario($path, $userId)
    {
        $remetenteResults = $this->database->getReference($path)
            ->orderByChild('remetente')
            ->equalTo($userId)
            ->getValue();

        $destinatarioResults = $this->database->getReference($path)
            ->orderByChild('destinatario')
            ->equalTo($userId)
            ->getValue();

        // Combina os dois resultados
        $allResults = array_merge_recursive((array)$remetenteResults, (array)$destinatarioResults);

        return $allResults;
    }

    // Novo método para atualizar registros com base em um filtro (campo e valor)
    public function updateWithFilter($path, $field, $value, $data)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value)
            ->getValue();

        // Se algum registro for encontrado, atualiza os dados
        if ($results) {
            foreach ($results as $key => $record) {
                // Atualiza cada registro encontrado com o novo dado
                $this->database->getReference("$path/$key")->update($data);
            }
            return true;
        }

        return false; // Caso não tenha encontrado nenhum registro
    }

    // Novo método para excluir registros com base em um filtro (campo e valor)
    public function deleteWithFilter($path, $field, $value)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value)
            ->getValue();

        // Se algum registro for encontrado, deleta os dados
        if ($results) {
            foreach ($results as $key => $record) {
                // Deleta cada registro encontrado
                $this->database->getReference("$path/$key")->remove();
            }
            return true;
        }

        return false; // Caso não tenha encontrado nenhum registro
    }
}

// Exemplo de uso:
// try {
    // Inicialize a conexão com o Firebase
    // $firebase = new FirebaseDatabase(
    //     "../db/firebase-credentials.json",
    //     "https://comunicamao-a541b-default-rtdb.firebaseio.com/"
    // );

    /*
    *
    * Inserindo dados com timestamp
    *
    */
    // $id = $firebase->insert('conversas', [
    //     'mensagem' => 'sapiquinha',
    //     'destinatario' => 5,
    //     'remetente' => 1,
    //     'horario' => time()
    // ]);

    // echo 'Dados inseridos com sucesso! ID: ' . $id . PHP_EOL;

    /*
    *
    * Selecionando dados onde remetente = 1
    *
    */
    // $dadosRemetente = $firebase->selectWithFilter("conversas", "remetente", 1);
    // echo "Registros com remetente 1:\n";
    // print_r($dadosRemetente);

    /*
    *
    * Atualizando dados onde remetente = 1
    *
    */
    // $updateData = ['mensagem' => 'mensagem atualizada'];
    // $updated = $firebase->updateWithFilter("conversas", "remetente", 1, $updateData);
    // if ($updated) {
    //     echo "Dados atualizados com sucesso!" . PHP_EOL;
    // } else {
    //     echo "Nenhum dado encontrado para atualizar." . PHP_EOL;
    // }

    /*
    *
    * Deletando dados onde destinatario = 5
    *
    */
//     $deleted = $firebase->deleteWithFilter("conversas", "destinatario", 5);
//     if ($deleted) {
//         echo "Dados deletados com sucesso!" . PHP_EOL;
//     } else {
//         echo "Nenhum dado encontrado para deletar." . PHP_EOL;
//     }
// } catch (Exception $e) {
//     echo 'Erro ao conectar no Firebase: ' . $e->getMessage();
// }
