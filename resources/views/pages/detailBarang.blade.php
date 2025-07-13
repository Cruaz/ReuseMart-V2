@extends('layout')

@section('title', 'Detail Barang ReuseMart')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white p-4 shadow-sm rounded-lg">
        <div class="flex flex-col md:flex-row gap-8">
            {{-- Product Images Section --}}
            <div class="w-full md:w-1/2">
                <div class="flex flex-col">
                    <div class="mb-4">
                        <a href="{{url('homeumum')}}" class="btn btn-outline-dark px-5 py-2 text-sm text-gray-600 hover:text-orange-500 underline">
                            Kembali
                        </a>
                    </div>
                    {{-- Main Product Image --}}
                    <div class="mb-4 border rounded-md">
                        <img 
                            src="{{ asset('storage/Barang/' . $item->gambar_barang) }}" 
                            alt="{{ $item->nama_barang }}"
                            class="w-full h-96 object-contain"
                            onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.jpg') }}'"
                        >
                    </div>

                    {{-- Additional Images (if available) --}}
                    @php
                        $additionalImages = $item->additional_images ?? [];
                    @endphp
                    @if(count($additionalImages) > 0)
                        <div class="flex gap-2 overflow-x-auto">
                            @foreach($additionalImages as $index => $image)
                                <div class="border p-1 rounded-md cursor-pointer hover:border-orange-500">
                                    <img 
                                        src="{{ asset('storage/images/barang/' . $image) }}" 
                                        alt="Thumbnail {{ $index + 1 }}"
                                        class="w-16 h-16 object-cover" 
                                    >
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                
                {{-- Product Description Section --}}
                <div class="mt-6 border-t pt-4">
                    <h3 class="text-lg font-semibold mb-3">Deskripsi Produk</h3>
                    <div class="text-sm text-gray-700 space-y-3">
                        <p>{{ $item->deskripsi_barang }}</p>

                        {{-- Kondisi Barang --}}
                        <div class="mt-2">
                            <p class="font-bold">Kondisi: {{ $item->kondisi_barang ?? 'Tidak Disebutkan' }}</p>
                        </div>

                        {{-- Garansi --}}
                        @php
                            $statusGaransi = 'Tidak Bergaransi';
                            $warnaStatus = 'text-muted';

                            if ($item->status_garansi_barang && $item->tanggal_habis_garansi) {
                                $tanggal = \Carbon\Carbon::parse($item->tanggal_habis_garansi);
                                if ($tanggal->isFuture()) {
                                    $statusGaransi = 'Aktif';
                                    $warnaStatus = 'text-success';
                                } else {
                                    $statusGaransi = 'Tidak Aktif';
                                    $warnaStatus = 'text-danger';
                                }
                            }
                        @endphp
                        <div class="mt-2">
                            <p class="font-bold {{ $warnaStatus }}">Status Garansi: {{ $statusGaransi }}</p>
                            @if($item->tanggal_habis_garansi)
                                <p class="text-gray-600">Berlaku sampai: {{ \Carbon\Carbon::parse($item->tanggal_habis_garansi)->translatedFormat('d F Y') }}</p>
                            @endif
                        </div>

                        {{-- Berat dan Dimensi --}}
                        <div class="mt-2">
                            <p>Berat: {{ $item->berat_barang }} gram</p>
                            @if($item->dimensi_barang)
                                <p>Dimensi: {{ $item->dimensi_barang }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Product Details Section --}}
            <div class="w-full md:w-1/2">
                {{-- Product Header --}}
                <div class="mb-4">
                    <h1 class="text-xl font-bold">{{ $item->nama_barang }}</h1>
                </div>

                {{-- Ratings Section --}}
                <div class="flex items-center gap-4 mb-4">
                    <div class="flex items-center">
                        <span class="font-bold text-lg">{{ number_format($item->rating ?? 0, 1) }}</span>
                        <div class="flex ml-1">
                            @php
                                $rating = $item->rating ?? 0;
                                $fullStars = floor($rating);
                                $halfStar = $rating - $fullStars >= 0.5;
                            @endphp
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $fullStars)
                                    <span class="text-yellow-400">★</span>
                                @elseif ($halfStar && $i == $fullStars + 1)
                                    <span class="text-yellow-400">½</span>
                                @else
                                    <span class="text-gray-300">★</span>
                                @endif
                            @endfor
                        </div>
                    </div>
                    <div class="text-gray-500 border-l border-r px-4">
                        <span class="font-bold text-black">{{ $item->total_penilaian ?? 0 }}</span> Penilaian
                    </div>
                </div>

                {{-- Price --}}
                <div class="mb-6">
                    <h2 class="text-3xl font-bold text-orange-500">Rp{{ number_format($item->harga_barang, 0, ',', '.') }}</h2>
                </div>
            </div>

            {{-- Diskusi Produk --}}
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-800 mb-3 mt-5">Diskusi Produk</h3>

                <div class="bg-gray-100 border border-gray-300 text-gray-500 p-4 rounded-md mb-6">
                    <p class="text-sm">Login sebagai pembeli untuk mengajukan pertanyaan.</p>
                </div>

                {{-- Daftar Diskusi --}}
                <div class="space-y-6 my-3">
                    @forelse($diskusi as $diskusiItem)
                        <div class="p-4 border rounded-md bg-white shadow-sm my-3">
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-semibold text-gray-800">{{ $diskusiItem->pembeli->username }}</span>
                            </div>
                            <p class="text-gray-800 text-sm mb-4">{{ $diskusiItem->pertanyaan_diskusi }}</p>

                            @if($diskusiItem->jawaban_diskusi)
                                <div class="bg-gray-100 p-3 rounded-md">
                                    <span class="text-sm font-semibold text-green-700">Jawaban:</span>
                                    <p class="text-sm text-gray-700 mt-1">{{ $diskusiItem->jawaban_diskusi }}</p>
                                </div>
                            @else
                                <div class="bg-gray-50 p-3 rounded-md border border-dashed">
                                    <span class="text-sm text-gray-500 italic">Belum ada jawaban.</span>
                                </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada diskusi pada produk ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const idBarang = {{ $item->id_barang }};
    document.addEventListener('DOMContentLoaded', function() {
        const thumbnails = document.querySelectorAll('.thumbnail-image');
        const mainImage = document.querySelector('.main-product-image');

        thumbnails.forEach(thumbnail => {
            thumbnail.addEventListener('click', function() {
                const newSrc = this.getAttribute('src');
                mainImage.setAttribute('src', newSrc);
                
                thumbnails.forEach(t => t.classList.remove('border-orange-500', 'scale-105'));
                
                this.classList.add('border-orange-500', 'scale-105');
            });
        });
    });
</script>
@endpush