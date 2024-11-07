<?php

include "../includes/cabecalho.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicamão</title>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-md-2">
                <div class="card mt-3">
                    <div class="card-header">
                        Atendimento Número
                    </div>
                    <div class="card-body text-center">
                        <h1 class="text-success h1"><?php print $_POST['numAtendimento']; ?></h1>
                    </div>
                </div>
            </div>
            <div class="col-md-10">
                <div class="card mt-3">
                    <div class="card-header">
                        Médico
                    </div>

                    <div class="card-body">
                        <form name="dialogo" id="dialogo">
                            <textarea name="conversa" id="conversa" cols="30" rows="10" class="form-control"></textarea>
                            <input type="hidden" name="atendimento" id="atendimento" value="<?php print $_POST['numAtendimento']; ?>">
                        </form>
                        <div class="row mt-3">
                            <div class="col-md-12 text-end"><button type="button" name="encerrar" class="btn btn-danger" onclick="encerrar()">Encerrar atendimento</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form name="encerrado" id="encerrado" method="POST" action="index.php">
        <input type="hidden" name="mensagem" id="mensagem" value="Atendimento Encerrado com Sucesso">
    </form>


    
    <script>
        function envia() {
            var conteudo = $('#conversa').val();
            var acao = "atender";
            var atendimento = $('#atendimento').val();
            $.ajax({
                type: "POST",
                url: "acoes.php",
                data: 'conteudo=' + conteudo +
                    '&acao=' + acao +
                    '&atendimento=' + atendimento,
                dataType: "html",
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error('Erro:', error);
                    Swal.fire("", "Ops! Algo deu errado no processo.", "error");
                }
            });
        }

        envia();
        setInterval(envia, 5000);

        function encerrar() {
            var acao = "encerrar";
            var atendimento = $('#atendimento').val();
            $.ajax({
                type: "POST",
                url: "acoes.php",
                data: 'atendimento=' + atendimento +
                    '&acao=' + acao,
                dataType: "html",
                success: function(response) {
                    if (response == 'encerrado') {
                        document.encerrado.submit();
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro:', error);
                    Swal.fire("", "Ops! Algo deu errado no processo.", "error");
                }
            });
        }
    </script>

</body>

</html>