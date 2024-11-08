<?php
session_name("comunicamao");
session_start();

if (isset($_SESSION["usuario"])) {
    unset($_SESSION["usuario"]);
}
if (isset($_SESSION["tipo"])) {
    unset($_SESSION["tipo"]);
}
if (isset($_SESSION["id_usuario"])) {
    unset($_SESSION["id_usuario"]);
}
if (isset($_SESSION["logado"])) {
    unset($_SESSION["logado"]);
}

session_destroy();

header("Location: ../index.php");
exit();
