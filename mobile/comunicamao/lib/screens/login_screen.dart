import 'package:flutter/material.dart';
import 'package:firebase_database/firebase_database.dart';

class LoginScreen extends StatefulWidget {
  const LoginScreen({super.key});

  @override
  State<LoginScreen> createState() => _LoginScreenState();
}

class _LoginScreenState extends State<LoginScreen> {
  final TextEditingController messageController = TextEditingController();
  String? destinatario;

  late DatabaseReference databaseRef;

  @override
  void didChangeDependencies() {
    super.didChangeDependencies();

    // Obter o destinatário a partir dos argumentos da rota
    destinatario = ModalRoute.of(context)?.settings.arguments as String?;

    if (destinatario != null) {
      databaseRef = FirebaseDatabase.instance.ref('usuarios');
    }
  }

  @override
  Widget build(BuildContext context) {
    final TextEditingController destinatarioController =
        TextEditingController();

    return Scaffold(
      appBar: AppBar(title: const Text('Login')),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            TextField(
              controller: destinatarioController,
              decoration: const InputDecoration(
                labelText: 'Código do destinatário',
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 16),
            ElevatedButton(
              onPressed: () {
                final destinatario = destinatarioController.text;
                if (destinatario.isNotEmpty) {
                  Navigator.pushNamed(
                    context,
                    '/chat',
                    arguments: destinatario,
                  );
                }
              },
              child: const Text('Entrar no Chat'),
            ),
          ],
        ),
      ),
    );
  }
}
