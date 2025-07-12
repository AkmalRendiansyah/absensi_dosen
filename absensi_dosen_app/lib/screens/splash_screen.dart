import 'package:flutter/material.dart';
import 'package:shared_preferences/shared_preferences.dart';
import 'package:maps_flutter/screens/dosen/dashboard_dosen.dart';
import 'package:maps_flutter/screens/dosen/login.dart';

class SplashScreen extends StatefulWidget {
  const SplashScreen({super.key});

  @override
  State<SplashScreen> createState() => _SplashScreenState();
}

class _SplashScreenState extends State<SplashScreen> {
  @override
  void initState() {
    super.initState();
    _checkLogin();
  }

  Future<void> _checkLogin() async {
    SharedPreferences prefs = await SharedPreferences.getInstance();
    bool isLoggedIn = prefs.getBool('is_logged_in') ?? false;
    if (isLoggedIn) {
      int dosenId = prefs.getInt('dosen_id') ?? 0;
      String namaLengkap = prefs.getString('nama_lengkap') ?? '';
      String nidn = prefs.getString('nidn') ?? '';
      String jurusan = prefs.getString('jurusan') ?? '';
      String prodi = prefs.getString('prodi') ?? '';
      if (!mounted) return;
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(
          builder: (_) => DosenDashboardPage(
            dosenId: dosenId,
            namaLengkap: namaLengkap,
            nidn: nidn,
            jurusan: jurusan,
            prodi: prodi,
          ),
        ),
      );
    } else {
      if (!mounted) return;
      Navigator.pushReplacement(
        context,
        MaterialPageRoute(builder: (_) => const LoginDosenPage()),
      );
    }
  }

  @override
  Widget build(BuildContext context) {
    return const Scaffold(
      body: Center(child: CircularProgressIndicator()),
    );
  }
}
