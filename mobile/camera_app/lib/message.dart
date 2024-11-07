class Message {
  final String? text;
  final String? imagePath;
  final bool isImage;
  final bool isSentByMe;

  Message({
    this.text,
    this.imagePath,
    this.isImage = false,
    required this.isSentByMe,
  });
}
