<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Edit Rumah</h1>
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        <div class="card shadow-sm">
            <div class="card-body">
                <form method="POST" action="{{ route('agent.update', $house) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="title" class="form-label">Judul</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $house->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="price" class="form-label">Harga (Rp)</label>
                        <input type="number" class="form-control" id="price" name="price" value="{{ $house->price }}" step="0.01" required>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="Tersedia" {{ $house->status == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                            <option value="Dalam Proses" {{ $house->status == 'Dalam Proses' ? 'selected' : '' }}>Dalam Proses</option>
                            <option value="Terjual" {{ $house->status == 'Terjual' ? 'selected' : '' }}>Terjual</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="photo" class="form-label">Foto Rumah (opsional)</label>
                        <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        @if ($house->photo_path)
                            <img src="{{ Storage::url($house->photo_path) }}" alt="Photo" class="img-fluid rounded mt-2" style="max-width: 600px;">
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary">Update</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>