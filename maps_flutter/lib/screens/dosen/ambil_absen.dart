import 'dart:convert';
import 'dart:math';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'package:location/location.dart';
import 'package:google_maps_flutter/google_maps_flutter.dart';

class AmbilAbsenPage extends StatefulWidget {
  final int jadwalId;

  const AmbilAbsenPage({
    super.key,
    required this.jadwalId,
  });

  @override
  State<AmbilAbsenPage> createState() => _AmbilAbsenPageState();
}

class _AmbilAbsenPageState extends State<AmbilAbsenPage> {
  Map<String, dynamic>? jadwal;
  bool isLoading = true;
  GoogleMapController? mapController;
  Set<Marker> markers = {};
  LocationData? currentLocation;

  @override
  void initState() {
    super.initState();
    fetchJadwal();
    getCurrentLocation();
  }

  Future<void> getCurrentLocation() async {
    try {
      final location = Location();
      var permissionStatus = await location.hasPermission();
      if (permissionStatus == PermissionStatus.denied) {
        permissionStatus = await location.requestPermission();
        if (permissionStatus != PermissionStatus.granted) {
          throw Exception('Location permission not granted');
        }
      }

      final serviceEnabled = await location.serviceEnabled();
      if (!serviceEnabled) {
        final isEnabled = await location.requestService();
        if (!isEnabled) {
          throw Exception('Location service not enabled');
        }
      }

      currentLocation = await location.getLocation();
      setState(() {
        isLoading = false;
      });
    } catch (e) {
      showSnackbar('Gagal mendapatkan lokasi: $e');
      setState(() {
        isLoading = false;
      });
    }
  }

  Future<void> fetchJadwal() async {
    try {
      final url =
          'http://192.168.1.5:8080/get_jadwal_by_id.php?jadwal_id=${widget.jadwalId}';
      final res = await http.get(Uri.parse(url));
      final data = jsonDecode(res.body);

      if (data['success'] && data['jadwal'] != null) {
        final jadwalData = data['jadwal'];
        double distance = 0;
        if (currentLocation != null) {
          distance = calculateHaversine(
            currentLocation!.latitude!,
            currentLocation!.longitude!,
            double.parse(jadwalData['latitude'].toString()),
            double.parse(jadwalData['longitude'].toString()),
          );
        }
        jadwalData['distance'] = distance;

        setState(() {
          jadwal = jadwalData;
          isLoading = false;
        });
      } else {
        showSnackbar(data['message'] ?? "Gagal memuat jadwal");
        setState(() => isLoading = false);
      }
    } catch (e) {
      showSnackbar("Gagal koneksi: $e");
      setState(() => isLoading = false);
    }
  }

  double calculateHaversine(
      double lat1, double lon1, double lat2, double lon2) {
    const earthRadius = 6371000;
    final dLat = _degToRad(lat2 - lat1);
    final dLon = _degToRad(lon2 - lon1);
    final a = sin(dLat / 2) * sin(dLat / 2) +
        cos(_degToRad(lat1)) *
            cos(_degToRad(lat2)) *
            sin(dLon / 2) *
            sin(dLon / 2);
    final c = 2 * atan2(sqrt(a), sqrt(1 - a));
    return earthRadius * c;
  }

  double _degToRad(double deg) => deg * (pi / 180);

