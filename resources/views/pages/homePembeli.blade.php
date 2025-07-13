@extends('pembeliLayout')

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
        min-height: 100px;
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
        box-shadow: 0 0.5rem 1rem rgba(0, 123, 255, 0.3);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    body {
        padding-top: 70px;
    }

    .floating-cart {
        position: fixed;
        top: 120px;
        right: 40px;
        z-index: 1050;
        background-color: #fff;
        border-radius: 50%;
        width: 50px;
        height: 50px;
        box-shadow: 0 0 10px rgba(0,0,0,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }
    .floating-cart .badge {
        position: absolute;
        top: -5px;
        right: -5px;
        font-size: 0.7rem;
    }
</style>

<div id="cartIcon" class="floating-cart" data-bs-toggle="modal" data-bs-target="#cartModal">
    🛒
    <span class="badge bg-danger rounded-circle text-white" id="cart-count">0</span>
</div>

<!-- Modal Cart -->
<div class="modal fade" id="cartModal" tabindex="-1" aria-labelledby="cartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Keranjang Belanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
            </div>
            <div class="modal-body" id="cartItems">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Gambar</th>
                                <th>Produk</th>
                                <th>Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="cartTableBody">

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <a href="{{ route('checkout') }}" class="btn btn-success">Checkout</a>
            </div>
        </div>
    </div>
</div>

<div class="">
    <div class="container-fluid py-4" style="background-color: #ffffff;">
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
                        <a href="{{ $category['slug'] === 'all' ? route('homePembeli') : route('kategori.filter.pembeli', $category['slug']) }}"
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
                        <a href="{{ route('pembeli.barang.detail', $item->id_barang) }}" class="text-decoration-none text-dark">
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
<script>
    $(document).ready(function() {
        function loadCart() {
            const idPembeli = {{ auth()->guard('pembeli')->user()->id_pembeli ?? 'null' }};

            $.ajax({
                url: `/api/cart?id_pembeli=${idPembeli}`,
                type: 'GET',
                success: function(response) {
                    updateCartUI(response.data.data);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                }
            });
        }

        function updateCartUI(cartItems) {
            const cartTableBody = $('#cartTableBody');
            const cartCount = $('#cart-count');
            
            cartTableBody.empty();
            
            if (cartItems.length === 0) {
                cartTableBody.append('<tr><td colspan="4" class="text-center">Keranjang belanja kosong</td></tr>');
                cartCount.text('0');
                return;
            }
            
            cartCount.text(cartItems.length);
            
            cartItems.forEach(item => {
                const barang = item.barang || {};
                const deskripsi = barang.deskripsi_barang ? barang.deskripsi_barang.substring(0, 50) : 'Tanpa deskripsi';
                const gambarSrc = barang.gambar_barang ? `{{ asset('storage/Barang/') }}/${barang.gambar_barang}` : '{{ asset('images/placeholder-product.jpg') }}';
                
                const row = `
                    <tr>
                        <td>
                            <img src="${gambarSrc}" 
                                 alt="${barang.nama_barang || 'Produk'}" 
                                 style="width: 60px; height: 60px; object-fit: cover;"
                                 onerror="this.onerror=null;this.src='{{ asset('images/placeholder-product.jpg') }}'">
                        </td>
                        <td>
                            <h6>${barang.nama_barang || 'Produk'}</h6>
                            <p class="text-muted small">${deskripsi}...</p>
                        </td>
                        <td>Rp${barang.harga_barang ? new Intl.NumberFormat('id-ID').format(barang.harga_barang) : '0'}</td>
                        <td>
                            <button class="btn btn-danger btn-sm remove-from-cart" data-id="${item.id_cart}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;
                cartTableBody.append(row);
            });
        }

        $('#cartModal').on('show.bs.modal', function() {
            loadCart();
        });

        $(document).on('click', '.remove-from-cart', function() {
            const cartId = $(this).data('id');
            
            $.ajax({
                url: `/api/cart/${cartId}`,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'DELETE'
                },
                success: function(response) {
                    loadCart();
                    toastr.success(response.message);
                },
                error: function(xhr) {
                    console.error(xhr.responseText);
                    toastr.error('Gagal menghapus item dari keranjang');
                }
            });
        });

        loadCart();
    });
</script>
@endsection