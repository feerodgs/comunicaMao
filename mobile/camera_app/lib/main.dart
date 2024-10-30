import 'package:camera/camera.dart';
import 'package:flutter/material.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final cameras = await availableCameras();
  runApp(MyApp(cameras: cameras));
}

class MyApp extends StatelessWidget {
  final List<CameraDescription> cameras;

  const MyApp({super.key, required this.cameras});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Comunica Mão',
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(
            seedColor: const Color.fromARGB(255, 22, 227, 67)),
        useMaterial3: true,
      ),
      home: MyHomePage(title: 'Comunica mão', cameras: cameras),
    );
  }
}

class MyHomePage extends StatefulWidget {
  final String title;
  final List<CameraDescription> cameras;

  const MyHomePage({super.key, required this.title, required this.cameras});

  @override
  State<MyHomePage> createState() => _MyHomePageState();
}

class _MyHomePageState extends State<MyHomePage> {
  CameraController? _cameraController;
  bool _isCameraOpen = false;

  void _toggleCamera() async {
    if (_isCameraOpen) {
      // Fecha a câmera
      await _cameraController?.dispose();
      setState(() {
        _cameraController = null;
        _isCameraOpen = false;
      });
    } else {
      // Abre a câmera
      if (widget.cameras.isNotEmpty) {
        _cameraController = CameraController(
          widget.cameras[0],
          ResolutionPreset.high,
        );

        await _cameraController!.initialize();
        setState(() {
          _isCameraOpen = true;
        });
      } else {
        print("Nenhuma câmera disponível.");
      }
    }
  }

  @override
  void dispose() {
    _cameraController?.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        backgroundColor: Theme.of(context).colorScheme.inversePrimary,
        title: Text(widget.title),
      ),
      body: Center(
        child: _isCameraOpen &&
                _cameraController != null &&
                _cameraController!.value.isInitialized
            ? CameraPreview(_cameraController!)
            : const Text('Pressione o botão para abrir a câmera'),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: _toggleCamera,
        tooltip: _isCameraOpen ? 'Close Camera' : 'Open Camera',
        child: Icon(_isCameraOpen ? Icons.close : Icons.camera),
      ),
    );
  }
}
