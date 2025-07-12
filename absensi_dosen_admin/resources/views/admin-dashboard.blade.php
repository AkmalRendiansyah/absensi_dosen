<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Data Dosen</title>
</head>
<body>
    <h2>Daftar Dosen</h2>

    @isset($error)
        <p style="color:red">{{ $error }}</p>
    @endisset

    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Nama Lengkap</th>
                <th>NIDN</th>
                <th>Jurusan</th>
                <th>Prodi</th>
                <th>Email</th>
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
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
