import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:camera/camera.dart';
import 'package:gallery_saver/gallery_saver.dart';
import 'package:http/http.dart' as http;
import 'camera_screen.dart';
import 'preview_screen.dart';
import 'message.dart';

class MyHomePage extends StatefulWidget {
  final String title;
  final List<CameraDescription> cameras;

  const MyHomePage({super.key, required this.title, required this.cameras});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  XFile? _imageFile;
  TextEditingController _controller = TextEditingController();
  List<Message> _messages = [];
  final String apiUrl =
      'https://drzbdggakc.execute-api.us-east-1.amazonaws.com/get-label';

  // Função para abrir a câmera
  Future<void> _openCamera() async {
    final result = await Navigator.push(
      context,
      MaterialPageRoute(
        builder: (context) => CameraScreen(cameras: widget.cameras),
      ),
    );

    if (result != null && result is XFile) {
      setState(() {
        _imageFile = result; // Salva o arquivo de imagem tirado
      });
      await Navigator.push(
        context,
        MaterialPageRoute(
          builder: (context) => PreviewScreen(
            imagePath: result.path,
            onSend: _sendImageMessage,
            onCancel: _cancelImage,
          ),
        ),
      );
    }
  }

  // Envia a mensagem de texto
  void _sendMessage(String text) {
    setState(() {
      _messages.add(Message(text: text, isSentByMe: true));
      _controller.clear();
    });
  }

  // Envia a imagem para a lista de mensagens e para a API
  Future<void> _sendImageMessage(String imagePath) async {
    setState(() {
      _messages
          .add(Message(imagePath: imagePath, isImage: true, isSentByMe: true));
      _imageFile = null; // Limpa a imagem após o envio
    });
    await _sendImageToApi(imagePath);
  }

  // Função para cancelar a imagem
  void _cancelImage() {
    setState(() {
      _imageFile = null;
    });
    Navigator.pop(context);
  }

  // Envia a imagem para a API
  Future<void> _sendImageToApi(String imagePath) async {
    try {
      final bytes = await File(imagePath).readAsBytes();
      final base64Image = base64Encode(bytes);

      final response = await http.post(
        Uri.parse(apiUrl),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'image_data': base64Image}),
      );

      if (response.statusCode == 200) {
        print("Imagem enviada com sucesso para a API.");
        print("Falha ao enviar a imagem. Código: ${response.body}");
      } else {
        print("Falha ao enviar a imagem. Código: ${response.statusCode}");
      }
    } catch (e) {
      print("Erro ao enviar imagem para a API: $e");
    }
  }

  // Construção de uma mensagem na lista de mensagens
  Widget _buildMessage(Message message) {
    if (message.isImage) {
      return Align(
        alignment:
            message.isSentByMe ? Alignment.centerRight : Alignment.centerLeft,
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: ClipRRect(
            borderRadius: BorderRadius.circular(10),
            child: Image.file(
              File(message.imagePath!),
              width: 200,
              height: 200,
              fit: BoxFit.cover,
            ),
          ),
        ),
      );
    } else {
      return Align(
        alignment:
            message.isSentByMe ? Alignment.centerRight : Alignment.centerLeft,
        child: Padding(
          padding: const EdgeInsets.all(8.0),
          child: Container(
            padding: const EdgeInsets.symmetric(vertical: 10, horizontal: 15),
            decoration: BoxDecoration(
              color: message.isSentByMe ? Colors.teal[200] : Colors.grey[300],
              borderRadius: BorderRadius.circular(20),
            ),
            child: Text(
              message.text!,
              style: TextStyle(color: Colors.black87),
            ),
          ),
        ),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("ComunicaMao"),
        backgroundColor: Colors.teal,
      ),
      body: Column(
        children: [
          Expanded(
            child: ListView.builder(
              reverse: true,
              itemCount: _messages.length,
              itemBuilder: (context, index) {
                final message = _messages[_messages.length - 1 - index];
                return _buildMessage(message);
              },
            ),
          ),
          if (_imageFile != null)
            PreviewScreen(
              imagePath: _imageFile!.path,
              onSend: _sendImageMessage,
              onCancel: _cancelImage,
            ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              children: [
                IconButton(
                  icon: Icon(Icons.camera_alt),
                  color: Colors.teal,
                  onPressed: _openCamera, // Abre a câmera quando pressionado
                ),
                Expanded(
                  child: TextField(
                    controller: _controller,
                    decoration: InputDecoration(
                      hintText: "Digite uma mensagem",
                      filled: true,
                      fillColor: Colors.white,
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(30),
                        borderSide: BorderSide.none,
                      ),
                      contentPadding:
                          EdgeInsets.symmetric(horizontal: 20, vertical: 5),
                    ),
                  ),
                ),
                IconButton(
                  icon: Icon(Icons.send),
                  color: Colors.teal,
                  onPressed: () {
                    if (_controller.text.trim().isNotEmpty) {
                      _sendMessage(_controller.text.trim());
                    }
                  },
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }
}
