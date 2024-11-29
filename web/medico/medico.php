<?php

include "../includes/cabecalho.php";
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comunicamão</title>

    <style>
        .chat-box {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            height: 70vh;
            border: 1px solid #dee2e6;
            border-radius: 5px;
            background-color: #ffffff;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 10px;
        }

        .message {
            margin-bottom: 10px;
            max-width: 70%;
            padding: 10px;
            border-radius: 10px;
        }

        .message-left {
            text-align: left;
            background-color: #e9ecef;
            margin-right: auto;
        }

        .message-right {
            text-align: right;
            background-color: #ff8b41;
            color: #ffffff;
            margin-left: auto;
        }

        .chat-footer {
            padding: 10px;
            border-top: 1px solid #dee2e6;
            display: flex;
            gap: 10px;
        }
    </style>
</head>

<body>
    <?php /*  <div class="container">
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
                            <input type="hidden" name="medico" id="medico" value="<?php print $_SESSION['id_usuario']; ?>">
                        </form>
                        <div class="row mt-3">
                            <div class="col-md-12 text-end"><button type="button" name="encerrar" class="btn btn-danger" onclick="encerrar()">Encerrar atendimento</button></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> */ ?>

    <div class="container">
        <div class="row">
            <div class="col-md-9">
                <div class="chat-box">

                    <div class="chat-messages" id="chat-mensagem">

                    </div>


                    <div class="chat-footer">
                        <input type="text" class="form-control" id="messageInput" placeholder="Digite sua mensagem">
                        <button class="btn text-white" style="background-color:#ff8b41; " onclick="sendMessage()">Enviar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-3">
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4 d-flex justify-content-center align-items-center p-3" style="border-bottom-left-radius: 5px; border-top-left-radius: 5px; background-color:#ff8b41;">
                            <i class="fa-solid fa-user-injured fs-1 text-white"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <label class="form-label">Paciente</label>
                                <input type="number" name="destinatario" id="destinatario" class="form-control" onkeydown="limpaChat()">
                            </div>
                        </div>
                    </div>
                </div>
                <br>
                <div class="card mb-3" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4 d-flex justify-content-center align-items-center p-3" style="border-bottom-left-radius: 5px; border-top-left-radius: 5px; background-color:#ff8b41;">
                            <i class="fa-solid fa-user-doctor fs-1 text-white"></i>
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">

                                <label class="form-label">Código do Médico</label>
                                <br>
                                <div class="text-center">
                                    <span class="text-center fs-3 fw-bolder" style="color: #ff8b41;"><?php print $_SESSION['id_usuario'] ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <form name="encerrado" id="encerrado" method="POST" action="index.php">
        <input type="hidden" name="mensagem" id="mensagem" value="Atendimento Encerrado com Sucesso">
    </form>

    <form name="dados" id="dados">
        <input type="hidden" name="medico" id="medico" value="<?php print $_SESSION['id_usuario'] ?>">
        <input type="hidden" name="uuid" id="uuid" value="">
    </form>


    <script>
        let isBusy = false


        function buscar() {

            if (isBusy || $('#destinatario').val().trim() == '') {
                return;
            }

            var acao = "atender";
            var medico = $('#medico').val();
            var paciente = $('#destinatario').val();

            isBusy = true;
            $.ajax({
                type: "POST",
                url: "../listener.php",
                data: 'acao=' + acao +
                    '&medico=' + medico +
                    '&id_conversa=' + $('#mensagens').val() +
                    '&paciente=' + paciente,
                dataType: "html",
                success: function(response) {

                    var mensagens = response.split("|");

                    var mensagemMaisRecente = null;
                    var maiorTimestamp = null;
                    var mensagemKey = null;


                    mensagens.forEach(function(mensagem) {

                        var campos = mensagem.split("&");
                        var mensagemObj = {};


                        campos.forEach(function(campo) {
                            var chaveValor = campo.split("=");
                            mensagemObj[chaveValor[0]] = chaveValor[1];
                        });

                        if (mensagemObj.Mensagem && mensagemObj.Timestamp) {

                            var timestampDate = mensagemObj.Timestamp;


                            if (!maiorTimestamp || timestampDate > maiorTimestamp) {
                                maiorTimestamp = timestampDate;
                                mensagemMaisRecente = mensagemObj.Mensagem;
                                mensagemKey = mensagemObj.key;
                            }
                        }
                    });

                    if (mensagemMaisRecente) {
                        console.log("Mensagem mais recente:", mensagemMaisRecente);
                    } else {
                        console.log("Nenhuma mensagem encontrada.");
                    }

                    const messageText = mensagemMaisRecente;
                    const messageHorario = maiorTimestamp;

                    if (messageText && $('#uuid').val() != mensagemKey) {
                        const chatMessages = document.querySelector('.chat-messages');
                        const messageElement = document.createElement('div');

                        messageElement.classList.add('message', 'message-left');

                        messageElement.innerHTML = `
                        <span class="username">Paciente:</span>
                        <p class="text mb-0">${messageText}</p>
                        <p class="fst-italic" style="font-size: 0.8rem">${messageHorario}</p>
                    `;

                        chatMessages.appendChild(messageElement);
                        chatMessages.scrollTop = chatMessages.scrollHeight; // Scroll para a última mensagem
                        $('#uuid').val(mensagemKey);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Erro:', error);
                    Swal.fire("", "Ops! Algo deu errado no processo.", "error");
                },
                complete: function() {
                    isBusy = false;
                }
            });
        }



        setInterval(buscar, 3000);

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


        function sendMessage() {
            const messageInput = document.getElementById('messageInput');
            const messageText = messageInput.value.trim();
            var paciente = $('#destinatario').val();
            var medico = $('#medico').val();

            if (paciente == medico) {
                Swal.fire("", "Você não pode enviar mensagens para você mesmo !", "warning");
                return false;
            }


            if (messageText) {

                const chatMessages = document.querySelector('.chat-messages');
                const messageElement = document.createElement('div');
                const now = new Date();

                var d = new Date();
                var strDate = d.getDate() + "/" + (d.getMonth() + 1) + "/" + d.getFullYear() + " " + d.getHours() + ":" + d.getMinutes() + ":" + d.getSeconds();
                messageElement.classList.add('message', 'message-right');

                messageElement.innerHTML = `
                <span class="username">Você:</span>
                <p class="text  mb-0">${messageText}</p>
                <p class="fst-italic" style="font-size: 0.8rem">${strDate}</p>
            `;
                const medico = $('#medico').val();
                const acao = "inserir";
                const destinatario = $('#destinatario').val();
                const mensagem = messageText;


                if (destinatario == "") {
                    Swal.fire("", "Favor preencher o campo do Paciente", "warning");
                    $("#destinatario").focus();
                    return false;
                }

                $.ajax({
                    type: "POST",
                    url: "../insert.php",
                    data: 'acao=' + acao +
                        '&medico=' + medico +
                        '&destinatario=' + destinatario +
                        '&mensagem=' + mensagem,
                    dataType: "html",
                    success: function(response) {

                        if (response != "succes") {
                            Swal.fire("", "Erro ao enviar mensagem ! Tente novamente.", "warning");
                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Erro:', error);
                        Swal.fire("", "Ops! Algo deu errado no processo.", "error");
                    }
                });

                chatMessages.appendChild(messageElement);
                chatMessages.scrollTop = chatMessages.scrollHeight;
                messageInput.value = '';
            }
        }


        function limpaChat() {
            console.log("entro");
            $('#chat-mensagem').html("");
            $('#uuid').val("");

        }
    </script>

</body>

</html>