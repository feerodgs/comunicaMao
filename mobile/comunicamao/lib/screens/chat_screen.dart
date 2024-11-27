import 'package:flutter/material.dart';
import 'package:firebase_database/firebase_database.dart';
import '../models/message_model.dart';
import 'package:intl/intl.dart';

class ChatScreen extends StatefulWidget {
  const ChatScreen({super.key});

  @override
  State<ChatScreen> createState() => _ChatScreenState();
}

class _ChatScreenState extends State<ChatScreen> {
  final TextEditingController messageController = TextEditingController();
  final int remetente = 2; // ID do remetente (ajuste conforme necessário)
  String? destinatario;

  late DatabaseReference databaseRef;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    // Obter o destinatário a partir dos argumentos da rota
    destinatario = ModalRoute.of(context)?.settings.arguments as String?;

    if (destinatario != null) {
      // Inicializar referência do nó `conversas`
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
                      child: Text('Nenhuma mensagem encontrada.'),
                    );
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
                              Text(
                                message.mensagem,
                                style: const TextStyle(fontSize: 16),
                              ),
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
                Expanded(
                  child: TextField(
                    controller: messageController,
                    decoration: InputDecoration(
                      hintText: 'Digite sua mensagem...',
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(20),
                      ),
                      contentPadding: const EdgeInsets.symmetric(
                        vertical: 10,
                        horizontal: 16,
                      ),
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
}
