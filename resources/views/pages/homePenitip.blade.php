@extends('penitipLayout')

@section('title', 'Daftar Penitipan Saya')

@section('content')

<style>
    .card-img-top {
        height: 300px;
        object-fit: cover;
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .barang-card {
        height: 100%;
    }
</style>

<div class="container mt-5">
    <h2 class="mb-4 text-center">Daftar Penitipan Saya</h2>

    {{-- FORM SEARCH --}}
    <form method="GET" action="{{ route('penitipan.daftar') }}" class="mb-4">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="Mencari............" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit">Cari</button>
        </div>
    </form>

    @forelse ($penitipan as $item)
        <div class="card mb-4 shadow-sm">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <div>
                    <strong>ID Penitipan:</strong> {{ $item->id_penitipan }} |
                    <strong>Tanggal:</strong> {{ $item->tanggal_penitipan }} |
                    <strong>Masa Penitipan:</strong> {{ $item->masa_penitipan }} hari |
                    <strong>Batas Pengambilan:</strong> {{ $item->batas_pengambilan ?? '-' }} <br>
                    <strong>Status Konfirmasi:</strong> 
                    @if($item->tanggal_konfirmasi_pengambilan)
                        <span class="badge bg-success">Sudah Konfirmasi ({{ $item->tanggal_konfirmasi_pengambilan }})</span>
                    @else
                        <span class="badge bg-warning text-dark">Belum Konfirmasi</span>
                    @endif
                </div>
                <div>
                    <form method="POST" action="{{ route('penitipan.perpanjang', $item->id_penitipan) }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Perpanjang masa penitipan +30 hari?')">
                            Perpanjang Masa Penitipan
                        </button>
                    </form>

                    @if(!$item->tanggal_konfirmasi_pengambilan)
                    <button class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#confirmForm{{ $item->id_penitipan }}">
                        Konfirmasi Pengambilan
                    </button>
                    @endif
                </div>
            </div>
            <div class="card-body">
                @if ($item->penitipanBarang->isEmpty())
                    <p class="text-muted">Belum ada barang dititipkan.</p>
                @else
                    <div class="row">
                        @foreach ($item->penitipanBarang as $pb)
                            @if ($pb->barang)
                                <div class="col-md-4 mb-3">
                                    <div class="card barang-card h-100">
                                        <img src="{{ asset('storage/Barang/' . $pb->barang->gambar_barang) }}" class="card-img-top" alt="{{ $pb->barang->nama_barang }}">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $pb->barang->nama_barang }}</h5>
                                            <p class="card-text">
                                                <strong>Harga:</strong> Rp{{ number_format($pb->barang->harga_barang, 0, ',', '.') }}<br>
                                                <strong>Garansi Habis:</strong> {{ $pb->barang->tanggal_habis_garansi ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif

                {{-- FORM KONFIRMASI --}}
                <div class="collapse mt-3" id="confirmForm{{ $item->id_penitipan }}">
                    <form method="POST" action="{{ route('penitipan.konfirmasi', $item->id_penitipan) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="tanggal_konfirmasi_pengambilan_{{ $item->id_penitipan }}" class="form-label">Tanggal Konfirmasi Pengambilan</label>
                            <input type="date" class="form-control" id="tanggal_konfirmasi_pengambilan_{{ $item->id_penitipan }}" name="tanggal_konfirmasi_pengambilan" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Kirim Konfirmasi</button>
                    </form>
                </div>

            </div>
        </div>
    @empty
        <div class="alert alert-info text-center">
            Anda belum memiliki data penitipan.
        </div>
    @endforelse
</div>

@endsection
