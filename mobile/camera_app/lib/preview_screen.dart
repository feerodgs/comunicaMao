import 'dart:io';
import 'package:flutter/material.dart';

class PreviewScreen extends StatelessWidget {
  final String imagePath;
  final Function(String)
      onSend; // Função que recebe uma String (caminho da imagem)
  final VoidCallback onCancel;

  const PreviewScreen({
    Key? key,
    required this.imagePath,
    required this.onSend,
    required this.onCancel,
  }) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text("Visualizar Foto"),
        backgroundColor: Colors.teal,
      ),
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          // Exibe a imagem que foi tirada
          Image.file(
            File(imagePath),
            width: 300,
            height: 300,
            fit: BoxFit.cover,
          ),
          SizedBox(height: 20),
          // Botões de enviar ou cancelar
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceEvenly,
            children: [
              ElevatedButton(
                onPressed: () => onSend(
                    imagePath), // Passa o caminho da imagem para a função onSend
                child: Text("Enviar para o Chat"),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.teal,
                  padding: EdgeInsets.symmetric(vertical: 12, horizontal: 25),
                  textStyle: TextStyle(fontSize: 16),
                ),
              ),
              ElevatedButton(
                onPressed: onCancel, // Cancela a ação e retorna à tela anterior
                child: Text("Cancelar"),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red,
                  padding: EdgeInsets.symmetric(vertical: 12, horizontal: 25),
                  textStyle: TextStyle(fontSize: 16),
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}
