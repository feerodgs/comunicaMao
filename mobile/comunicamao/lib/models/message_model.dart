class MessageModel {
  final String destinatario;
  final String remetente;
  final String mensagem;
  final int horario;

  MessageModel({
    required this.destinatario,
    required this.remetente,
    required this.mensagem,
    required this.horario,
  });
}
