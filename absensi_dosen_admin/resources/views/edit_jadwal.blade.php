@extends('layout.main')

@section('title', 'Edit Jadwal')

@section('content')
    <h2 class="mb-4">Edit Jadwal</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <form method="POST" action="{{ url('/jadwal/' . $jadwal['id']) }}">
        @csrf
        @method('PUT')

       

        <div class="row">
            <div class="col-md-4 mb-3">
                <label class="form-label">Tanggal:</label>
                <input type="date" name="tanggal" value="{{ $jadwal['tanggal'] }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Jam Mulai:</label>
                <input type="time" name="jam_mulai" value="{{ $jadwal['jam_mulai'] }}" class="form-control" required>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label">Batas Absen:</label>
                <input type="time" name="batas_absen" value="{{ $jadwal['batas_absen'] }}" class="form-control" required>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Radius (meter):</label>
            <input type="number" name="radius" value="{{ $jadwal['radius'] }}" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Dosen:</label>
            <select name="dosen_id" class="form-select" required>
                @foreach ($dosen as $d)
                    <option value="{{ $d['id'] }}" {{ $jadwal['dosen_id'] == $d['id'] ? 'selected' : '' }}>
                        {{ $d['nama_lengkap'] }} ({{ $d['nidn'] }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Lokasi Saat Ini:</label>
            <div id="map" style="height: 300px;"></div>
        </div>

        <input type="hidden" name="latitude" id="latitude" value="{{ $jadwal['latitude'] }}">
        <input type="hidden" name="longitude" id="longitude" value="{{ $jadwal['longitude'] }}">

        <div class="d-flex justify-content-between mt-4">
            <button type="submit" class="btn btn-primary">💾 Simpan Perubahan</button>
            <a href="{{ url('/jadwal') }}" class="btn btn-secondary">← Kembali</a>
        </div>
    </form>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        var lat = {{ $jadwal['latitude'] }};
        var lng = {{ $jadwal['longitude'] }};
        var map = L.map('map').setView([lat, lng], 15);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        var marker = L.marker([lat, lng], { draggable: true }).addTo(map);

        marker.on('dragend', function (e) {
            var latlng = marker.getLatLng();
            document.getElementById('latitude').value = latlng.lat;
            document.getElementById('longitude').value = latlng.lng;
        });
    </script>
@endsection
