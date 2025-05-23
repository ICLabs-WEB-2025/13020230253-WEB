@extends('layouts.app')

@section('title', 'Daftar Rumah Agen')

@section('styles')
    <style>
        :root {
            --primary: #1e40af;
            --secondary: #3b82f6;
            --accent: #10b981;
            --text: #1f2937;
            --bg-light: #f8fafc;
            --bg-gradient: linear-gradient(135deg, #3b82f6, #1e40af);
            --error: #ef4444;
        }

        body {
            font-family: 'Roboto', sans-serif;
        }

        .listing-container {
            padding: 2rem 0;
            background: var(--bg-light);
            min-height: 100vh;
        }

        .listing-header {
            margin-bottom: 2rem;
            animation: fadeIn 0.8s ease-out;
            text-align: center;
        }

        .listing-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text);
        }

        .search-form {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem;
            font-size: 1rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .btn-primary {
            background: var(--bg-gradient);
            border: none;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-size: 1rem;
            font-weight: 600;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        .card {
            border: none;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            animation: cardFadeIn 0.5s ease-out;
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.2);
        }

        .card-img-top {
            height: 250px;
            object-fit: cover;
            transition: transform 0.3s ease;
            display: block; /* Pastikan gambar terlihat */
        }

        .card:hover .card-img-top {
            transform: scale(1.05);
        }

        @media (max-width: 576px) {
            .listing-header h1 {
                font-size: 1.8rem;
            }

            .search-form {
                padding: 1.5rem;
            }
        }
    </style>
@endsection

@section('content')
<div class="listing-container">
    <div class="container">
        <div class="listing-header">
            <h1>Daftar Rumah Saya</h1>
            <p class="text-muted">Kelola daftar properti Anda dengan mudah.</p>
        </div>

        <div class="search-form">
            <form method="GET" action="{{ route('agent.index') }}" id="search-form" class="row g-3">
                <div class="col-md-6">
                    <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul" 
                           value="{{ $search ?? '' }}" aria-label="Cari daftar rumah">
                </div>
                <div class="col-md-3">
                    <select name="sort_by" class="form-select" aria-label="Urutkan berdasarkan">
                        <option value="price" {{ $sortBy === 'price' ? 'selected' : '' }}>Harga</option>
                        <option value="title" {{ $sortBy === 'title' ? 'selected' : '' }}>Judul</option>
                        <option value="status" {{ $sortBy === 'status' ? 'selected' : '' }}>Status</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="sort_order" class="form-select" aria-label="Urutan pengurutan">
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>Naik</option>
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>Turun</option>
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button type="submit" class="btn btn-primary" id="search-btn">
                        <i class="fas fa-search me-2"></i>Cari
                    </button>
                    <a href="{{ route('agent.create') }}" class="btn btn-success">
                        <i class="fas fa-plus me-2"></i>Tambah Rumah Baru
                    </a>
                </div>
            </form>
        </div>

        @if ($houses->isEmpty())
            <div class="empty-state">
                <h3>Tidak Ada Daftar Rumah</h3>
                <p>Mulai dengan menambahkan rumah baru ke portofolio Anda.</p>
                <a href="{{ route('agent.create') }}" class="btn btn-primary">Tambah Rumah Baru</a>
            </div>
        @else
            <div class="row" id="listing-grid">
                @foreach ($houses as $house)
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100" data-house-id="{{ $house->id }}">
                            @php
                                // Hapus prefiks 'public/' dan pastikan tidak ada '/' di awal
                                $pathToCheck = ltrim(str_replace('public/', '', $house->photo_path), '/');
                                $fileExists = Storage::disk('public')->exists($pathToCheck);
                                // Gunakan path yang sudah diperbaiki untuk URL
                                $imageUrl = Storage::url($pathToCheck);
                            @endphp
                            @if ($house->photo_path && $fileExists)
                                <img src="{{ $imageUrl }}" 
                                     class="card-img-top lazy" 
                                     alt="{{ $house->title }}" 
                                     loading="lazy">
                            @else
                                <img src="https://via.placeholder.com/300?text=Gambar+Tidak+Tersedia" 
                                     class="card-img-top lazy" 
                                     alt="Gambar tidak tersedia" 
                                     loading="lazy">
                                <p style="font-size: 0.75rem; color: #666;">File tidak ditemukan: {{ $house->photo_path }}</p>
                                <p style="font-size: 0.75rem; color: #666;">Path checked: {{ $pathToCheck }}</p>
                                <p style="font-size: 0.75rem; color: #666;">File exists: {{ $fileExists ? 'Yes' : 'No' }}</p>
                            @endif
                            <div class="card-body">
                                <h5 class="card-title">{{ Str::limit($house->title, 25) }}</h5>
                                <p class="card-text">Harga: Rp {{ number_format($house->price, 0, ',', '.') }}</p>
                                <p class="card-text">Status: 
                                    <span class="badge {{ $house->status === 'Tersedia' ? 'bg-success' : ($house->status === 'Terjual' ? 'bg-danger' : 'bg-warning') }}">
                                        {{ $house->status }}
                                    </span>
                                </p>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('agent.edit', $house) }}" 
                                       class="btn btn-primary btn-sm" 
                                       aria-label="Edit {{ $house->title }}">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                    <button class="btn btn-danger btn-sm delete-btn" 
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

            <div class="d-flex justify-content-center mt-4">
                {{ $houses->appends(request()->query())->links('vendor.pagination.bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus <strong id="house-title"></strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="delete-form" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" id="confirm-delete">Hapus</button>
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

                form.action = `/agent/houses/${houseId}`;
                titleElement.textContent = houseTitle;
            });
        });

        // Feedback pencarian real-time
        const searchInput = document.querySelector('input[name="search"]');
        const searchForm = document.getElementById('search-form');
        const searchBtn = document.getElementById('search-btn');

        searchInput.addEventListener('input', function () {
            if (this.value.length > 2) {
                searchBtn.disabled = false;
                searchBtn.classList.add('btn-primary');
                searchBtn.classList.remove('btn-secondary');
            } else {
                searchBtn.disabled = true;
                searchBtn.classList.add('btn-secondary');
                searchBtn.classList.remove('btn-primary');
            }
        });

        // Status loading saat submit form
        searchForm.addEventListener('submit', function () {
            searchBtn.disabled = true;
            searchBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mencari...';
        });
    </script>
@endsection