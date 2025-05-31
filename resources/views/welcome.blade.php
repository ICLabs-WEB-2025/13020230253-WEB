<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PropertyApp - Selamat Datang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
        }
        .navbar {
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            padding: 1rem 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        .navbar-brand, .nav-link {
            color: #fff !important;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #e0f7fa !important;
        }
        .hero-section {
            position: relative;
            height: 90vh;
            overflow: hidden;
        }
        .carousel-item {
            height: 90vh;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        .carousel-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6));
        }
        .hero-content {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: #fff;
            text-align: center;
            z-index: 2;
            animation: fadeInUp 1.2s ease-in-out;
        }
        .hero-content h1 {
            font-size: 3.5rem;
            font-weight: 700;
            text-shadow: 2px 2px 8px rgba(0, 0, 0, 0.5);
            margin-bottom: 1rem;
        }
        .hero-content p {
            font-size: 1.3rem;
            margin-bottom: 2rem;
        }
        .search-form {
            display: flex;
            gap: 1rem;
            background-color: rgba(255, 255, 255, 0.95);
            padding: 1.5rem;
            border-radius: 50px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
            max-width: 900px;
            width: 100%;
            animation: fadeInUp 1.5s ease-in-out;
        }
        .search-form input, .search-form select {
            border: none;
            border-radius: 25px;
            padding: 0.75rem 1.5rem;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
        }
        .search-form input:focus, .search-form select:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.5);
        }
        .search-form button {
            border-radius: 25px;
            padding: 0.75rem 2rem;
            font-weight: 500;
            transition: transform 0.3s ease, background-color 0.3s ease;
        }
        .search-form button:hover {
            transform: scale(1.05);
            background-color: #2563eb;
        }
        .carousel-control-prev, .carousel-control-next {
            width: 5%;
            background: none;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }
        .carousel-control-prev:hover, .carousel-control-next:hover {
            opacity: 1;
        }
        .carousel-control-prev-icon, .carousel-control-next-icon {
            display: none; /* Sembunyikan ikon default */
        }
        .carousel-control-prev::after, .carousel-control-next::after {
            font-size: 3rem;
            font-weight: 700;
            color: #fff;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.5);
        }
        .carousel-control-prev::after {
            content: '<';
        }
        .carousel-control-next::after {
            content: '>';
        }
        .properties-section {
            padding: 5rem 0;
            background: linear-gradient(180deg, #fff 0%, #f0f2f5 100%);
        }
        .property-card {
            position: relative;
            overflow: hidden;
            border: none;
            border-radius: 15px;
            background-color: #fff;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: fadeInUp 1s ease-in-out;
        }
        .property-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(0, 0, 0, 0.15);
        }
        .property-card img {
            border-radius: 15px 15px 0 0;
            height: 220px;
            object-fit: cover;
            transition: transform 0.5s ease;
        }
        .property-card:hover img {
            transform: scale(1.05);
        }
        .card-body {
            padding: 1.5rem;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #1e3a8a;
        }
        .card-text {
            font-size: 0.95rem;
            color: #4b5563;
            margin-bottom: 0.5rem;
        }
        .card-text i {
            margin-right: 0.5rem;
            color: #3b82f6;
        }
        .btn-sm {
            border-radius: 20px;
            padding: 0.5rem 1.5rem;
            font-size: 0.9rem;
        }
        .footer-section {
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
            color: #fff;
            padding: 2.5rem 0;
        }
        .footer-section a {
            color: #e0f7fa;
            transition: color 0.3s ease;
        }
        .footer-section a:hover {
            color: #fff;
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">PropertyApp</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Home</a>
                    </li>
                   
                    @auth
                        @if (Auth::user()->role === 'agent')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('agent.index') }}">Listing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('agent.requests') }}">Permintaan</a>
                            </li>
                        @elseif (Auth::user()->role === 'buyer')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('buyer.index') }}">Jelajahi</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('buyer.offers') }}">Penawaran</a>
                            </li>
                        @elseif (Auth::user()->role === 'admin')
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.agent.applications') }}">Kelola</a>
                            </li>
                        @endif
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section with Carousel -->
    <div class="hero-section">
        <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
            <div class="carousel-inner">
                <div class="carousel-item active" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
                <div class="carousel-item" style="background-image: url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?ixlib=rb-4.0.3&auto=format&fit=crop&w=1600&q=80');"></div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                <span class="visually-hidden">Next</span>
            </button>
        </div>
        <div class="hero-content">
            <h1>Temukan Properti Impian Anda</h1>
            <p>Jelajahi berbagai pilihan rumah terbaik dengan PropertyApp</p>
            <div class="search-form">
                <input type="text" class="form-control" id="search" placeholder="Cari rumah..." name="search">
                <select class="form-select" id="sort" name="sort">
                    <option value="price_asc">Harga: Rendah - Tinggi</option>
                    <option value="price_desc">Harga: Tinggi - Rendah</option>
                    <option value="status_tersedia">Status: Tersedia</option>
                    <option value="status_terjual">Status: Terjual</option>
                </select>
                <button class="btn btn-primary btn-custom" onclick="filterProperties()">
                    <i class="bi bi-search me-2"></i>Cari
                </button>
            </div>
        </div>
    </div>

    <!-- Properties Section -->
    <section class="properties-section">
        <div class="container">
            <h2 class="text-center mb-5" style="color: #1e3a8a; font-weight: 700;">Properti Unggulan</h2>
            <div class="row" id="propertiesList">
                @if (isset($featuredHouses) && $featuredHouses->isNotEmpty())
                    @foreach ($featuredHouses as $house)
                        <div class="col-md-4 col-sm-6 mb-4">
                            <div class="card property-card">
                                @php
                                    // Hapus prefiks 'public/' dan pastikan tidak ada '/' di awal
                                    $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                                    $fileExists = Storage::disk('public')->exists($pathToCheck);
                                    $imageUrl = $fileExists ? Storage::url($pathToCheck) : null;
                                    // Debugging sementara
                                    $originalPath = $house->photo_path;
                                @endphp
                                @if ($imageUrl)
                                    <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $house->title }}">
                                @else
                                    <img src="https://via.placeholder.com/300x220?text={{ urlencode('Gambar Tidak Tersedia: ' . $house->title) }}" class="card-img-top" alt="Gambar tidak tersedia">
                                    <!-- Debugging sementara -->
                                    <p style="font-size: 0.75rem; color: #666;">Path: {{ $originalPath }}</p>
                                    <p style="font-size: 0.75rem; color: #666;">Path checked: {{ $pathToCheck }}</p>
                                    <p style="font-size: 0.75rem; color: #666;">File exists: {{ $fileExists ? 'Yes' : 'No' }}</p>
                                @endif
                                <div class="card-body">
                                    <h5 class="card-title">{{ $house->title }}</h5>
                                    <p class="card-text"><i class="bi bi-currency-dollar"></i><strong>Harga:</strong> Rp {{ number_format($house->price, 0, ',', '.') }}</p>
                                    <p class="card-text"><i class="bi bi-house-door"></i><strong>Status:</strong> {{ $house->status }}</p>
                                    <p class="card-text"><i class="bi bi-person"></i><strong>Agen:</strong> {{ $house->agent->name ?? 'Tidak diketahui' }}</p>
                                    @if (auth()->check() && auth()->user()->role === 'buyer')
                                        <a href="{{ route('buyer.show', $house->id) }}" class="btn btn-primary btn-sm">Lihat Detail</a>
                                    @else
                                        <a href="{{ route('login') }}" class="btn btn-primary btn-sm">Login untuk Detail</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p class="text-muted">Belum ada properti yang tersedia saat ini.</p>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer-section">
        <div class="container text-center">
            <p>© 2025 PropertyApp. All rights reserved.</p>
            <p>Hubungi kami: <a href="mailto:support@propertyapp.com">support@propertyapp.com</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function filterProperties() {
            const search = document.getElementById('search').value;
            const sort = document.getElementById('sort').value;
            let url = '{{ route('houses.index') }}';
            const params = new URLSearchParams();
            if (search) params.append('search', search);
            if (sort) params.append('sort', sort);
            if (params.toString()) url += '?' + params.toString();
            window.location.href = url;
        }
    </script>
</body>
</html>