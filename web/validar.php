<?php

error_reporting(E_ALL);
ini_set("display_errors", 1);

require "class/FirebaseDatabase.php";
include "includes/session.php";

if (isset($_POST['senha']) and isset($_POST['usuario'])) {

    $senha = $_POST['senha'];
    $usuario = $_POST['usuario'];

    $firebase = new FirebaseDatabase(
        __DIR__ . "/db/firebase-credentials.json",
        "https://comunicamao-a541b-default-rtdb.firebaseio.com/"
    );

    $response = $firebase->login($usuario, $senha);

    if (isset($response['error'])) {
?>
        <form name="erro" id="erro" method="post" action="login.php">
            <input type="hidden" name="mensagem" id="mensagem" value="<?php print $response['error']; ?>">
        </form>

        <script>
            document.erro.submit();
        </script>
    <?php
        exit();
        // echo "Erro: " . $response['error'] . PHP_EOL;
    }

    $dados = $firebase->selectByUid("usuarios", $response['userId']);

    if ($dados['tipo'] == "p") {
        $mensagem = "Usário não possui essa permissão de acesso !"
    ?>
        <form name="erro" id="erro" method="post" action="login.php">
            <input type="hidden" name="mensagem" id="mensagem" value="<?php print $mensagem ?>">
        </form>

        <script>
            document.erro.submit();
        </script>
<?php
        exit();
        // echo "Erro: " . $response['error'] . PHP_EOL;        


    } else {
        // echo "Login realizado com sucesso! ID do usu�rio: " . $response['userId'] . PHP_EOL;
        // echo "Token de autentica��o: " . $response['idToken'] . PHP_EOL;


        // print_r($dados);

        $_SESSION['usuario']        = $dados['usuario'];
        $_SESSION['tipo']           = $dados['tipo'];
        $_SESSION['id_usuario']     = $dados['id_usuario'];
        $_SESSION['logado']         = "s";

        session_write_close();
        header("Location: medico/index.php");
        exit();
    }
}
