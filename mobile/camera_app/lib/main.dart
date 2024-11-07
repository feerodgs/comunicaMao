import 'package:flutter/material.dart';
import 'package:permission_handler/permission_handler.dart';
import 'home_screen.dart';
import 'package:camera/camera.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Solicita permissão de câmera
  var status = await Permission.camera.status;
  if (!status.isGranted) {
    await Permission.camera.request();
  }

  final cameras = await availableCameras();
  runApp(MyApp(cameras: cameras));
}

class MyApp extends StatelessWidget {
  final List<CameraDescription> cameras;

  const MyApp({super.key, required this.cameras});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'ComunicaMao',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
          seedColor: Colors.teal,
          secondary: Colors.tealAccent,
        ),
        scaffoldBackgroundColor: Colors.blueGrey[50],
        textTheme: TextTheme(
          bodyLarge: TextStyle(color: Colors.black),
        ),
        useMaterial3: true,
      ),
      home: MyHomePage(title: 'ComunicaMao', cameras: cameras),
    );
  }
}
