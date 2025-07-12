import 'dart:async';
import 'dart:convert';
import 'dart:math';
import 'package:flutter/material.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';
import 'package:location/location.dart';
import 'package:http/http.dart' as http;
import 'package:maps_flutter/screens/dosen/ambil_absen.dart';

class DosenDashboardPage extends StatefulWidget {
  final int dosenId;
  final String namaLengkap;
  final String nidn;
  final String jurusan;
  final String prodi;

  const DosenDashboardPage({
    super.key,
    required this.dosenId,
    required this.namaLengkap,
    required this.nidn,
    required this.jurusan,
    required this.prodi,
  });

  @override
  State<DosenDashboardPage> createState() => _DosenDashboardPageState();
}

class _DosenDashboardPageState extends State<DosenDashboardPage> {
  bool _isLoading = false;
  List<dynamic> jadwalList = [];
  int _selectedIndex = 0;

  @override
  void initState() {
    super.initState();
    fetchJadwalDosen();
  }

  Future<void> fetchJadwalDosen() async {
    setState(() => _isLoading = true);
    final url =
        'http://192.168.1.5:8080/get_jadwal_aktif_dosen.php?dosen_id=${widget.dosenId}';
    try {
      final response = await http.get(Uri.parse(url));
      final data = jsonDecode(response.body);
      if (data['success']) {
        setState(() {
          jadwalList = List.from(data['jadwal']);
        });
      } else {
        _showSnackbar("Gagal memuat jadwal");
      }
    } catch (e) {
      _showSnackbar("Terjadi kesalahan: $e");
    } finally {
      setState(() => _isLoading = false);
    }
  }

  void _showSnackbar(String message) {
    if (mounted) {
      ScaffoldMessenger.of(context)
          .showSnackBar(SnackBar(content: Text(message)));
    }
  }

  Widget _buildDashboardContent() {
    return Column(
      crossAxisAlignment: CrossAxisAlignment.start,
      children: [
        const Text("Jadwal Aktif",
            style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
        const SizedBox(height: 12),
        _isLoading
            ? const Center(child: CircularProgressIndicator())
            : jadwalList.isEmpty
                ? const Center(child: Text("Tidak ada jadwal aktif saat ini."))
                : ListView.builder(
                    shrinkWrap: true,
                    physics: const NeverScrollableScrollPhysics(),
                    itemCount: jadwalList.length,
                    itemBuilder: (context, index) {
                      final jadwal = jadwalList[index];
                      return Card(
                        margin: const EdgeInsets.symmetric(vertical: 8),
                        child: ListTile(
                          subtitle: Column(
                            crossAxisAlignment: CrossAxisAlignment.start,
                            children: [
                              Text("Tanggal: ${jadwal['tanggal']}"),
                              Text("Jam: ${jadwal['jam_mulai']} - ${jadwal['batas_absen']}"),
                              Text("Lokasi: (${jadwal['latitude']}, ${jadwal['longitude']})"),
                              Text("Radius: ${jadwal['radius']} meter"),
                              Row(
                                children: [
                                  Container(
                                    margin: const EdgeInsets.only(top: 4),
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 8,
                                      vertical: 4,
                                    ),
                                    decoration: BoxDecoration(
                                      color: Colors.green.shade100,
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      "Status: ${jadwal['status']}",
                                      style: TextStyle(
                                        color: Colors.green.shade700,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                  const SizedBox(width: 8),
                                  Container(
                                    margin: const EdgeInsets.only(top: 4),
                                    padding: const EdgeInsets.symmetric(
                                      horizontal: 8,
                                      vertical: 4,
                                    ),
                                    decoration: BoxDecoration(
                                      color: jadwal['sudah_absen'] == true
                                          ? Colors.blue.shade100
                                          : Colors.orange.shade100,
                                      borderRadius: BorderRadius.circular(4),
                                    ),
                                    child: Text(
                                      jadwal['sudah_absen'] == true
                                          ? "Sudah Absen: ${jadwal['waktu_absen']}"
                                          : "Belum Absen",
                                      style: TextStyle(
                                        color: jadwal['sudah_absen'] == true
                                            ? Colors.blue.shade700
                                            : Colors.orange.shade700,
                                        fontWeight: FontWeight.bold,
                                      ),
                                    ),
                                  ),
                                ],
                              ),
                            ],
                          ),
                          onTap: jadwal['sudah_absen'] == true
                              ? null
                              : () {
                                  Navigator.push(
                                    context,
                                    MaterialPageRoute(
                                      builder: (_) => AmbilAbsenPage(
                                        jadwalId: jadwal['id'],
                                      ),
                                    ),
                                  ).then((_) => fetchJadwalDosen()); // Refresh after return
                                },
                        ),
                      );
                    },
                  ),
      ],
    );
  }

  Widget _buildProfileContent() {
    return Center(
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Card(
          shape:
              RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
          child: Padding(
            padding: const EdgeInsets.all(20.0),
            child: Column(
              mainAxisSize: MainAxisSize.min,
              children: [
                Text(widget.namaLengkap,
                    style: const TextStyle(
                        fontSize: 22, fontWeight: FontWeight.bold)),
                const SizedBox(height: 8),
                Text("NIDN: ${widget.nidn}"),
                Text("Jurusan: ${widget.jurusan}"),
                Text("Prodi: ${widget.prodi}"),
              ],
            ),
          ),
        ),
      ),
    );
  }

  void _onTabTapped(int index) {
    setState(() {
      _selectedIndex = index;
    });
  }

  @override
  Widget build(BuildContext context) {
    final List<Widget> _pages = [
      _buildDashboardContent(),
      _buildProfileContent(),
    ];

    return Scaffold(
      backgroundColor: const Color(0xFFB4161B),
      body: SafeArea(
        child: Column(
          children: [
            Container(
              padding: const EdgeInsets.all(16),
              color: const Color(0xFFB4161B),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  const Text('Hallo',
                      style: TextStyle(color: Colors.white, fontSize: 18)),
                  Text(widget.namaLengkap,
                      style: const TextStyle(
                          color: Colors.white,
                          fontWeight: FontWeight.bold,
                          fontSize: 22)),
                  const SizedBox(height: 4),
                  Text("NIDN: ${widget.nidn}",
                      style: const TextStyle(color: Colors.white70)),
                ],
              ),
            ),
            Expanded(
              child: Container(
                padding: const EdgeInsets.all(16),
                width: double.infinity,
                decoration: const BoxDecoration(
                  color: Colors.white,
                  borderRadius: BorderRadius.only(
                      topLeft: Radius.circular(32),
                      topRight: Radius.circular(32)),
                ),
                child: SingleChildScrollView(
                  child: _pages[_selectedIndex],
                ),
              ),
            ),
          ],
        ),
      ),
      bottomNavigationBar: BottomAppBar(
        shape: const CircularNotchedRectangle(),
        notchMargin: 8,
        color: const Color(0xFFB4161B),
        child: SizedBox(
          height: 60,
          child: Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              IconButton(
                icon: Icon(Icons.dashboard,
                    color: _selectedIndex == 0 ? Colors.white : Colors.white54),
                onPressed: () => _onTabTapped(0),
              ),
              IconButton(
                icon: Icon(Icons.person,
                    color: _selectedIndex == 1 ? Colors.white : Colors.white54),
                onPressed: () => _onTabTapped(1),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
