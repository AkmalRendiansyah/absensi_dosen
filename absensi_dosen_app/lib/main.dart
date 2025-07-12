// lib/main.dart
import 'package:flutter/material.dart';
import 'package:maps_flutter/screens/splash_screen.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Maps Flutter',
      debugShowCheckedModeBanner: false,
      home: const SplashScreen(), // Ganti jadi SplashScreen
    );
  }
}
