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
                            <img src="img/logo.png" style="width: 185px;" alt="logo">
                            <h2 class="fw-bold mb-2 text-uppercase text-white">Cadastro</h2>
                            <p class="text-white-50 mb-5">Faça o cadastro dos usuários aqui !</p>
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5 mb-5" type="button" onclick="criarUsuario('m');">Cadastrar Medico</button>
                            <button data-mdb-button-init data-mdb-ripple-init class="btn btn-outline-light btn-lg px-5" type="button" onclick="criarUsuario('p');">Cadastrar Paciente</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <form name="tipo_usuario" id="tipo_usuario" method="post" action="cadastro.php">
        <input type="hidden" name="tipo" id="tipo" value="">
    </form>

    <script src="js/jquery.min.js"></script>
    <script src="js/sweetalert2.all.min.js"></script>

    <script>
        function criarUsuario(tipo) {
            $("#tipo").val(tipo);
            $("#tipo_usuario").submit();
        }
    </script>
</body>