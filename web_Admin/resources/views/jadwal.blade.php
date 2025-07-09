@extends('layout.main')

@section('title', 'Data Jadwal')

@section('content')
    <h2 class="mb-4">Daftar Jadwal</h2>

    @if ($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @elseif (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <a href="{{ url('/jadwal/create') }}" class="btn btn-primary mb-3">+ Tambah Jadwal</a>

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark text-center">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Jam Mulai</th>
                    <th>Batas Absen</th>
                    <th>Radius (meter)</th>
                    <th>Status</th>
                    <th>Dosen</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($jadwal as $index => $item)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $item['tanggal'] }}</td>
                        <td>{{ $item['jam_mulai'] }}</td>
                        <td>{{ $item['batas_absen'] }}</td>
                        <td>{{ $item['radius'] }}</td>
                        <td>{{ ucfirst($item['status']) }}</td>
                        <td>{{ $item->dosen->nama_lengkap ?? 'Tidak ditemukan' }}</td>
                        <td>
                            <div id="map-{{ $item['id'] }}" class="map"></div>
                        </td>
                        <td class="text-center">
                            <a href="{{ url('/jadwal/' . $item['id'] . '/edit') }}" class="btn btn-sm btn-warning mb-1">Edit</a>
                            <form action="{{ url('/jadwal/' . $item['id']) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus jadwal ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="9" class="text-center">Tidak ada data jadwal.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css"/>
    <style>
        .map {
            width: 100%;
            height: 150px;
        }
    </style>

    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        @foreach ($jadwal as $item)
            var lat{{ $item['id'] }} = {{ $item['latitude'] }};
            var lng{{ $item['id'] }} = {{ $item['longitude'] }};
            var map{{ $item['id'] }} = L.map('map-{{ $item['id'] }}').setView([lat{{ $item['id'] }}, lng{{ $item['id'] }}], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors'
            }).addTo(map{{ $item['id'] }});
            L.marker([lat{{ $item['id'] }}, lng{{ $item['id'] }}]).addTo(map{{ $item['id'] }})
                .bindPopup(`<a href="https://www.google.com/maps?q=${lat{{ $item['id'] }}},${lng{{ $item['id'] }}}" target="_blank">Lihat di Google Maps</a>`);
        @endforeach
    </script>
@endsection
