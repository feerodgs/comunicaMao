<?php

error_reporting(0);
ini_set("display_errors", 0);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="bootstrap/css/bootstrap.min.css">
    <title>Comunicamão</title>
    <style>
        .gradient-custom {

            background: #6a11cb;

            background: -webkit-linear-gradient(to bottom, #ff930fb8, #ffe1b3);

            background: linear-gradient(to bottom, #ff930fb8, #ffe1b3);
        }
    </style>
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form name="login" id="login" method="post" action="validar.php">
                                <div class="mb-md-5 mt-md-4 pb-5">
                                    <img src="img/logo.png" style="width: 185px;" alt="logo">
                                    <h2 class="fw-bold mb-2 text-uppercase text-white">Login</h2>
                                    <p class="text-white-50 mb-5">Acesse com seu Usuário e Senha !</p>
                                    <?php
                                    if ($_POST['mensagem'] != "") { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php print $_POST['mensagem']; ?>
                                        </div>
                                    <?php } ?>
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" id="usuario" class="form-control" name="usuario" placeholder="Usuário" onkeypress="removeUsuario();">
                                            <label for="usuario">Usuário</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-usuario">
                                                <b>Favor preencher o usuário.</b>
                                            </div>
                                        </div>

                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="senha" placeholder="Senha" name="senha" onkeypress="removeSenha();">
                                            <label for="password">Senha</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-senha">
                                                <b>Favor preencher a senha.</b>
                                            </div>
                                        </div>
                                    </div>

                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="button" onclick="logar();">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <script>
        function logar() {

            var usuario = document.login.usuario.value;
            var senha = document.login.senha.value;

            if (usuario == "") {
                const elemento = document.getElementById("usuario");
                const aviso = document.getElementById("aviso-usuario");
                aviso.classList.remove("hide");
                elemento.classList.add("is-invalid");
                return false;
            }

            if (senha == "") {
                const elemento = document.getElementById("senha");
                const aviso = document.getElementById("aviso-senha");
                aviso.classList.remove("hide");
                elemento.classList.add("is-invalid");
                return false;
            }

            document.login.submit();

        }

        function removeUsuario() {

            const elemento = document.getElementById("usuario");
            const aviso = document.getElementById("aviso-usuario");

            if (elemento.classList.contains("is-invalid")) {
                elemento.classList.remove("is-invalid");
                aviso.classList.add("hide");
            }
        }

        function removeSenha() {

            const elemento = document.getElementById("senha");
            const aviso = document.getElementById("aviso-senha");

            if (elemento.classList.contains("is-invalid")) {
                elemento.classList.remove("is-invalid");
                aviso.classList.add("hide");
            }
        }
    </script>
</body>

</html>