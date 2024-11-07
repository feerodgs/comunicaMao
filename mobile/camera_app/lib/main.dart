import 'package:camera/camera.dart';
import 'package:flutter/material.dart';
import 'package:gallery_saver/gallery_saver.dart';
import 'dart:io';

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
      title: 'ComunicaMao',
      theme: ThemeData(
        primarySwatch: Colors.teal, // Cor primária mais moderna
        scaffoldBackgroundColor: Colors.blueGrey[50], // Cor de fundo suave
        textTheme: TextTheme(
          bodyLarge: TextStyle(color: Colors.black87), // Texto mais escuro
        ),
        useMaterial3: true,
      ),
      home: MyHomePage(title: 'ComunicaMao', cameras: cameras),
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
  XFile? _imageFile;
  TextEditingController _controller = TextEditingController();
  List<Message> _messages = [];

  void _toggleCamera() async {
    if (_isCameraOpen) {
      await _cameraController?.dispose();
      setState(() {
        _cameraController = null;
        _isCameraOpen = false;
      });
    } else {
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

  Future<void> _takePicture() async {
    if (_cameraController != null && _cameraController!.value.isInitialized) {
      try {
        final image = await _cameraController!.takePicture();
        await GallerySaver.saveImage(image.path, albumName: 'ComunicaMao');

        setState(() {
          _imageFile = image;
        });

        Navigator.push(
          context,
          MaterialPageRoute(
            builder: (context) => PreviewScreen(
              imagePath: image.path,
              onSend: _sendImageMessage,
              onCancel: _cancelImage,
            ),
          ),
        );
      } catch (e) {
        print("Erro ao tirar foto: $e");
      }
    }
  }

  void _sendMessage(String text) {
    setState(() {
      _messages.add(Message(text: text));
      _controller.clear();
    });
  }

  void _sendImageMessage(String imagePath) {
    setState(() {
      _messages.add(Message(imagePath: imagePath, isImage: true));
    });
    Navigator.pop(context); // Volta para o chat
  }

  void _cancelImage() {
    setState(() {
      _imageFile = null;
    });
    Navigator.pop(context); // Volta para o chat
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
                final message = _messages[index];
                return Align(
                  alignment:
                      message.isImage ? Alignment.center : Alignment.centerLeft,
                  child: Padding(
                    padding: const EdgeInsets.all(8.0),
                    child: message.isImage
                        ? ClipRRect(
                            borderRadius: BorderRadius.circular(10),
                            child: Image.file(
                              File(message.imagePath!),
                              width: 200,
                              height: 200,
                              fit: BoxFit.cover,
                            ),
                          )
                        : Container(
                            padding: const EdgeInsets.symmetric(
                                vertical: 10, horizontal: 15),
                            decoration: BoxDecoration(
                              color: Colors.teal[100],
                              borderRadius: BorderRadius.circular(20),
                            ),
                            child: Text(
                              message.text!,
                              style: TextStyle(color: Colors.black87),
                            ),
                          ),
                  ),
                );
              },
            ),
          ),
          Padding(
            padding: const EdgeInsets.all(8.0),
            child: Row(
              children: [
                IconButton(
                  icon: Icon(Icons.camera_alt),
                  onPressed: _toggleCamera,
                ),
                Expanded(
                  child: TextField(
                    controller: _controller,
                    decoration: InputDecoration(
                      hintText: "Digite uma mensagem",
                      border: OutlineInputBorder(
                        borderRadius: BorderRadius.circular(30),
                      ),
                      contentPadding: EdgeInsets.all(10),
                    ),
                  ),
                ),
                IconButton(
                  icon: Icon(Icons.send),
                  onPressed: () {
                    if (_controller.text.isNotEmpty) {
                      _sendMessage(_controller.text);
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

class CameraScreen extends StatefulWidget {
  final List<CameraDescription> cameras;
  final Function onPictureTaken;

  const CameraScreen(
      {super.key, required this.cameras, required this.onPictureTaken});

  @override
  State<CameraScreen> createState() => _CameraScreenState();
}

class _CameraScreenState extends State<CameraScreen> {
  CameraController? _cameraController;
  bool _isCameraOpen = false;

  @override
  void initState() {
    super.initState();
    _initializeCamera();
  }

  void _initializeCamera() async {
    if (widget.cameras.isNotEmpty) {
      _cameraController = CameraController(
        widget.cameras[0],
        ResolutionPreset.high,
      );
      await _cameraController?.initialize();
      setState(() {
        _isCameraOpen = true;
      });
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
        title: const Text('Captura de Imagem'),
      ),
      body: Center(
        child: _isCameraOpen
            ? CameraPreview(_cameraController!)
            : const CircularProgressIndicator(),
      ),
      floatingActionButton: FloatingActionButton(
        onPressed: () async {
          await widget.onPictureTaken();
        },
        child: const Icon(Icons.camera),
      ),
    );
  }
}

class PreviewScreen extends StatelessWidget {
  final String imagePath;
  final Function(String) onSend;
  final VoidCallback onCancel;

  const PreviewScreen({
    super.key,
    required this.imagePath,
    required this.onSend,
    required this.onCancel,
  });

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Visualizar Foto")),
      body: Column(
        mainAxisAlignment: MainAxisAlignment.center,
        children: [
          Image.file(File(imagePath),
              width: 300, height: 300, fit: BoxFit.cover),
          Row(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              ElevatedButton(
                onPressed: () => onSend(imagePath),
                child: Text("Enviar para o Chat"),
              ),
              SizedBox(width: 20),
              ElevatedButton(
                onPressed: onCancel,
                child: Text("Cancelar"),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.red, // Ajuste para cancelar
                ),
              ),
            ],
          ),
        ],
      ),
    );
  }
}

class Message {
  final String? text;
  final String? imagePath;
  final bool isImage;

  Message({this.text, this.imagePath, this.isImage = false});
}
