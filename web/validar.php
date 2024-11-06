<?php

error_reporting(0);
ini_set("display_errors", 0);

include "includes/conexao.php";
include "includes/session.php";

$senha = $_POST['senha'];
$usuario = $_POST['usuario'];

if (isset($_POST['senha']) and isset($_POST['usuario'])) {

    $senhaMD5 = md5($senha);

    $query = "SELECT * FROM usuarios WHERE usuario = '$usuario' AND senha = '$senhaMD5'";
    $result = mysqli_query($conect, $query);
    $dados = mysqli_fetch_array($result);
    $num_result = mysqli_num_rows($result);

    if ($num_result > 0) {

        if ($dados['tipo'] == "m") {

            $_SESSION['usuario']        = $dados['usuario'];
            $_SESSION['tipo']           = $dados['tipo'];
            $_SESSION['id_usuario']     = $dados['id_usuario'];
            $_SESSION['logado']         = "s";

            header("Location: medico/index.php");
            exit();
        } else if ($dados['tipo'] == "p") {

            $_SESSION['usuario']        = $dados['usuario'];
            $_SESSION['tipo']           = $dados['tipo'];
            $_SESSION['id_usuario']     = $dados['id_usuario'];
            $_SESSION['logado']         = "s";
            header("Location: cliente/index.php");
            exit();
        } else {
            $mensagem = "Erro no login ! Favor contate o administrador do sistema.";
        }
    } else {
        $mensagem = "Erro ao login ! Usuario ou senha incorreto.";
    }
}


if ($mensagem != "") { ?>

    <form name="erro" id="erro" method="post" action="login.php">
        <input type="hidden" name="mensagem" id="mensagem" value="<?php print $mensagem; ?>">
    </form>

    <script>
        document.erro.submit();
    </script>
<?php } ?>