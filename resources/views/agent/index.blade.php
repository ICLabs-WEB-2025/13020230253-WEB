@extends('layouts.app')

@section('title', 'Daftar Rumah Agen')

@section('styles')
    <style>
        :root {
            --primary: #1e40af; /* Darker blue */
            --secondary: #3b82f6; /* Medium blue */
            --accent: #10b981; /* Green */
            --text-dark: #1f2937; /* Very dark gray for main text */
            --text-light: #4b5563; /* Slightly lighter gray for secondary text */
            --bg-light: #f8fafc; /* Off-white background */
            --bg-gradient: linear-gradient(135deg, #3b82f6, #1e40af); /* Blue gradient */
            --error: #ef4444; /* Red for errors */
            --success: #22c55e; /* Green for success */
            --warning: #f59e0b; /* Amber for warnings */
        }

        body {
            font-family: 'Inter', sans-serif; /* Modern font */
            background-color: var(--bg-light);
            color: var(--text-dark);
        }

        .listing-container {
            padding: 3rem 0; /* More vertical padding */
            background: var(--bg-light);
            min-height: 100vh;
        }

        .listing-header {
            margin-bottom: 3rem; /* Increased margin */
            text-align: center;
            animation: fadeIn 0.8s ease-out;
        }

        .listing-header h1 {
            font-size: 2.8rem; /* Larger heading */
            font-weight: 800; /* Extra bold */
            color: var(--primary); /* Use primary color for heading */
            margin-bottom: 0.5rem;
        }

        .listing-header p {
            font-size: 1.1rem;
            color: var(--text-light);
        }

        .search-form {
            background: #fff;
            padding: 2.5rem; /* More padding */
            border-radius: 16px; /* Slightly more rounded corners */
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08); /* Softer, wider shadow */
            margin-bottom: 3rem; /* Increased margin */
            border: 1px solid #e5e7eb; /* Subtle border */
        }

        .form-control, .form-select {
            border-radius: 10px; /* More rounded inputs */
            border: 1px solid #d1d5db;
            padding: 0.85rem 1rem; /* More comfortable padding */
            font-size: 1rem;
            transition: all 0.3s ease; /* Smooth transition for all properties */
            background-color: #f9fafb; /* Light background for inputs */
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15); /* Slightly larger, more pronounced shadow */
            outline: none;
            background-color: #fff;
        }

        .btn-primary {
            background: var(--bg-gradient);
            border: none;
            border-radius: 10px; /* Consistent rounded corners */
            padding: 0.85rem 1.8rem; /* More padding */
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover {
            transform: translateY(-3px); /* More pronounced lift */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
        }

        .btn-success {
            background-color: var(--accent);
            border: none;
            border-radius: 10px;
            padding: 0.85rem 1.8rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .btn-success:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
            opacity: 0.9;
        }

        .btn-danger {
            background-color: var(--error);
            border: none;
            border-radius: 8px; /* Slightly less rounded for action buttons */
            padding: 0.6rem 1.2rem;
            font-size: 0.95rem;
            font-weight: 600;
            color: #fff;
            transition: all 0.3s ease;
        }

        .btn-danger:hover {
            background-color: #dc2626; /* Slightly darker red */
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: #6b7280; /* Gray for disabled/secondary */
            border: none;
            border-radius: 10px;
            padding: 0.85rem 1.8rem;
            font-size: 1.05rem;
            font-weight: 700;
            color: #fff;
            cursor: not-allowed;
            opacity: 0.7;
        }

        .card {
            border: none;
            border-radius: 16px; /* Consistent rounded corners */
            overflow: hidden;
            box-shadow: 0 6px 25px rgba(0, 0, 0, 0.1); /* Enhanced shadow */
            transition: transform 0.4s cubic-bezier(0.25, 0.8, 0.25, 1), box-shadow 0.4s cubic-bezier(0.25, 0.8, 0.25, 1); /* Smoother transition */
            animation: cardFadeIn 0.6s ease-out forwards; /* Add forwards to keep final state */
            opacity: 0; /* Start hidden for animation */
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .card:hover {
            transform: translateY(-7px); /* More significant lift */
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.18); /* More pronounced shadow on hover */
        }

        .card-img-top {
            height: 220px; /* Slightly reduced height for better balance */
            object-fit: cover;
            transition: transform 0.4s ease;
            display: block;
            width: 100%; /* Ensure image fills width */
            border-bottom: 1px solid #e5e7eb; /* Subtle separator */
        }

        .card:hover .card-img-top {
            transform: scale(1.08); /* More noticeable zoom */
        }

        .card-body {
            padding: 1.5rem; /* Consistent padding */
            display: flex;
            flex-direction: column;
            flex-grow: 1; /* Allows body to expand */
        }

        .card-title {
            font-size: 1.4rem; /* Larger title */
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.75rem;
            line-height: 1.3;
        }

        .card-text {
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 0.5rem;
        }

        .card-text:last-of-type {
            margin-bottom: 1rem; /* Space before buttons */
        }

        .badge {
            padding: 0.4em 0.7em;
            border-radius: 0.5rem;
            font-weight: 600;
            font-size: 0.85rem;
        }

        .badge.bg-success { background-color: var(--success) !important; }
        .badge.bg-danger { background-color: var(--error) !important; }
        .badge.bg-warning { background-color: var(--warning) !important; color: #78350f !important; } /* Darker text for warning */

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            background-color: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.07);
            margin-top: 2rem;
        }

        .empty-state h3 {
            font-size: 2rem;
            color: var(--text-dark);
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .empty-state p {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 2rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes cardFadeIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        /* Modal specific styles */
        .modal-content {
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            border: none;
        }

        .modal-header {
            border-bottom: none;
            padding: 1.5rem 2rem 0.5rem;
        }

        .modal-title {
            font-weight: 700;
            color: var(--primary);
        }

        .modal-body {
            padding: 1rem 2rem 1.5rem;
            color: var(--text-dark);
        }

        .modal-footer {
            border-top: none;
            padding: 0.5rem 2rem 1.5rem;
        }

        .modal-footer .btn {
            border-radius: 8px; /* Slightly less rounded than main buttons */
            padding: 0.6rem 1.2rem;
        }

        /* Pagination styles */
        .pagination {
            --bs-pagination-color: var(--primary);
            --bs-pagination-active-bg: var(--primary);
            --bs-pagination-active-border-color: var(--primary);
            --bs-pagination-hover-color: var(--secondary);
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25);
        }

        .page-item .page-link {
            border-radius: 8px;
            margin: 0 5px;
            transition: all 0.3s ease;
        }

        .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
            box-shadow: 0 4px 10px rgba(30, 64, 175, 0.2);
        }

        .page-item .page-link:hover {
            background-color: var(--secondary);
            border-color: var(--secondary);
            color: #fff;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .listing-header h1 {
                font-size: 2rem;
            }

            .search-form {
                padding: 1.5rem;
            }

            .btn-primary, .btn-success {
                width: 100%; /* Full width buttons on small screens */
                margin-bottom: 0.5rem;
            }

            .col-12.d-flex.gap-2 {
                flex-direction: column;
            }

            .card-img-top {
                height: 180px;
            }
        }
    </style>
