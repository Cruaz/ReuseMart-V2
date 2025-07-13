@extends('pembeliLayout')

@section('title', 'Detail Barang ReuseMart')

@section('body-class', 'with-navbar-padding')

@section('content')
<style>
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

<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white p-4 shadow-sm rounded-lg">
        <div class="flex flex-col md:flex-row gap-8">
            {{-- Product Images Section --}}
            <div class="w-full md:w-1/2">
                <div class="flex flex-col">
                    <div class="mb-4">
                        <a href="{{url('homePembeli')}}" class="btn btn-outline-dark px-5 py-2 text-sm text-gray-600 hover:text-orange-500 underline">
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

                <div class="w-1/4 text-right">
                    @auth('pembeli')
                        {{-- Formulir Pertanyaan --}}
                        <form action="{{ route('cart.store', ['id_barang' => $item->id_barang]) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id_barang" value="{{ $item->id_barang }}">
                            <button type="submit" class="btn btn-primary px-5 py-2 text-sm text-white hover:bg-orange-600 rounded-md shadow-md">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-6">
                            <p class="text-sm">Silakan <a href="{{ route('login') }}" class="font-semibold underline">login terlebih dahulu</a> untuk mengajukan pertanyaan.</p>
                        </div>
                    @endauth
                </div>
            </div>

            {{-- Diskusi Produk --}}
            <div class="mt-12">
                <h3 class="text-2xl font-bold text-gray-800 mb-3 mt-5">Diskusi Produk</h3>

                @auth('pembeli')
                    {{-- Formulir Pertanyaan --}}
                    <form action="{{ route('diskusi.store', $item->id_barang) }}" method="POST" class="mb-8 space-y-4 bg-gray-50 p-6 rounded-md">
                        @csrf
                        <div>
                            <textarea id="pertanyaan_diskusi" name="pertanyaan_diskusi" rows="3" class="w-full border border-gray-300 rounded-md p-3 text-sm" placeholder="Tulis pertanyaan Anda disini..." required></textarea>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-outline-dark px-5 py-2 text-sm text-gray-600 hover:text-orange-500 rounded-md">Kirim Pertanyaan</button>
                        </div>
                    </form>
                @else
                    <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 rounded-md mb-6">
                        <p class="text-sm">Silakan <a href="{{ route('login') }}" class="font-semibold underline">login terlebih dahulu</a> untuk mengajukan pertanyaan.</p>
                    </div>
                @endauth

                {{-- Daftar Diskusi --}}
                <div class="space-y-6 my-3">
                    @forelse($diskusi as $diskusiItem)
                    
                        <div class="p-4 border rounded-md bg-white shadow-sm my-3">
                            <div class="text-sm text-gray-600 mb-2">
                                <span class="font-semibold text-gray-800">Pembeli {{ $diskusiItem->pembeli->username }}</span>
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