import 'dart:convert';
import 'dart:io';
import 'package:flutter/material.dart';
import 'package:firebase_database/firebase_database.dart';
import 'package:intl/intl.dart';
import 'package:camera/camera.dart';
import 'package:http/http.dart' as http;
import 'camera_screen.dart'; // Certifique-se de criar essa tela de câmera
import 'preview_screen.dart'; // Certifique-se de criar a tela de prévia da imagem
import '../models/message_model.dart';

class ChatScreen extends StatefulWidget {
  final List<CameraDescription> cameras; // Adicionado o parâmetro cameras

  const ChatScreen({super.key, required this.cameras});

  @override
  State<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {
  final TextEditingController messageController = TextEditingController();
  final int remetente = 2; // ID do remetente (ajuste conforme necessário)
  String? destinatario;
  late DatabaseReference databaseRef;
  XFile? _imageFile; // Para armazenar a imagem capturada

  final String apiUrl =
      'https://drzbdggakc.execute-api.us-east-1.amazonaws.com/get-label';

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();
    destinatario = ModalRoute.of(context)?.settings.arguments as String?;
    if (destinatario != null) {
      databaseRef = FirebaseDatabase.instance.ref('conversas');
    }
  }

  void sendMessage(String mensagem) {
    if (destinatario == null || mensagem.isEmpty) return;

    final horario =
        DateTime.now().millisecondsSinceEpoch ~/ 1000; // Horário em segundos

    // Enviar mensagem para o Firebase
    databaseRef.push().set({
      'destinatario': destinatario,
      'remetente': remetente.toString(),
      'mensagem': mensagem,
      'horario': horario,
    });

    messageController.clear();
  }

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

  Future<void> _sendImageMessage(String imagePath) async {
    String response = await _sendImageToApi(imagePath);

    setState(() {
      // Adiciona a imagem à lista de mensagens
      databaseRef.push().set({
        'destinatario': destinatario,
        'remetente': remetente.toString(),
        'mensagem': response,
        'horario': DateTime.now().millisecondsSinceEpoch ~/ 1000,
      });
      _imageFile = null;
    });

    Navigator.pop(context);
  }

