@extends('layout.main')

@section('title', 'Data Presensi')

@section('content')
    <h2 class="mb-4">Data Presensi Dosen</h2>

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Dosen ID</th>
                    <th>Tanggal</th>
                    <th>Waktu Absen</th>
                    <th>Lokasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($presensi as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $item->dosen->nama_lengkap ?? 'Tidak ditemukan' }}</td>
                        <td>{{ $item['tanggal'] }}</td>
                        <td>{{ $item['waktu_absen'] }}</td>
                        <td>
                            <div id="map-{{ $item['id'] }}" class="map"></div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        .map {
            width: 100%;
            height: 150px;
        }
    </style>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        @foreach ($presensi as $item)
            var lat{{ $item['id'] }} = {{ $item['latitude'] }};
            var lng{{ $item['id'] }} = {{ $item['longitude'] }};
            var map{{ $item['id'] }} = L.map('map-{{ $item['id'] }}').setView([lat{{ $item['id'] }}, lng{{ $item['id'] }}], 16);

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map{{ $item['id'] }});

            L.marker([lat{{ $item['id'] }}, lng{{ $item['id'] }}])
                .addTo(map{{ $item['id'] }})
                .bindPopup(`<a href="https://www.google.com/maps?q=${lat{{ $item['id'] }}},${lng{{ $item['id'] }}}" target="_blank">Lihat di Google Maps</a>`);
        @endforeach
    </script>
@endsection
