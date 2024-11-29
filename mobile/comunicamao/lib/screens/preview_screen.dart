import 'dart:io';
import 'package:flutter/material.dart';

class PreviewScreen extends StatelessWidget {
  final String imagePath;
  final VoidCallback onCancel;
  final ValueChanged<String> onSend;

  const PreviewScreen({
    super.key,
    required this.imagePath,
    required this.onSend,
    required this.onCancel,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Prévia da Imagem'),
        actions: [
          IconButton(
            icon: const Icon(Icons.send),
            onPressed: () {
              onSend(imagePath);
              Navigator.pop(context);
            },
          ),
        ],
      ),
      body: Column(
        children: [
          Expanded(child: Image.file(File(imagePath))),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                TextButton.icon(
                  icon: const Icon(Icons.cancel),
                  label: const Text('Cancelar'),
                  onPressed: onCancel,
                ),
                ElevatedButton.icon(
                  icon: const Icon(Icons.send),
                  label: const Text('Enviar'),
                  onPressed: () {
                    onSend(imagePath);
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
