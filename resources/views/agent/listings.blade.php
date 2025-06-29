@extends('layouts.app') {{-- pastikan ini file app.blade.php yang benar --}}

@section('title', 'Daftar Rumah')

@section('content')
<div class="container">
    <h1 class="mb-4">Daftar Rumah Anda</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($houses->isEmpty())
        <p>Tidak ada rumah yang terdaftar.</p>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Judul</th>
                    <th>Harga</th>
                    <th>Lokasi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($houses as $house)
                    <tr>
                        <td>{{ $house->title }}</td>
                        <td>Rp{{ number_format($house->price, 0, ',', '.') }}</td>
                        <td>{{ $house->location }}</td>
                        <td>
                            <a href="{{ route('agent.edit', $house->id) }}" class="btn btn-sm btn-primary">Edit</a>

                            <form action="{{ route('agent.listings.destroy', $listing->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>

                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
