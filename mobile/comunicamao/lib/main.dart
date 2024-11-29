import 'package:flutter/material.dart';
import 'package:firebase_core/firebase_core.dart';
import 'package:camera/camera.dart'; // Import necessário para a câmera
import 'screens/chat_screen.dart';
import 'screens/login_screen.dart';

List<CameraDescription> cameras = []; // Lista de câmeras

Future<void> main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Firebase.initializeApp();

  // Inicializa as câmeras disponíveis
  cameras = await availableCameras();

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Comunicamao',
      theme: ThemeData(primarySwatch: Colors.blue),
      initialRoute: '/',
      routes: {
        '/': (context) => const LoginScreen(),
        '/chat': (context) =>
            ChatScreen(cameras: cameras), // Passa as câmeras para o ChatScreen
      },
    );
  }
}
