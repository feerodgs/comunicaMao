<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'vendor/autoload.php';
require 'class/FirebaseDatabase.php';

use Kreait\Firebase\Factory;

$serviceAccountFile = 'db/firebase-credentials.json';

$factory = (new Factory)
    ->withServiceAccount($serviceAccountFile)
    ->withDatabaseUri("https://comunicamao-a541b-default-rtdb.firebaseio.com/");

$auth = $factory->createAuth();
$database = $factory->createDatabase();

function criarUsuario($email, $password, $dadosAdicionais = [])
{
    global $auth, $database;

    try {

        $usuario = $auth->createUserWithEmailAndPassword($email, $password);
        $uid = $usuario->uid;

        $auth->sendEmailVerificationLink($email);

        $userData = array_merge(['email' => $email], $dadosAdicionais);
        $database->getReference('usuarios/' . $uid)->set($userData);

        echo "Usuário criado com sucesso! Um e-mail de verificação foi enviado para $email.";
    } catch (\Kreait\Firebase\Exception\AuthException $e) {
        echo 'Erro ao criar o usuário: ' . $e->getMessage();
    } catch (\Kreait\Firebase\Exception\FirebaseException $e) {
        echo 'Erro no Firebase: ' . $e->getMessage();
    }
}

$firebase = new FirebaseDatabase(
    __DIR__ . "/db/firebase-credentials.json",
    "https://comunicamao-a541b-default-rtdb.firebaseio.com/"
);

$max_id = $firebase->buscarUsuarioComMaiorId();

if ($max_id == "su") {
    $new_id = 1;
} else {
    $new_id = intval($max_id['id_usuario']) + 1;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' and $_POST['acao'] == "cadastrar") {


    $email = $_POST['email'];
    $password = $_POST['senha'];
    $nome = $_POST['nome'];
    $tipo = $_POST['tipo'];

    criarUsuario($email, $password, ['usuario' => $nome, 'tipo' => $tipo, 'id_usuario' => $new_id]);
}
