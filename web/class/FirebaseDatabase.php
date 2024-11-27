<?php

require 'vendor/autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\Auth;
use Kreait\Firebase\Exception\Auth\InvalidPassword;
use Kreait\Firebase\Exception\Auth\UserNotFound;


class FirebaseDatabase
{
    private $database;
    private $auth;

    public function __construct($serviceAccountPath, $databaseUri)
    {
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccountPath)
            ->withDatabaseUri($databaseUri);

        $this->database = $firebase->createDatabase();
        $this->auth = $firebase->createAuth();
    }

    public function insert($path, $data)
    {
        $newEntry = $this->database->getReference($path)->push($data);
        return $newEntry->getKey();
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

    public function selectByUid($path, $uid)
    {
        $results = $this->database->getReference("$path/$uid")->getValue();
        return $results;
    }

    public function selectWithFilter($path, $field, $value)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value, $field)
            ->getValue();

        return $results;
    }

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

        $allResults = array_merge_recursive((array)$remetenteResults, (array)$destinatarioResults);

        return $allResults;
    }

    public function updateWithFilter($path, $field, $value, $data)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value)
            ->getValue();

        if ($results) {
            foreach ($results as $key => $record) {
                $this->database->getReference("$path/$key")->update($data);
            }
            return true;
        }

        return false;
    }

    public function deleteWithFilter($path, $field, $value)
    {
        $results = $this->database->getReference($path)
            ->orderByChild($field)
            ->equalTo($value)
            ->getValue();

        if ($results) {
            foreach ($results as $key => $record) {

                $this->database->getReference("$path/$key")->remove();
            }
            return true;
        }

        return false;
    }

    public function login($email, $password)
    {
        try {
            $signInResult = $this->auth->signInWithEmailAndPassword($email, $password);
            $userId = $signInResult->firebaseUserId();
            $idToken = $signInResult->idToken();

            $user = $this->auth->getUserByEmail('eduardo@uricer.edu.br');

            if (!$user->emailVerified) {
                return [
                    'error' => 'E-mail não autenticado.'
                ];
            }

            return [
                'userId' => $userId,
                'idToken' => $idToken,
                'message' => 'Login realizado com sucesso!'
            ];
        } catch (InvalidPassword $e) {
            return [
                'error' => 'Senha incorreta.'
            ];
        } catch (UserNotFound $e) {
            return [
                'error' => 'Usuário não encontrado.'
            ];
        } catch (\Exception $e) {
            if ($e->getMessage() == "INVALID_LOGIN_CREDENTIALS") {
                $erro = "Credenciais Inválidas";
            } else {
                $erro = 'Erro ao realizar o login: ' . $e->getMessage();
            }
            return [
                'error' => $erro
            ];
        }
    }

    public function buscarUsuarioComMaiorId()
    {

        try {
            // Referência ao nó de usuários no Realtime Database
            $ref = $this->database->getReference('usuarios');

            // Ordenar os usuários pelo campo 'id_usuario' e pegar o último item
            $snapshot = $ref->orderByChild('id_usuario')->limitToLast(1)->getSnapshot();

            // Obter os dados do usuário
            $usuarios = $snapshot->getValue();

            if (!empty($usuarios)) {
                // Como limitToLast retorna uma lista, pegamos o primeiro registro
                $usuario = array_values($usuarios)[0];
                return $usuario;
            } else {
                return "su";//Sem Usuarios
            }
        } catch (\Kreait\Firebase\Exception\DatabaseException $e) {
            echo 'Erro ao acessar o Realtime Database: ' . $e->getMessage();
        }
    }
}
// try {

//     $firebase = new FirebaseDatabase(
//         "../db/firebase-credentials.json",
//         "https://comunicamao-a541b-default-rtdb.firebaseio.com/"
//     );

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
    // $dadosRemetente = $firebase->selectWithFilter("conversas", "destinatario", 1);
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