  void showSnackbar(String msg) {
    ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(msg)));
  }

  Future<void> submitAbsen() async {
    try {
      final location = Location();
      final current = await location.getLocation();

      final payload = {
        'dosen_id': jadwal!['dosen_id'],
        'jadwal_id': widget.jadwalId,
        'latitude': current.latitude,
        'longitude': current.longitude,
      };

      final response = await http.post(
        Uri.parse('http://192.168.1.5:8080/absen.php'),
        headers: {'Content-Type': 'application/json'},
        body: json.encode(payload),
      );

      final data = json.decode(response.body);
      showSnackbar(data['message']);

      if (data['success']) {
        await fetchJadwal(); 
      }
    } catch (e) {
      showSnackbar('Gagal mengirim absensi: $e');
    }
  }

  void _onMapCreated(GoogleMapController controller) {
    mapController = controller;
    setState(() {
      markers.add(
        Marker(
          markerId: const MarkerId('currentLocation'),
          position: LatLng(
            currentLocation?.latitude ?? 0,
            currentLocation?.longitude ?? 0,
          ),
          infoWindow: const InfoWindow(title: 'Lokasi Anda'),
        ),
      );
      if (jadwal != null) {
        markers.add(
          Marker(
            markerId: const MarkerId('jadwalLocation'),
            position: LatLng(
              double.parse(jadwal!['latitude'].toString()),
              double.parse(jadwal!['longitude'].toString()),
            ),
            infoWindow: const InfoWindow(title: 'Lokasi Absen'),
            icon:
                BitmapDescriptor.defaultMarkerWithHue(BitmapDescriptor.hueBlue),
          ),
        );
      }
    });
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Ambil Absen")),
      body: isLoading
          ? const Center(
              child: Column(
                mainAxisAlignment: MainAxisAlignment.center,
                children: [
                  SizedBox(height: 16),
                ],
              ),
            )
          : (jadwal == null || currentLocation == null)
              ? const Center(
                  child: Column(
                    mainAxisAlignment: MainAxisAlignment.center,
                    children: [
                      CircularProgressIndicator(),
                    ],
                  ),
                )
              : SingleChildScrollView(
                  child: Column(
                    children: [
                      SizedBox(
                        height: 300,
                        child: currentLocation == null
                            ? const Center(child: CircularProgressIndicator())
                            : GoogleMap(
                                onMapCreated: _onMapCreated,
                                initialCameraPosition: CameraPosition(
                                  target: LatLng(
                                    currentLocation!.latitude!,
                                    currentLocation!.longitude!,
                                  ),
                                  zoom: 16,
                                ),
                                markers: markers,
                                myLocationEnabled: true,
                                myLocationButtonEnabled: true,
                                zoomControlsEnabled: true,
                                mapType: MapType.normal,
                              ),
                      ),
                      Padding(
                        padding: const EdgeInsets.all(16.0),
                        child: Card(
                          child: Padding(
                            padding: const EdgeInsets.all(16.0),
                            child: Column(
                              mainAxisSize: MainAxisSize.min,
                              crossAxisAlignment: CrossAxisAlignment.start,
                              children: [
                                Text("Tanggal: ${jadwal!['tanggal'] ?? '-'}"),
                                Text("Jam: ${jadwal!['jam_mulai'] ?? '-'}"),
                                Text(
                                    "Batas Absen: ${jadwal!['batas_absen'] ?? '-'}"),
                                Text(
                                    "Lokasi: (${jadwal!['latitude']}, ${jadwal!['longitude']})"),
                                Text("Radius: ${jadwal!['radius']} meter"),
                                Text(
                                    "Jarak Anda: ${jadwal!['distance']?.toStringAsFixed(1) ?? 'Menghitung...'} meter"),
                                Text("Status: ${jadwal!['status']}"),
                                const SizedBox(height: 16),
                                ElevatedButton(
                                  onPressed: () => submitAbsen(),
                                  style: ElevatedButton.styleFrom(
                                    backgroundColor: (jadwal?['distance'] ??
                                                double.infinity) <=
                                            (jadwal?['radius'] ?? 0)
                                        ? Colors.blue
                                        : Colors.grey,
                                  ),
                                  child: const Text("Ambil Absen"),
                                ),
                                if ((jadwal?['distance'] ?? double.infinity) >
                                    (jadwal?['radius'] ?? 0))
                                  Padding(
                                    padding: const EdgeInsets.only(top: 8.0),
                                    child: Text(
                                      "Anda berada di luar radius ${jadwal?['radius']} meter",
                                      style: const TextStyle(color: Colors.red),
                                    ),
                                  ),
                              ],
                            ),
                          ),
                        ),
                      ),
                    ],
                  ),
                ),
    );
  }
}
