<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WebGIS DIY')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
        rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB"
        crossorigin="anonymous">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    @yield('styles')
</head>

<body>
    <!-- Glass Navbar -->
    <div class="navbar-glass">
        <div class="nav-menu">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('peta') }}">Peta</a>
            <a href="{{ route('tabel') }}">Tabel</a>
        </div>

        <div class="search-box">
            <input type="text" id="searchInput" placeholder="Cari lokasi...">
            <button onclick="searchLocation()">Cari</button>
        </div>
    </div>

    <!-- Content -->
    @yield('content')

    <!-- Modal -->
    <div class="modal" id="modal">
        <div class="modal-content">
            <h3 id="modalTitle"></h3>
            <p id="modalText"></p>
            <button class="close-btn" onclick="closeModal()">Tutup</button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>

    @yield('scripts')

    @include('components.toast')
</body>

</html>
