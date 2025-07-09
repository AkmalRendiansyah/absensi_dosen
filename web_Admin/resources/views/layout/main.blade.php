<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sistem Absensi Dosen')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            min-height: 100vh;
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            background-color: #343a40;
            position: fixed;
            top: 0;
            left: 0;
            width: 220px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .sidebar a {
            color: white;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
        }
        .main-content {
            margin-left: 220px;
            padding: 20px;
        }
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        .logout-btn {
            margin: 20px;
        }
    </style>
</head>
<body>

    <!-- Header / Navbar -->
    <nav class="navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <span class="navbar-brand mb-0 h1">Sistem Absensi Dosen</span>
        </div>
    </nav>

    <!-- Sidebar -->
    <div class="sidebar">
        <div>
            <h5 class="text-center mt-4">Navigasi</h5>
            <a href="{{ url('/dosen') }}" class="{{ Request::is('dosen*') ? 'active' : '' }}">👨‍🏫 Data Dosen</a>
            <a href="{{ url('/users') }}" class="{{ Request::is('users*') ? 'active' : '' }}">👥 Data User</a>
            <a href="{{ url('/jadwal') }}" class="{{ Request::is('jadwal*') ? 'active' : '' }}">📆 Data Jadwal</a>
            <a href="{{ url('/presensi') }}" class="{{ Request::is('presensi*') ? 'active' : '' }}">📝 Data Presensi</a>
        </div>
        <div class="logout-btn text-center mb-3">
            <a href="{{ url('/') }}" class="btn btn-outline-light btn-sm">🚪 Logout</a>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="mt-5 pt-3">
            @yield('content')
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
