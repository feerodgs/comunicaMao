import 'package:firebase_database/firebase_database.dart';

class FirebaseService {
  final DatabaseReference databaseRef =
      FirebaseDatabase.instance.ref('conversas');

  Future<void> sendMessage({
    required String destinatario,
    required String remetente,
    required String mensagem,
  }) async {
    final horario = DateTime.now().toIso8601String();
    await databaseRef.push().set({
      'destinatario': destinatario,
      'remetente': remetente,
      'mensagem': mensagem,
      'horario': horario,
    });
  }

  Stream<Map<dynamic, dynamic>> getMessages() {
    return databaseRef.onValue.map((event) {
      return event.snapshot.value as Map<dynamic, dynamic>? ?? {};
    });
  }
}
