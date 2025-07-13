@extends('pembeliLayout')

@section('title', 'Konfirmasi Pembayaran - ReuseMart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white p-6 shadow-sm rounded-lg">
        <div class="mb-6">
            <h1 class="text-2xl font-bold">Konfirmasi Pembayaran</h1>
            <p class="text-gray-600">Nomor Transaksi: {{ $transaksi->nomor_transaksi }}</p>
        </div>
        
        <div class="bg-green-50 border-l-4 border-green-400 p-4 mb-6">
            <div class="flex">
                <div class="ml-3">
                    <p class="text-sm text-green-700">
                        Pembayaran Anda sedang diverifikasi. Kami akan mengirimkan notifikasi setelah pembayaran dikonfirmasi.
                    </p>
                </div>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h2 class="text-lg font-semibold mb-4">Detail Pembayaran</h2>
                <div class="space-y-2">
                    <div class="flex justify-between">
                        <span>Total Pembayaran:</span>
                        <span class="font-semibold">Rp{{ number_format($transaksi->harga_total_barang, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Metode Pembayaran:</span>
                        <span>Transfer Bank</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Status:</span>
                        <span class="font-semibold text-yellow-600">Menunggu Verifikasi</span>
                    </div>
                </div>
            </div>
            
            <div>
                <h2 class="text-lg font-semibold mb-4">Bukti Pembayaran</h2>
                <img src="{{ asset('storage/Galery/' . $transaksi->bukti_pembayaran) }}" alt="Bukti Pembayaran" class="w-full h-auto rounded border">
            </div>
        </div>
        
        <div class="mt-3">
            <a href="{{ url('homePembeli') }}" class="btn btn-outline-dark px-5 py-2 text-sm">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection