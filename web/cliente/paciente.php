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
        <div class="card mt-3">
            <div class="card-header">
                Cliente
            </div>
            <div class="card-body">
                <form name="dialogo" id="dialogo">
                    <textarea name="conversa" id="conversa" cols="30" rows="10" class="form-control"></textarea>
                    <input type="hidden" name="atendimento" id="atendimento" value="<?php print $_POST['atendimento']; ?>">
                </form>
            </div>
        </div>
    </div>

<form name="encerrar" id="encerrar" method="post" action="index.php">
    <input type="hidden" name="mensagem" id="mensagem" value="Atendimento Encerrado">
</form>

    <script>
        
        function envia() {
            var atendimento = $('#atendimento').val();
            var acao = 'consultar';
                $.ajax({
                    type: "POST",
                    url: "acoes.php",
                    data: 'atendimento=' + atendimento +
                          '&acao=' + acao,
                    dataType: "html",
                    success: function(response) {
                        
                        if(response == 'encerrada'){
                            $('#encerrar').submit();
                        }

                         $('#conversa').val(response);

                    },
                    error: function(xhr, status, error) {
                        console.error('Erro:', error);
                        Swal.fire("", "Ops! Algo deu errado no processo.", "error");
                    }
                });
        }

        envia();
        setInterval(envia, 2000);
    </script>

</body>

</html>