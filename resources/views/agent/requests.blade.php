<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Permintaan Penawaran</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('agent.requests') }}" class="row g-3">
                    <div class="col-md-6">
                        <input type="text" name="search" class="form-control" placeholder="Cari berdasarkan judul rumah..." value="{{ $search ?? '' }}">
                    </div>
                    <div class="col-md-3">
                        <select name="sort_by" class="form-control">
                            <option value="offer_price" {{ $sortBy == 'offer_price' ? 'selected' : '' }}>Harga Tawaran</option>
                            <option value="status" {{ $sortBy == 'status' ? 'selected' : '' }}>Status</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="sort_order" class="form-control">
                            <option value="asc" {{ $sortOrder == 'asc' ? 'selected' : '' }}>Rendah-Tinggi</option>
                            <option value="desc" {{ $sortOrder == 'desc' ? 'selected' : '' }}>Tinggi-Rendah</option>
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="submit" class="btn btn-primary w-100">Cari</button>
                    </div>
                </form>
            </div>
        </div>
        @foreach ($offers as $offer)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <h5 class="card-title">{{ $offer->house->title }}</h5>
                    <p class="card-text">Harga Tawaran: Rp {{ number_format($offer->offer_price, 0, ',', '.') }}</p>
                    <p class="card-text">Pesan: {{ $offer->message ?? 'Tidak ada pesan' }}</p>
                    <p class="card-text">Status: 
                        <span class="badge {{ $offer->status == 'Tertunda' ? 'bg-warning' : ($offer->status == 'Disetujui' ? 'bg-success' : 'bg-danger') }}">
                            {{ $offer->status }}
                        </span>
                    </p>
                    @if ($offer->status == 'Tertunda')
                        <form action="{{ route('agent.approve', $offer) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                        </form>
                        <form action="{{ route('agent.reject', $offer) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
        {{ $offers->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>