  Future<String> _sendImageToApi(String imagePath) async {
    try {
      // Ler os bytes da imagem e codificar em Base64
      final bytes = await File(imagePath).readAsBytes();
      final base64Image = base64Encode(bytes);

      // Fazer a requisição POST para a API
      final response = await http.post(
        Uri.parse(apiUrl),
        headers: {'Content-Type': 'application/json'},
        body: jsonEncode({'image_data': base64Image}),
      );

      print(response.body);

      // Verificar se a resposta foi bem-sucedida
      if (response.statusCode == 200) {
        final responseData = jsonDecode(response.body);

        String firstLabel = 'Label não encontrado';

        // Verificar se a resposta contém a chave 'Detected custom labels' (Lista de labels)
        if (responseData.containsKey('Detected custom labels')) {
          final customLabels = responseData['Detected custom labels'];
          if (customLabels is List && customLabels.isNotEmpty) {
            firstLabel = customLabels[0]['Label'] ?? 'Label não encontrado';
          }
        }
        // Verificar se a resposta é um objeto único de label
        else if (responseData.containsKey('Label')) {
          firstLabel = responseData['Label'] ?? 'Label não encontrado';
        }

        return firstLabel; // Retorna o primeiro label encontrado
      } else {
        print("Falha ao enviar a imagem. Código: ${response.statusCode}");
        return 'Erro ao processar a imagem na API.';
      }
    } catch (e) {
      print("Erro ao enviar imagem para a API: $e");
      return 'Erro ao enviar imagem.';
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Row(
          children: [
            const CircleAvatar(
              child: Icon(Icons.person),
            ),
            const SizedBox(width: 8),
            Text('Chat com $destinatario'),
          ],
        ),
      ),
      body: Column(
        children: [
          Expanded(
            child: StreamBuilder<DatabaseEvent>(
              stream: databaseRef.orderByChild('horario').onValue,
              builder: (context, snapshot) {
                if (snapshot.connectionState == ConnectionState.waiting) {
                  return const Center(child: CircularProgressIndicator());
                }

                if (snapshot.hasError) {
                  return const Center(
                      child: Text('Erro ao carregar mensagens.'));
                }

                if (snapshot.hasData) {
                  final data =
                      snapshot.data!.snapshot.value as Map<dynamic, dynamic>? ??
                          {};

                  // Converter os dados para uma lista de mensagens
                  final messages = data.entries
                      .map((entry) {
                        final value = entry.value as Map<dynamic, dynamic>;
                        return MessageModel(
                          destinatario: value['destinatario'] as String? ?? '',
                          remetente: value['remetente'] as String? ?? '',
                          mensagem: value['mensagem'] as String? ?? '',
                          horario: value['horario'] as int? ?? 0,
                        );
                      })
                      .where((message) =>
                          (message.destinatario == destinatario &&
                              message.remetente == remetente.toString()) ||
                          (message.remetente == destinatario &&
                              message.destinatario == remetente.toString()))
                      .toList()
                    ..sort((a, b) => a.horario.compareTo(b.horario));

                  if (messages.isEmpty) {
                    return const Center(
                        child: Text('Nenhuma mensagem encontrada.'));
                  }

                  return ListView.builder(
                    itemCount: messages.length,
                    padding: const EdgeInsets.symmetric(horizontal: 8),
                    itemBuilder: (context, index) {
                      final message = messages[index];
                      final horario = DateTime.fromMillisecondsSinceEpoch(
                          message.horario * 1000);
                      final horarioFormatado =
                          DateFormat('HH:mm:ss').format(horario);

                      final isRemetente =
                          message.remetente == remetente.toString();

                      return Align(
                        alignment: isRemetente
                            ? Alignment.centerRight
                            : Alignment.centerLeft,
                        child: Container(
                          margin: const EdgeInsets.symmetric(
                              vertical: 4, horizontal: 8),
                          padding: const EdgeInsets.all(12),
                          decoration: BoxDecoration(
                            color: isRemetente
                                ? Colors.blue[100]
                                : Colors.grey[300],
                            borderRadius: BorderRadius.only(
                              topLeft: const Radius.circular(12),
                              topRight: const Radius.circular(12),
                              bottomLeft: isRemetente
                                  ? const Radius.circular(12)
                                  : Radius.zero,
                              bottomRight: isRemetente
                                  ? Radius.zero
                                  : const Radius.circular(12),
                            ),
                          ),
                          child: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              message.mensagem.isNotEmpty
                                  ? Text(
                                      message.mensagem,
                                      style: const TextStyle(fontSize: 16),
                                    )
                                  : Image.file(File(message.mensagem),
                                      width: 150, height: 150),
                              const SizedBox(height: 4),
                              Text(
                                horarioFormatado,
                                style: const TextStyle(
                                    fontSize: 12, color: Colors.black54),
                              ),
                            ],
                          ),
                        ),
                      );
                    },
                  );
                }

                return const Center(
                    child: Text('Nenhuma mensagem encontrada.'));
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              children: [
                IconButton(
                  icon: const Icon(Icons.camera_alt),
                  onPressed: _openCamera, // Abre a câmera ao pressionar
                ),
                Expanded(
                  child: TextField(
                    controller: messageController,
                    decoration: InputDecoration(
                      hintText: 'Digite sua mensagem...',
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                      contentPadding: const EdgeInsets.symmetric(
                          vertical: 10, horizontal: 16),
                    ),
                  ),
                ),
                const SizedBox(width: 8),
                FloatingActionButton(
                  onPressed: () {
                    sendMessage(messageController.text);
                  },
                  child: const Icon(Icons.send),
                  mini: true,
                ),
              ],
            ),
          ),
        ],
      ),
    );
  }

  void _cancelImage() {
    setState(() {
      _imageFile = null;
    });
    Navigator.pop(context);
  }
}
