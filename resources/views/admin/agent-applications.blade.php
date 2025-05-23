<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Aplikasi Agen</h1>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @foreach ($applications as $application)
            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <p class="mb-1"><strong>Nama:</strong> {{ $application->user->name }}</p>
                    <p class="mb-1"><strong>NIK:</strong> {{ $application->nik }}</p>
                    <p class="mb-1"><strong>Alamat:</strong> {{ $application->address }}</p>
                    <p class="mb-1"><strong>Telepon:</strong> {{ $application->phone }}</p>
                    @if ($application->document_path)
                        <p class="mb-1"><strong>Dokumen:</strong> <a href="{{ Storage::url($application->document_path) }}" target="_blank">Lihat Dokumen</a></p>
                    @endif
                    <form action="{{ route('admin.agent.approve', $application->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">Setujui</button>
                    </form>
                    <form action="{{ route('admin.agent.reject', $application->id) }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Tolak</button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</x-app-layout>