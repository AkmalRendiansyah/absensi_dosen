<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-body">
                <h3 class="card-title mb-4">Edit User</h3>
                
                <form method="POST" action="{{ url('/users/' . $user['id']) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" name="email" value="{{ $user['email'] }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru (opsional):</label>
                        <input type="password" class="form-control" name="password">
                    </div>

                    <div class="mb-3">
                        <label for="role" class="form-label">Role:</label>
                        <input type="text" class="form-control" name="role" value="{{ $user['role'] }}" required>
                    </div>

                    <button type="submit" class="btn btn-success">Update</button>
                    <a href="{{ url('/users') }}" class="btn btn-secondary ms-2">← Kembali</a>
                </form>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
