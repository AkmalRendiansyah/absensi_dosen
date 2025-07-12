@extends('layout.main')

@section('title', 'Tambah Jadwal')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Tambah Jadwal</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ url('/jadwal/store') }}">
        @csrf


        <div class="mb-3">
            <label for="tanggal" class="form-label">Tanggal</label>
            <input type="date" class="form-control" id="tanggal" name="tanggal" required>
        </div>

        <div class="mb-3">
            <label for="jam_mulai" class="form-label">Jam Mulai</label>
            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" required>
        </div>

        <div class="mb-3">
            <label for="batas_absen" class="form-label">Batas Absen</label>
            <input type="time" class="form-control" id="batas_absen" name="batas_absen" required>
        </div>

        <div class="mb-3">
            <label for="radius" class="form-label">Radius (meter)</label>
            <input type="number" class="form-control" id="radius" name="radius" required>
        </div>

        <div class="mb-3">
            <label for="dosen_id" class="form-label">Dosen</label>
            <select class="form-select" id="dosen_id" name="dosen_id" required>
                <option value="">-- Pilih Dosen --</option>
                @foreach ($dosen as $d)
                    <option value="{{ $d['id'] }}">{{ $d['nama_lengkap'] }} ({{ $d['nidn'] }})</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Pilih Lokasi di Peta</label>
            <div id="map" style="height: 300px;" class="border rounded"></div>
        </div>

        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Simpan Jadwal</button>
            <a href="{{ url('/jadwal') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </form>
</div>

<!-- Leaflet -->
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    var map = L.map('map').setView([-0.7893, 113.9213], 5); // Default Indonesia
    var marker;

    // Tambahkan layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap'
    }).addTo(map);

    // Coba deteksi lokasi pengguna
    if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(
        function (position) {
            var lat = position.coords.latitude;
            var lng = position.coords.longitude;

            map.setView([lat, lng], 15);
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
        },
        function (error) {
            switch(error.code) {
                case error.PERMISSION_DENIED:
                    alert("Izin lokasi ditolak oleh pengguna.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Lokasi tidak tersedia.");
                    break;
                case error.TIMEOUT:
                    alert("Permintaan lokasi melebihi batas waktu.");
                    break;
                default:
                    alert("Terjadi kesalahan saat mendapatkan lokasi.");
                    break;
            }
        }
    );
} else {
    alert("Browser tidak mendukung geolocation.");
}


    // Update marker saat peta diklik
    map.on('click', function (e) {
        if (marker) {
            map.removeLayer(marker);
        }
        marker = L.marker(e.latlng).addTo(map);
        document.getElementById('latitude').value = e.latlng.lat;
        document.getElementById('longitude').value = e.latlng.lng;
    });
</script>
@endsection
