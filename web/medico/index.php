<?php 

include "../includes/cabecalho.php";

if(isset($_POST['mensagem']) and $_POST['mensagem'] != ''){ ?>
    
    <script>
        Swal.fire("","<?php print $_POST['mensagem'];?>", "success");
    </script>

<?php } ?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicamão</title>
</head>

<body class="bg-light">
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                Médico
            </div>
            <div class="card-body">
                <form name="iniciar" id="iniciar" action="medico.php" mathod="POST">
                    
                    <button type="button" class="btn btn-success" onclick="iniciarAtendimento()">Inicar Atendimento</button>
                </form>
            </div>
        </div>
    </div>

<form name="atendimento" id="atendimento">
    <input type="hidden" name="numAtendimento" id="numAtendimento">
</form>

    <script>

        function iniciarAtendimento() {
            var acao = "criar";
                $.ajax({
                    type: "POST",
                    url: "acoes.php",
                    data: '&acao=' + acao,
                    dataType: "html",
                    success: function(response) {
                        var resultado = response.split("&");

                        if(resultado[0] == 'sucesso'){
                            var idInserido = resultado[1];
                            $('#numAtendimento').val(idInserido);
                            document.atendimento.method = "POST";
                            document.atendimento.action = "medico.php";
                            document.atendimento.submit();
                        }
                    },
                });
        }

        
    </script>

</body>

</html>