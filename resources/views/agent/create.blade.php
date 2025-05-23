@extends('layouts.app')

@section('title', 'Tambah Rumah Baru')

@section('content')
<div class="container">
    <h1 class="mb-4">Tambah Rumah Baru</h1>

    <form action="{{ route('agent.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Judul</label>
            <input type="text" name="title" id="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">Harga (Rp)</label>
            <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}" required>
            @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <div class="mb-3">
            <label for="photo" class="form-label">Foto</label>
            <input type="file" name="photo" id="photo" class="form-control @error('photo') is-invalid @enderror" required>
            @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('agent.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection