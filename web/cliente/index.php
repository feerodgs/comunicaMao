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

<body>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header">
                Cliente
            </div>
            <div class="card-body">
                <form name="acessar" id="acessar" method="POST" action="paciente.php">
                    <div class="row d-flex align-items-center">
                        <div class="col-md-10">
                            <input type="number" name="atendimento" id="atendimento" class="form-control" placeholder="Número do Atendimento">
                        </div>
                        <div class="col-md-2">
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Acessar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>