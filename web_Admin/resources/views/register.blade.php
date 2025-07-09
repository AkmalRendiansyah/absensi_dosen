<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register Dosen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
    <h2>Form Registrasi Dosen</h2>
    <form id="registerForm">
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <label>Konfirmasi Password:</label><br>
        <input type="password" name="password2" required><br><br>

        <label>NIDN:</label><br>
        <input type="text" name="nidn" required><br><br>

        <label>Jurusan:</label><br>
        <input type="text" name="jurusan" required><br><br>

        <label>Program Studi:</label><br>
        <input type="text" name="prodi" required><br><br>

        <button type="submit">Daftar</button>
    </form>

    <p id="message"></p>

    <script>
        const form = document.getElementById('registerForm');
        const message = document.getElementById('message');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => data[key] = value);

            try {
                const response = await fetch('http://192.168.191.238:8080/register_dosen.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                message.innerText = result.message;
                message.style.color = result.success ? 'green' : 'red';
            } catch (error) {
                message.innerText = 'Terjadi kesalahan saat mengirim data.';
                message.style.color = 'red';
            }
        });
    </script>
</body>
</html>
