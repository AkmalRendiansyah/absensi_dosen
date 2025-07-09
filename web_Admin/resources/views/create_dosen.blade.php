<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Dosen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Form Tambah Dosen</h3>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ url('/dosen/store') }}">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap:</label>
                        <input type="text" name="nama_lengkap" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIDN:</label>
                        <input type="text" name="nidn" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan:</label>
                        <input type="text" name="jurusan" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prodi:</label>
                        <input type="text" name="prodi" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Simpan</button>
                        <a href="{{ url('/dosen') }}" class="btn btn-secondary">← Kembali ke daftar dosen</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
