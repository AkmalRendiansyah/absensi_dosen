<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Dosen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Edit Data Dosen</h3>

                <form method="POST" action="{{ url('/dosen/' . $dosen['id']) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap:</label>
                        <input type="text" name="nama_lengkap" class="form-control" value="{{ $dosen['nama_lengkap'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIDN:</label>
                        <input type="text" name="nidn" class="form-control" value="{{ $dosen['nidn'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jurusan:</label>
                        <input type="text" name="jurusan" class="form-control" value="{{ $dosen['jurusan'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Prodi:</label>
                        <input type="text" name="prodi" class="form-control" value="{{ $dosen['prodi'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email:</label>
                        <input type="email" name="email" class="form-control" value="{{ $dosen['email'] }}" required>
                    </div>

                    <div class="d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Update</button>
                        <a href="{{ url('/dosen') }}" class="btn btn-secondary">← Kembali</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
