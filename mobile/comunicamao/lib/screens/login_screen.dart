import 'package:flutter/material.dart';

class LoginScreen extends StatelessWidget {
  const LoginScreen({super.key});

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
