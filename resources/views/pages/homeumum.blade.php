@extends('layout')

@section('title', 'ReuseMart - Toko Barang Bekas Berkualitas')

@section('content')

<style>
    .card-img-top {
        height: 180px;
        object-fit: cover;
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    .category-icon {
        width: 40px;
        height: 40px;
        object-fit: contain;
    }
    .category-button {
        min-height: 100px; /* Ukuran tinggi seragam */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 500;
    }
    @media (max-width: 576px) {
        .category-button {
            font-size: 0.8rem;
            min-height: 90px;
        }
    }
    .category-button.active {
        transform: scale(1.1);
        box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.3); /* biru lembut */
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
</style>


<div class="">
    <div class="container-fluid border-top border-bottom py-5">
        <div class="container text-center">
            <h5 class="fw-bold mb-4">Kategori</h5>
            <div class="row justify-content-center gx-3 gy-3">
                @php
                    $categories = [
                        ['icon' => '📦', 'name' => 'Semua', 'slug' => 'all'],
                        ['icon' => '📱', 'name' => 'Elektronik & Gadget', 'slug' => 'elektronik'],
                        ['icon' => '👕', 'name' => 'Pakaian & Aksesori', 'slug' => 'pakaian'],
                        ['icon' => '🪑', 'name' => 'Perabotan Rumah Tangga', 'slug' => 'perabotan'],
                        ['icon' => '📚', 'name' => 'Buku, Alat Tulis, & Peralatan Sekolah', 'slug' => 'buku'],
                        ['icon' => '🎮', 'name' => 'Hobi, Mainan, & Koleksi', 'slug' => 'hobi'],
                        ['icon' => '🍼', 'name' => 'Perlengkapan Bayi & Anak', 'slug' => 'bayi'],
                        ['icon' => '🚗', 'name' => 'Otomotif & Aksesori', 'slug' => 'otomotif'],
                        ['icon' => '🌿', 'name' => 'Perlengkapan Taman & Outdoor', 'slug' => 'taman'],
                        ['icon' => '🏢', 'name' => 'Peralatan Kantor & Industri', 'slug' => 'kantor'],
                        ['icon' => '💄', 'name' => 'Kosmetik & Perawatan Diri', 'slug' => 'kecantikan'],
                        // dst...
                    ];
                @endphp

                @foreach ($categories as $category)
                    <div class="col-6 col-sm-4 col-md-2">
                        <a href="{{ $category['slug'] === 'all' ? route('homeumum') : route('kategori.filter', $category['slug']) }}"
                            class="category-button w-100 border text-sm px-2 py-3 rounded bg-white shadow text-decoration-none text-dark text-center d-block
                            {{ ((!isset($slug) && $category['slug'] === 'all') || (isset($slug) && $slug === $category['slug'])) ? 'bg-primary text-blue active' : '' }}">
                            {{ $category['icon'] }}<br>{{ $category['name'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Produk Grid -->
    <div class="container py-4">
        @if(isset($barang) && $barang->count() > 0)
            <div class="row g-4">
                @foreach ($barang as $item)
                    <div class="col-6 col-md-3" data-category="{{ strtolower($item->kategori_barang) }}">
                        <a href="{{ route('barang.detail', $item->id_barang) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 shadow-sm h-100 product-card">
                                <div class="position-relative">
                                    <img src="{{ asset('storage/Barang/' . $item->gambar_barang) }}" 
                                        class="card-img-top" 
                                        alt="{{ $item->nama_barang }}"
                                        onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.jpg') }}'">
                                </div>
                                <div class="card-body p-3">
                                    <h5 class="card-title h6">{{ $item->nama_barang }}</h5>
                                    <p class="card-text fw-bold mb-1">Rp{{ number_format($item->harga_barang, 0, ',', '.') }}</p>
                                    <p class="card-text small text-muted">{{ Str::limit($item->deskripsi_barang ?? 'Tanpa deskripsi', 50) }}</p>
                                    <p class="card-text small text-muted">Berat: {{ $item->berat_barang }} gram</p>

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

                                    <p class="card-text small {{ $warnaStatus }}">
                                        Status garansi: {{ $statusGaransi }}
                                    </p>

                                    @if($item->tanggal_habis_garansi)
                                        <p class="card-text small text-muted">
                                            Berlaku sampai: {{ \Carbon\Carbon::parse($item->tanggal_habis_garansi)->translatedFormat('d F Y') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <div class="d-flex justify-content-center mt-5">
                {{ $barang->links() }}
            </div>
        @else
            <div class="alert alert-info text-center">Tidak ada produk yang tersedia</div>
        @endif
    </div>
</div>
<script>
    const categoryButtons = document.querySelectorAll('.category-button');
    const productCards = document.querySelectorAll('.product-card');

    categoryButtons.forEach(button => {
        button.addEventListener('click', function () {
            const category = this.getAttribute('data-category');

            if (category === 'all') {
                productCards.forEach(card => {
                    card.style.display = 'block';
                });

                const url = new URL(window.location);
                url.searchParams.delete('kategori');
                window.history.pushState({}, '', url);
            } else {
                window.location.href = `?kategori=${category}`;
            }
        });
    });
</script>
@endsection