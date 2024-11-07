<?php

include "../includes/conexao.php";
include "../includes/session.php";

?>

<link rel="stylesheet" href="../bootstrap/css/bootstrap.min.css">
<script src="https://kit.fontawesome.com/afd85da1dd.js" crossorigin="anonymous"></script>

<nav class="navbar navbar-expand-lg bg-dark-subtle">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="../img/logo_2.png" alt="Comunicamão Logo" width="60" height="60"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-user"></i> <?php print ucfirst($_SESSION['usuario']);?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../includes/encerrar.php">Desconectar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

<script src="../js/jquery.min.js"></script>
<script src="../js/sweetalert2.all.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<script src="../bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="../bootstrap/js/bootstrap.esm.min.js"></script>