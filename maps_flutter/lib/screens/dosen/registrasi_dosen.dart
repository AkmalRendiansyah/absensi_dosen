import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import 'package:maps_flutter/screens/dosen/login.dart';

class RegisterDosenPage extends StatefulWidget {
  const RegisterDosenPage({super.key});

  @override
  State<RegisterDosenPage> createState() => _RegisterDosenPageState();
}

class _RegisterDosenPageState extends State<RegisterDosenPage> {
  final _formKey = GlobalKey<FormState>();

  final emailController = TextEditingController();
  final namaController = TextEditingController();
  final nidnController = TextEditingController();
  final passwordController = TextEditingController();
  final passwordAgainController = TextEditingController();

  String? selectedJurusan;
  String? selectedProdi;

  bool isLoading = false;
  bool _obscurePassword = true;
  bool _obscurePassword2 = true;

  final Map<String, List<String>> jurusanProdiMap = {
    'Teknik Informatika': ['TRPL', 'SI', 'MI'],
    'Administrasi Niaga': ['Administrasi Bisnis', 'Manajemen Bisnis'],
    'Teknik Kimia': ['Teknik Kimia', 'Rekayasa Proses Pangan'],
  };

  Future<void> registerDosen() async {
    if (!_formKey.currentState!.validate()) return;
    setState(() => isLoading = true);

    try {
      final response = await http.post(
        Uri.parse('http://192.168.1.5:8080/register_dosen.php'),
        headers: {"Content-Type": "application/json"},
        body: jsonEncode({
          "email": emailController.text.trim(),
          "password": passwordController.text,
          "password2": passwordAgainController.text,
          "nama": namaController.text.trim(),
          "nidn": nidnController.text.trim(),
          "jurusan": selectedJurusan ?? "",
          "prodi": selectedProdi ?? "",
        }),
      );

      final data = jsonDecode(response.body);
      setState(() => isLoading = false);

      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(
        content: Text(data['message']),
        backgroundColor: data['success'] ? Colors.green : Colors.red,
      ));

      // ignore: use_build_context_synchronously
      if (data['success']) {
        Navigator.pushReplacement(
          // ignore: use_build_context_synchronously
          context,
          MaterialPageRoute(builder: (context) => const LoginDosenPage()),
        );
      }
    } catch (e) {
      setState(() => isLoading = false);
      // ignore: use_build_context_synchronously
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(
        content: Text('Connection error occurred.'),
        backgroundColor: Colors.red,
      ));
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
                (value == null || value.isEmpty) ? "$label is required" : null,
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
                      "Register",
                      style: TextStyle(
                          fontSize: 20,
                          fontWeight: FontWeight.bold,
                          color: Colors.blue),
                    ),
                    const SizedBox(height: 16),
                    buildInputField(
                      controller: emailController,
                      label: "Email",
                      icon: Icons.email,
                      inputType: TextInputType.emailAddress,
                      validator: (value) {
                        if (value == null || value.isEmpty)
                          return "Email is required";
                        if (!value.contains('@')) return "Invalid email format";
                        return null;
                      },
                    ),
                    buildInputField(
                      controller: namaController,
                      label: "Full Name",
                      icon: Icons.person,
                      validator: (value) {
                        if (value == null || value.isEmpty)
                          return "Full name is required";
                        if (RegExp(r'[0-9]').hasMatch(value))
                          return "Name cannot contain numbers";
                        if (value.length > 40) return "Name max 40 characters";
                        return null;
                      },
                    ),
                    buildInputField(
                      controller: nidnController,
                      label: "NIDN",
                      icon: Icons.badge,
                      inputType: TextInputType.number,
                      validator: (value) {
                        if (value == null || value.isEmpty)
                          return "NIDN is required";
                        if (value.length > 40) return "NIDN max 40 characters";
                        return null;
                      },
                    ),
                    const SizedBox(height: 10),
                    DropdownButtonFormField<String>(
                      value: selectedJurusan,
                      decoration: InputDecoration(
                        labelText: "Department",
                        prefixIcon:
                            const Icon(Icons.school, color: Colors.blue),
                        filled: true,
                        fillColor: Colors.grey.shade100,
                        border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12)),
                      ),
                      items: jurusanProdiMap.keys.map((jurusan) {
                        return DropdownMenuItem(
                            value: jurusan, child: Text(jurusan));
                      }).toList(),
                      onChanged: (value) {
                        setState(() {
                          selectedJurusan = value;
                          selectedProdi = null;
                        });
                      },
                      validator: (value) =>
                          value == null ? 'Department is required' : null,
                    ),
                    const SizedBox(height: 10),
                    DropdownButtonFormField<String>(
                      value: selectedProdi,
                      decoration: InputDecoration(
                        labelText: "Study Program",
                        prefixIcon: const Icon(Icons.list, color: Colors.blue),
                        filled: true,
                        fillColor: Colors.grey.shade100,
                        border: OutlineInputBorder(
                            borderRadius: BorderRadius.circular(12)),
                      ),
                      items:
                          (jurusanProdiMap[selectedJurusan] ?? []).map((prodi) {
                        return DropdownMenuItem(
                            value: prodi, child: Text(prodi));
                      }).toList(),
                      onChanged: (value) =>
                          setState(() => selectedProdi = value),
                      validator: (value) =>
                          value == null ? 'Study program is required' : null,
                    ),
                    buildInputField(
                      controller: passwordController,
                      label: "Password",
                      icon: Icons.lock,
                      obscure: _obscurePassword,
                      suffixIcon: IconButton(
                        icon: Icon(_obscurePassword
                            ? Icons.visibility_off
                            : Icons.visibility),
                        onPressed: () => setState(
                            () => _obscurePassword = !_obscurePassword),
                      ),
                      validator: (value) {
                        if (value == null || value.isEmpty)
                          return "Password is required";
                        if (value.length < 6)
                          return "Password must be at least 6 characters";
                        return null;
                      },
                    ),
                    buildInputField(
                      controller: passwordAgainController,
                      label: "Repeat Password",
                      icon: Icons.lock_outline,
                      obscure: _obscurePassword2,
                      suffixIcon: IconButton(
                        icon: Icon(_obscurePassword2
                            ? Icons.visibility_off
                            : Icons.visibility),
                        onPressed: () => setState(
                            () => _obscurePassword2 = !_obscurePassword2),
                      ),
                      validator: (value) {
                        if (value != passwordController.text)
                          return "Passwords do not match";
                        return null;
                      },
                    ),
                    const SizedBox(height: 20),
                    SizedBox(
                      width: double.infinity,
                      child: ElevatedButton.icon(
                        onPressed: isLoading ? null : registerDosen,
                        icon: const Icon(Icons.app_registration),
                        label: isLoading
                            ? const SizedBox(
                                height: 20,
                                width: 20,
                                child: CircularProgressIndicator(
                                    color: Colors.white, strokeWidth: 2),
                              )
                            : const Text("Register",
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
                              builder: (_) => const LoginDosenPage()),
                        );
                      },
                      icon: const Icon(Icons.login),
                      label: const Text("Already have an account? Login"),
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
