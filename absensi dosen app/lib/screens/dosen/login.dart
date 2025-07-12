import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';
import 'package:shared_preferences/shared_preferences.dart';

import 'package:maps_flutter/screens/dosen/dashboard_dosen.dart';
import 'package:maps_flutter/screens/dosen/registrasi_dosen.dart';

class LoginDosenPage extends StatefulWidget {
  const LoginDosenPage({super.key});

  @override
  State<LoginDosenPage> createState() => _LoginDosenPageState();
}

class _LoginDosenPageState extends State<LoginDosenPage> {
  final TextEditingController _identifierController = TextEditingController();
  final TextEditingController _passwordController = TextEditingController();
  final GlobalKey<FormState> _formKey = GlobalKey<FormState>();
  bool isLoading = false;
  bool isPasswordVisible = false;

  @override
  void dispose() {
    _identifierController.dispose();
    _passwordController.dispose();
    super.dispose();
  }

  bool _isEmailValid(String email) {
    final emailRegEx = RegExp(r'^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$');
    return emailRegEx.hasMatch(email);
  }

  Future<void> loginDosen() async {
    final identifier = _identifierController.text.trim();
    final password = _passwordController.text.trim();

    setState(() => isLoading = true);

    try {
      final response = await http.post(
        Uri.parse("http://192.168.1.5:8080/login_dosen.php"),
        headers: {"Content-Type": "application/json"},
        body: json.encode({
          "action": "login",
          "email": identifier,
          "password": password,
        }),
      );

      setState(() => isLoading = false);

      if (response.statusCode == 200) {
        final data = json.decode(response.body);

        if (data['success'] == true) {
          SharedPreferences prefs = await SharedPreferences.getInstance();
          await prefs.setBool('is_logged_in', true); // Tambahkan ini
          await prefs.setInt('dosen_id', data['dosen_id']);
          await prefs.setString('role', data['role']);
          await prefs.setString('nama_lengkap', data['nama_lengkap']);
          await prefs.setString('nidn', data['nidn']);
          await prefs.setString('jurusan', data['jurusan']);
          await prefs.setString('prodi', data['prodi']);

          _identifierController.clear();
          _passwordController.clear();

          if (!mounted) return;

          Navigator.pushReplacement(
            context,
            MaterialPageRoute(
              builder: (_) => DosenDashboardPage(
                dosenId: data['dosen_id'],
                namaLengkap: data['nama_lengkap'],
                nidn: data['nidn'],
                jurusan: data['jurusan'],
                prodi: data['prodi'],
              ),
            ),
          );
        } else {
          if (!mounted) return;
          ScaffoldMessenger.of(context).showSnackBar(
            SnackBar(content: Text(data['message'] ?? "Login failed")),
          );
        }
      } else {
        if (!mounted) return;
        ScaffoldMessenger.of(context).showSnackBar(
          const SnackBar(content: Text("Server error occurred.")),
        );
      }
    } catch (e) {
      setState(() => isLoading = false);
      if (!mounted) return;
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("An error occurred during login.")),
      );
    }
  }

  Widget buildInputField({
    required TextEditingController controller,
    required String label,
    required IconData icon,
    bool obscure = false,
    TextInputType inputType = TextInputType.text,
    String? Function(String?)? validator,
    Widget? suffixIcon,
  }) {
    return Padding(
      padding: const EdgeInsets.symmetric(vertical: 8),
      child: TextFormField(
        controller: controller,
        obscureText: obscure,
        keyboardType: inputType,
        validator: validator ??
            (value) =>
                (value == null || value.isEmpty) ? "$label wajib diisi" : null,
        decoration: InputDecoration(
          prefixIcon: Icon(icon, color: Colors.blue),
          suffixIcon: suffixIcon,
          labelText: label,
          filled: true,
          fillColor: Colors.grey.shade100,
          border: OutlineInputBorder(borderRadius: BorderRadius.circular(12)),
          focusedBorder: OutlineInputBorder(
            borderRadius: BorderRadius.circular(12),
            borderSide: const BorderSide(color: Colors.blue, width: 2),
          ),
        ),
      ),
    );
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: Colors.blue.shade50,
      body: Center(
        child: SingleChildScrollView(
          padding: const EdgeInsets.all(24),
          child: Card(
            shape:
                RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
            elevation: 6,
            child: Padding(
              padding: const EdgeInsets.all(20.0),
              child: Form(
                key: _formKey,
                child: Column(
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    const Text(
                      "Login",
                      style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.blue),
                    ),
                    const SizedBox(height: 16),
                    buildInputField(
                      controller: _identifierController,
                      label: "Email or NIDN",
                      icon: Icons.email,
                      inputType: TextInputType.text,
                      validator: (value) {
                        if (value == null || value.isEmpty) {
                          return 'Email or NIDN is required';
                        }
                        if (!RegExp(r'^\d+$').hasMatch(value) &&
                            !_isEmailValid(value)) {
                          return 'Enter a valid email or NIDN';
                        }
                        return null;
                      },
                    ),
                    buildInputField(
                      controller: _passwordController,
                      label: "Password",
                      icon: Icons.lock,
                      obscure: !isPasswordVisible,
                      suffixIcon: IconButton(
                        icon: Icon(
                          isPasswordVisible
                              ? Icons.visibility
                              : Icons.visibility_off,
                        ),
                        onPressed: () {
                          setState(
                              () => isPasswordVisible = !isPasswordVisible);
                        },
                      ),
                      validator: (value) => value == null || value.isEmpty
                          ? 'Password is required'
                          : null,
                    ),
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: isLoading
                            ? null
                            : () {
                                if (_formKey.currentState!.validate()) {
                                  loginDosen();
                                }
                              },
                        icon: const Icon(Icons.login),
                        label: isLoading
                            ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(
                                    color: Colors.white, strokeWidth: 2),
                              )
                            : const Text("Login",
                                style: TextStyle(fontSize: 16)),
                        style: ElevatedButton.styleFrom(
                          padding: const EdgeInsets.symmetric(vertical: 14),
                          backgroundColor: Colors.blue,
                          shape: RoundedRectangleBorder(
                              borderRadius: BorderRadius.circular(12)),
                        ),
                      ),
                    ),
                    const SizedBox(height: 16),
                    TextButton.icon(
                      onPressed: () {
                        Navigator.push(
                          context,
                          MaterialPageRoute(
                              builder: (_) => const RegisterDosenPage()),
                        );
                      },
                      icon: const Icon(Icons.app_registration),
                      label: const Text("Don't have an account? Register"),
                    ),
                  ],
                ),
              ),
            ),
          ),
        ),
      ),
    );
  }
}
