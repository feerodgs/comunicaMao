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
            background: linear-gradient(to bottom, #ff930fb8, #ffe1b3);

        }
    </style>
</head>


<body>
    <div class="gradient-custom position-fixed w-100 h-100"></div>
    <section class="vh-100 w-100">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <form name="login" id="login" method="post" action="inserir_usuario.php">
                                <input type="hidden" name="acao" id="acao" value="cadastrar">
                                <div class="mb-md-5 mt-md-4">
                                    <img src="img/logo.png" style="width: 185px;" alt="logo">
                                    <h2 class="fw-bold mb-2 text-uppercase text-white">Cadastro</h2>
                                    <p class="text-white-50 mb-5">Crie aqui sua conta !</p>
                                    <?php
                                    if ($_POST['mensagem'] != "") { ?>
                                        <div class="alert alert-danger" role="alert">
                                            <?php print $_POST['mensagem']; ?>
                                        </div>
                                    <?php } ?>
                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating mb-3">
                                            <input type="text" id="nome" class="form-control" name="nome" placeholder="Nome" onkeypress="removeNome();">
                                            <label for="nome">Nome</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-nome">
                                                <b>Favor preencher o Nome.</b>
                                            </div>
                                        </div>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating mb-3">
                                            <input type="mail" id="usuario" class="form-control" name="usuario" placeholder="Usuário" onkeypress="removeUsuario();">
                                            <label for="usuario">E-mail</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-usuario">
                                                <b>Favor preencher o E-mail.</b>
                                            </div>
                                        </div>

                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="senha" placeholder="Senha" name="senha" onkeypress="removeSenha();">
                                            <label for="password">Senha</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-senha">
                                                <b></b>
                                            </div>
                                        </div>
                                    </div>

                                    <div data-mdb-input-init class="form-outline form-white mb-4">
                                        <div class="form-floating">
                                            <input type="password" class="form-control" id="senhaConfirmada" placeholder="Senha" name="senhaConfirmada" onkeypress="removeSenhaConfirmada();">
                                            <label for="senhaConfirmada">Confirme sua senha</label>
                                            <div class="invalid-feedback hide text-start" id="aviso-senhaConfirmada">
                                                <b></b>
                                            </div>
                                        </div>
                                    </div>


                                    <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="button" onclick="validar();">Cadastrar-se</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>
    <script src="js/jquery.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>
    <script>
        function validar() {

            var nome = document.login.nome.value;
            var usuario = document.login.usuario.value;
            var senha = document.login.senha.value;
            var senhaConfirmada = document.login.senhaConfirmada.value;
            var acao = document.login.acao.value;

            if (nome == "") {
                const elemento = document.getElementById("nome");
                const aviso = document.getElementById("aviso-nome");
                aviso.classList.remove("hide");
                elemento.classList.add("is-invalid");
                return false;
            }


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
                document.querySelector("#aviso-senha b").textContent = "O campo senha não pode estar em branco.";
                return false;
            }

            if (senhaConfirmada == "") {
                const elemento = document.getElementById("senhaConfirmada");
                const aviso = document.getElementById("aviso-senhaConfirmada");
                aviso.classList.remove("hide");
                elemento.classList.add("is-invalid");
                document.querySelector("#aviso-senhaConfirmada b").textContent = "O campo Confirmação de Senha não pode estar em branco.";
                return false;
            }

            if (senha != senhaConfirmada) {

                const senhaConfirmada = document.getElementById("senhaConfirmada");
                const avisoConfirmada = document.getElementById("aviso-senhaConfirmada");
                const senha = document.getElementById("senha");
                const avisoSenha = document.getElementById("aviso-senha");
                avisoConfirmada.classList.remove("hide");
                senhaConfirmada.classList.add("is-invalid");
                avisoSenha.classList.remove("hide");
                senha.classList.add("is-invalid");
                document.querySelector("#aviso-senha b").textContent = "As senhas não são iguais";
                document.querySelector("#aviso-senhaConfirmada b").textContent = "As senhas não são iguais";
                return false;
            }

            var nome = document.login.nome.value;
            var usuario = document.login.usuario.value;
            var senha = document.login.senha.value;
            var senhaConfirmada = document.login.senhaConfirmada.value;
            var acao = document.login.acao.value;

            $.ajax({
                type: "POST",
                url: "inserir_usuario.php",
                data: 'acao=' + acao +
                    '&nome=' + nome +
                    '&senha=' + senha +
                    '&email=' + usuario,
                dataType: "html",
                success: function(response) {
                    Swal.fire({
                        title: "",
                        text: response,
                        icon: "success"
                    }).then(function() {
                        window.location = "index.php";
                    });
                }
            })


        }

        function removeNome() {

            const elemento = document.getElementById("nome");
            const aviso = document.getElementById("aviso-nome");

            if (elemento.classList.contains("is-invalid")) {
                elemento.classList.remove("is-invalid");
                aviso.classList.add("hide");
            }
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

        function removeSenhaConfirmada() {

            const elemento = document.getElementById("senhaConfirmada");
            const aviso = document.getElementById("aviso-senhaConfirmada");

            if (elemento.classList.contains("is-invalid")) {
                elemento.classList.remove("is-invalid");
                aviso.classList.add("hide");
            }
        }
    </script>
</body>

</html>