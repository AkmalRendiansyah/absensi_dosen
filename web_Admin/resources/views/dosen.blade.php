@extends('layout.main')

@section('title', 'Data Dosen')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Dosen</h2>

    <a href="{{ url('/dosen/create') }}" class="btn btn-primary mb-3">+ Buat Dosen</a>
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @isset($error)
        <div class="alert alert-danger">{{ $error }}</div>
    @endisset

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Nama Lengkap</th>
                    <th>NIDN</th>
                    <th>Jurusan</th>
                    <th>Prodi</th>
                    <th>Email</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dosen as $d)
                    <tr>
                        <td>{{ $d['nama_lengkap'] }}</td>
                        <td>{{ $d['nidn'] }}</td>
                        <td>{{ $d['jurusan'] }}</td>
                        <td>{{ $d['prodi'] }}</td>
                        <td>{{ $d['email'] }}</td>
                        <td>
                            <a href="{{ url('/dosen/' . $d['id'] . '/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ url('/dosen/' . $d['id']) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
