@extends('penitipLayout')

@section('title', 'Riwayat Transaksi Penitip')

@section('contentUser')
<div class="container">
    <h1 class="my-4">Riwayat Transaksi Barang Terjual</h1>
        <div class="alert alert-warning d-flex align-items-center" role="alert">
            <i class="fa-solid fa-circle-exclamation me-3"></i>
            Belum ada data history transaksi penitip.
        </div>
</div>
@endsection