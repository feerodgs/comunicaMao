import 'package:flutter/material.dart';
import 'package:flutter_test/flutter_test.dart';
import 'package:camera_app/main.dart';

void main() {
  testWidgets('Camera button exists and can be tapped',
      (WidgetTester tester) async {
    await tester.pumpWidget(MyApp(cameras: []));

    expect(find.byIcon(Icons.camera), findsOneWidget);

    await tester.tap(find.byIcon(Icons.camera));
    await tester.pump();
    expect(find.text('Pressione o botão para abrir a câmera'), findsOneWidget);
  });
}
