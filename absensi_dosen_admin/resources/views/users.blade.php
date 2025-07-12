@extends('layout.main')

@section('title', 'Daftar User')

@section('content')
<div class="container">
    <h2 class="mb-4">Daftar Seluruh User</h2>

    <a href="{{ url('/users/create') }}" class="btn btn-primary mb-3">+ Tambah User</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Dibuat Pada</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>{{ $user->created_at }}</td>
                        <td>
                            <a href="{{ url('/users/' . $user->id . '/edit') }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ url('/users/' . $user->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">Tidak ada user</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