@endsection

@section('content')
<div class="listing-container">
    <div class="container">
        <div class="listing-header">
            <h1><i class="fas fa-home me-2"></i>Daftar Rumah Saya</h1>
            <p class="text-muted">Kelola daftar properti Anda dengan mudah.</p>
        </div>

        <div class="search-form">
            <form method="GET" action="{{ route('agent.index') }}" id="search-form" class="row g-3 align-items-end">
                <div class="col-md-6 col-lg-5">
                    <label for="search-input" class="form-label visually-hidden">Cari Judul Properti</label>
                    <input type="text" name="search" id="search-input" class="form-control" placeholder="Cari berdasarkan judul properti..."
                           value="{{ $search ?? '' }}" aria-label="Cari daftar rumah">
                </div>
                <div class="col-md-3 col-lg-3">
                    <label for="sort-by-select" class="form-label visually-hidden">Urutkan Berdasarkan</label>
                    <select name="sort_by" id="sort-by-select" class="form-select" aria-label="Urutkan berdasarkan">
                        <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Judul</option>
                        <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                        <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>Tanggal Ditambahkan</option>
                    </select>
                </div>
                <div class="col-md-3 col-lg-2">
                    <label for="sort-order-select" class="form-label visually-hidden">Urutan</label>
                    <select name="sort_order" id="sort-order-select" class="form-select" aria-label="Urutan pengurutan">
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-flex gap-2">
                    {{-- Atribut 'disabled' awal dihapus, JavaScript akan mengontrol statusnya --}}
                    <button type="submit" class="btn btn-primary flex-grow-1" id="search-btn">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                </div>
            </form>
            <div class="d-flex justify-content-end mt-3">
                 <a href="{{ route('agent.create') }}" class="btn btn-success">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rumah Baru
                </a>
            </div>
        </div>

        @if ($houses->isEmpty())
            <div class="empty-state">
                <i class="fas fa-box-open fa-3x mb-3 text-muted"></i>
                <h3>Ups! Tidak Ada Daftar Rumah Ditemukan</h3>
                <p>Sepertinya Anda belum menambahkan properti. Mulai dengan menambahkan rumah baru ke portofolio Anda sekarang!</p>
                <a href="{{ route('agent.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus-circle me-2"></i>Tambah Rumah Baru
                </a>
            </div>
        @else
            <div class="row" id="listing-grid">
                @foreach ($houses as $house)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card h-100" data-house-id="{{ $house->id }}">
                            @php
                                $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                                $fileExists = Storage::disk('public')->exists($pathToCheck);
                                $imageUrl = $fileExists ? Storage::url($pathToCheck) : 'https://via.placeholder.com/400x220?text=Gambar+Tidak+Tersedia';
                            @endphp
                            <img src="{{ $imageUrl }}"
                                 class="card-img-top lazy"
                                 alt="{{ $house->title }}"
                                 loading="lazy">
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($house->title, 30) }}</h5>
                                <p class="card-text">Harga: <span class="fw-bold text-primary">Rp {{ number_format($house->price, 0, ',', '.') }}</span></p>
                                <p class="card-text">Status:
                                    <span class="badge {{ $house->status === 'Tersedia' ? 'bg-success' : ($house->status === 'Terjual' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $house->status }}
                                    </span>
                                </p>
                                <div class="mt-auto d-flex gap-2"> <a href="{{ route('agent.edit', $house) }}"
                                       class="btn btn-primary btn-sm flex-grow-1"
                                       aria-label="Edit {{ $house->title }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm flex-grow-1 delete-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteModal"
                                            data-house-id="{{ $house->id }}"
                                            data-house-title="{{ $house->title }}"
                                            aria-label="Hapus {{ $house->title }}">
                                        <i class="fas fa-trash me-1"></i>Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $houses->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus properti <strong id="house-title"></strong>? Tindakan ini tidak dapat dibatalkan.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('agent.destroy', $house->id) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash-alt me-1"></i>Hapus Permanen
                    </button>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        // Tangani modal hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function () {
                const houseId = this.dataset.houseId;
                const houseTitle = this.dataset.houseTitle;
                const form = document.getElementById('delete-form');
                const titleElement = document.getElementById('house-title');

                // Update the form action dynamically
                form.action = `/agent/houses/${houseId}`;
                titleElement.textContent = houseTitle;
            });
        });

        // Feedback pencarian real-time dan status loading
        const searchInput = document.getElementById('search-input');
        const searchForm = document.getElementById('search-form');
        const searchBtn = document.getElementById('search-btn');

        // Tombol "Cari" selalu aktif secara default saat halaman dimuat.
        // Ini memberikan UX yang lebih baik karena pengguna bisa langsung mencari tanpa mengetik.
        searchBtn.disabled = false;
        searchBtn.classList.remove('btn-secondary'); // Pastikan kelas secondary dihapus
        searchBtn.classList.add('btn-primary'); // Pastikan kelas primary ditambahkan

        searchInput.addEventListener('input', function () {
            // Aktifkan tombol jika ada teks (bukan hanya spasi)
            if (this.value.trim().length > 0) {
                searchBtn.disabled = false;
                searchBtn.classList.remove('btn-secondary');
                searchBtn.classList.add('btn-primary');
            } else {
                // Nonaktifkan tombol jika input kosong atau hanya berisi spasi
                searchBtn.disabled = true;
                searchBtn.classList.remove('btn-primary');
                searchBtn.classList.add('btn-secondary');
            }
        });

        // Status loading saat submit form
        searchForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
        });

        // Optional: Add a smooth scroll to top button for better UX on long lists
        // You'll need to include Font Awesome for icons if not already:
        // <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    </script>
@endsection