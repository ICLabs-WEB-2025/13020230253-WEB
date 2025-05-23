<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Rumah - PropertyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f2f5;
            margin: 0;
            padding-top: 60px;
        }
        .navbar {
            background: linear-gradient(90deg, #1e3a8a 0%, #3b82f6 100%);
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
        .container {
            padding: 2rem;
        }
        .card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
        }
        .card-img-top {
            height: 300px;
            object-fit: cover;
        }
        .btn-buy {
            background: #3b82f6;
            color: #fff;
            border-radius: 20px;
            transition: background 0.3s ease;
        }
        .btn-buy:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ route('buyer.index') }}">PropertyApp - Buyer</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('buyer.index') }}">Daftar Rumah</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            @if ($house->photo_path)
                <img src="{{ Storage::url($house->photo_path) }}" class="card-img-top" alt="{{ $house->title }}">
            @else
                <img src="https://via.placeholder.com/300x300?text={{ $house->title }}" class="card-img-top" alt="Placeholder">
            @endif
            <div class="card-body">
                <h2 class="card-title" style="color: #1e3a8a;">{{ $house->title }}</h2>
                <p class="card-text"><strong>Harga:</strong> Rp {{ number_format($house->price, 0, ',', '.') }}</p>
                <p class="card-text"><strong>Status:</strong> {{ $house->status }}</p>
                <p class="card-text"><strong>Agen:</strong> {{ $house->agent->name }}</p>
                <p class="card-text"><strong>Deskripsi:</strong> {{ $house->description ?? 'Tidak ada deskripsi' }}</p>
                <a href="{{ route('buyer.request', $house->id) }}" class="btn btn-buy btn-lg">Beli Sekarang</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>