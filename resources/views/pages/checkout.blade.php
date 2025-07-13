@extends('pembeliLayout')

@section('body-class', 'with-navbar-padding')

@section('title', 'Checkout - ReuseMart')

@section('content')
<style>
    #paymentProofContainer {
        display: none;
    }
    #countdownContainer {
        display: none;
    }
</style>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto bg-white p-4 shadow-sm rounded-lg">
        <div class="mb-4">
            <a href="{{url('homePembeli')}}" class="btn btn-outline-dark px-5 py-2 text-sm text-gray-600 hover:text-orange-500 underline">
                Kembali
            </a>
        </div>
        <h1 class="text-2xl font-bold mb-6">Pesanan Saya ({{ $cartItems ? count($cartItems) : 0 }})</h1>
        
        @if(!$cartItems || count($cartItems) === 0)
            <div class="text-center py-8">
                <p class="text-gray-500 mb-4">Keranjang belanja Anda kosong</p>
                <a href="{{url('homePembeli')}}" class="btn btn-outline-dark px-5 py-2 text-sm text-gray-600 hover:text-orange-500 underline">
                    Lanjutkan Belanja
                </a>
            </div>
        @else
            <!-- Daftar Produk -->
            <div class="grid grid-cols-1 gap-4 mb-8">
                @foreach($cartItems as $item)
                @php
                    $barang = $item->barang ?? null;
                    $deskripsi = $barang->deskripsi_barang ? substr($barang->deskripsi_barang, 0, 50) : 'Tanpa deskripsi';
                    $gambarSrc = $barang->gambar_barang ? asset('storage/Barang/'.$barang->gambar_barang) : asset('images/placeholder-product.jpg');
                @endphp
                <div class="row mb-4">
                    <div class="col-12 border rounded shadow-sm p-3">
                        <div class="d-flex justify-content-between align-items-start gap-3">
                            <img src="{{ $gambarSrc }}" class="img-fluid" style="width: 128px; height: 128px; object-fit: cover;" alt="...">
                            <div class="flex-grow-1">
                                <h5 class="fw-bold mb-1">{{ $barang->nama_barang }}</h5>
                                <p class="text-muted small mb-1">{{ $deskripsi }}...</p>
                                <p class="text-danger fw-semibold mb-0">Rp{{ number_format($barang->harga_barang ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="my-auto">
                                <button class="btn btn-danger btn-sm remove-from-cart" data-id="{{ $item->id_cart }}">
                                    <i class="fas fa-trash"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mb-4">
                <label class="fw-bold mb-2">Metode Pengiriman:</label>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="opsi_pengiriman" id="kurir" value="1" checked>
                    <label class="form-check-label" for="kurir">
                        Diantar Kurir
                    </label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="opsi_pengiriman" id="ambil_sendiri" value="0">
                    <label class="form-check-label" for="ambil_sendiri">
                        Ambil Sendiri
                    </label>
                </div>
            </div>

            <!-- Ringkasan Belanja -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-4">Ringkasan Belanja</h2>
                
                <div class="flex justify-between mb-2">
                    <span>Total</span>
                    <span class="font-semibold">Rp{{ number_format($totalHarga, 0, ',', '.') }}</span>
                </div>
                
                <div id="shippingCostContainer" class="flex justify-between mb-2">
                    <span>Ongkos Kirim</span>
                    <span id="shippingCostValue">
                        @if($ongkir > 0)
                            + Rp{{ number_format($ongkir, 0, ',', '.') }}
                        @else
                            Gratis
                        @endif
                    </span>
                </div>
                
                <div class="mb-3">
                    <div class="flex justify-between items-center mb-1">
                        <span>Poin Saya: <span id="currentPoints">{{ $pembeli->poin_pembeli }}</span></span>
                        <span class="text-green-600">Akan dapat +{{ $totalPoin }} poin</span>
                    </div>
                    <div class="flex items-center gap-2 my-2">
                        <input type="number" id="redeemPoints" name="redeem_points" 
                            class="form-control w-32" placeholder="Poin ditukar" 
                            min="0" max="{{ $pembeli->poin_pembeli }}">
                        <button type="button" id="applyPointsBtn" class="btn btn-sm btn-outline-primary mt-2">
                            Tukar
                        </button>
                    </div>
                    <small class="text-muted">1 poin = Rp1.000</small>
                    <div id="remainingPointsContainer" class="mt-1" style="display: none;">
                        <small class="text-success">Poin Tersisa: <span id="remainingPoints">0</span></small>
                    </div>
                </div>
                
                <hr class="my-4">
                
                <div class="flex justify-between font-bold text-lg">
                    <span>Total Pembayaran</span>
                    <span id="totalPayment">Rp{{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                </div>
                
                <form id="checkoutForm" action="{{ route('checkout.process') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="opsi_pengiriman" id="opsiPengirimanInput" value="1">
                    <input type="hidden" name="total_harga" id="formTotalHarga" value="{{ $totalHarga }}">
                    <input type="hidden" name="ongkir" id="formOngkir" value="{{ $ongkir }}">
                    <input type="hidden" name="poin_ditukar" id="formPoinDitukar" value="0">
                    <input type="hidden" name="total_pembayaran" id="formTotalPembayaran" value="{{ $totalPembayaran }}">
                    <input type="hidden" name="total_poin" id="formTotalPoin" value="{{ $totalPoin }}">

                    <div id="alamatContainer" class="mt-4">
                        <h6 class="mb-3 fw-semibold">Pilih Alamat Pengiriman:</h6>
                        @php
                            $alamat = $pembeli->alamat ? json_decode($pembeli->alamat, true) : [];
                        @endphp

                        @if($alamat && is_array($alamat))
                            @foreach($alamat as $index => $a)
                                <div class="card mb-2 alamat-item">
                                    <div class="card-body">
                                        <div class="form-check">
                                            <input
                                            type="radio"
                                            name="id_alamat"
                                            value="{{ $a['id_alamat'] }}"
                                            class="form-check-input"
                                            id="alamat-{{ $a['id_alamat'] }}"
                                            {{ $a['is_default'] ? 'checked' : '' }}
                                            >
                                            <label class="form-check-label" for="alamat-{{ $a['id_alamat'] }}">
                                                <h6 class="card-subtitle mb-1">{{ $a['label_alamat'] }}</h6>
                                                <p class="mb-0 text-muted">{{ $a['deskripsi_alamat'] }}</p>
                                                @if($a['is_default'])
                                                    <span class="badge bg-primary">Alamat Utama</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p>Belum ada alamat. Silakan tambahkan di profil.</p>
                        @endif
                    </div>
                    
                    <div id="paymentProofContainer" class="mb-4 mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Upload Bukti Pembayaran</label>
                        <input type="file" name="bukti_pembayaran" id="bukti_pembayaran" class="block w-full text-sm text-gray-500
                            file:mr-4 file:py-2 file:px-4
                            file:rounded-md file:border-0
                            file:text-sm file:font-semibold
                            file:bg-blue-50 file:text-blue-700
                            hover:file:bg-blue-100" required>
                        <p class="mt-1 text-sm text-gray-500">Format: JPG, PNG</p>
                    </div>
                    
                    <div id="countdownContainer" class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                        <div class="flex">
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Selesaikan pembayaran dalam: 
                                    <span id="countdown" class="font-bold">01:00</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <button type="button" id="paymentButton" class="btn btn-success px-5 py-2 text-sm text-white hover:bg-orange-600 rounded-md shadow-md mt-3 w-full">
                        Bayar Sekarang
                    </button>
                    
                    <button type="submit" id="confirmButton" class="btn btn-primary px-5 py-2 text-sm text-white hover:bg-orange-600 rounded-md shadow-md mt-3 w-full" style="display: none;">
                        Konfirmasi Pembayaran
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
    $(document).on('click', '.remove-from-cart', function () {
        const cartId = $(this).data('id');

        $.ajax({
            url: `/api/cart/${cartId}`,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'DELETE'
            },
            success: function (response) {
                toastr.success(response.message);
                $(this).closest('.flex').remove();
                location.reload();
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                toastr.error('Gagal menghapus item dari keranjang');
            }
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const alamatContainer = document.getElementById('alamatContainer');
        const radioKurir = document.getElementById('kurir');
        const radioAmbilSendiri = document.getElementById('ambil_sendiri');

        function toggleAlamat() {
            if (radioKurir.checked) {
                alamatContainer.style.display = 'block';
                document.querySelectorAll('input[name="id_alamat"]').forEach(r => {
                    r.disabled = false;
                });

            } else {
                alamatContainer.style.display = 'none';
                document.querySelectorAll('input[name="id_alamat"]').forEach(r => {
                    r.disabled = true;
                });
            }
        }

        toggleAlamat();

        radioKurir.addEventListener('change', toggleAlamat);
        radioAmbilSendiri.addEventListener('change', toggleAlamat);
    });

    document.addEventListener('DOMContentLoaded', function() {
        const shippingMethodRadios = document.querySelectorAll('input[name="opsi_pengiriman"]');
        const shippingCostContainer = document.getElementById('shippingCostContainer');
        const totalPaymentElement = document.getElementById('totalPayment');
        const currentPointsElement = document.getElementById('currentPoints');
        const redeemPointsInput = document.getElementById('redeemPoints');
        const applyPointsBtn = document.getElementById('applyPointsBtn');
        const paymentButton = document.getElementById('paymentButton');
        const confirmButton = document.getElementById('confirmButton');
        const countdownContainer = document.getElementById('countdownContainer');
        const countdownElement = document.getElementById('countdown');
        const paymentProofContainer = document.getElementById('paymentProofContainer');
        const checkoutForm = document.getElementById('checkoutForm');

        const baseTotal = {{ $totalHarga }};
        const shippingCost = {{ $ongkir }};
        const maxRedeemablePoints = {{ $pembeli->poin_pembeli ?? 0 }};
        const pointValue = 1000;

        let currentTotal = baseTotal + shippingCost;
        let pointsUsed = 0;
        let countdownInterval;
        let duration = 60;

        paymentProofContainer.style.display = 'none';
        countdownContainer.style.display = 'none';

        shippingMethodRadios.forEach(radio => {
            radio.addEventListener('change', function(e) {
                e.preventDefault();
                const selectedShipping = document.querySelector('input[name="opsi_pengiriman"]:checked');
                document.getElementById('opsiPengirimanInput').value = selectedShipping ? selectedShipping.value : '1';
                if (this.value === '1') {
                    shippingCostContainer.style.display = 'flex';
                    currentTotal = baseTotal + shippingCost - (pointsUsed * pointValue);
                } else {
                    shippingCostContainer.style.display = 'none';
                    currentTotal = baseTotal - (pointsUsed * pointValue);
                }
                updateTotalPayment();
            });
        });

        applyPointsBtn.addEventListener('click', function() {
            const pointsToRedeem = parseInt(redeemPointsInput.value) || 0;

            if (pointsToRedeem < 0) {
                toastr.warning(`Poin tidak boleh negatif`);
                redeemPointsInput.value = 0;
                return;
            }

            if (pointsToRedeem > maxRedeemablePoints) {
                toastr.warning(`Anda hanya memiliki ${maxRedeemablePoints} poin`);
                redeemPointsInput.value = maxRedeemablePoints;
                return;
            }

            pointsUsed = pointsToRedeem;
            const discount = pointsUsed * pointValue;

            const shippingSelected = document.querySelector('input[name="opsi_pengiriman"]:checked').value === '1';
            currentTotal = baseTotal + (shippingSelected ? shippingCost : 0) - discount;

            updateTotalPayment();

             const remainingPoints = maxRedeemablePoints - pointsUsed;
            document.getElementById('remainingPoints').textContent = remainingPoints;
            document.getElementById('remainingPointsContainer').style.display = 'block';

            document.getElementById('formPoinDitukar').value = pointsUsed;
        });

        function updateTotalPayment() {
            totalPaymentElement.textContent = 'Rp' + new Intl.NumberFormat('id-ID').format(currentTotal > 0 ? currentTotal : 0);
        }

        if (document.querySelector('input[name="opsi_pengiriman"]:checked').value === '1') {
            shippingCostContainer.style.display = 'flex';
        }

        function startCountdown() {
            duration = 60;
            updateCountdownDisplay();
            
            countdownInterval = setInterval(() => {
                duration--;
                updateCountdownDisplay();
                
                if (duration <= 0) {
                    clearInterval(countdownInterval);
                    cancelTransaction();
                }
            }, 1000);
        }

        function updateCountdownDisplay() {
            let minutes = String(Math.floor(duration / 60)).padStart(2, '0');
            let seconds = String(duration % 60).padStart(2, '0');
            countdownElement.textContent = `${minutes}:${seconds}`;
        }

        function cancelTransaction() {
            fetch('{{ route("cancel.transaction") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    id_pembeli: {{ auth('pembeli')->id() }}
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    toastr.warning('Transaksi dibatalkan karena waktu pembayaran habis');
                    resetPaymentUI();
                    setTimeout(() => location.reload(), 2000);
                }
            });
        }

        function resetPaymentUI() {
            paymentProofContainer.style.display = 'none';
            countdownContainer.style.display = 'none';
            paymentButton.style.display = 'block';
            confirmButton.style.display = 'none';
            clearInterval(countdownInterval);
        }

        paymentButton.addEventListener('click', function() {
            paymentButton.disabled = true;

            const shippingSelected = document.querySelector('input[name="opsi_pengiriman"]:checked').value === '1';
            const ongkir = shippingSelected ? {{ $ongkir }} : 0;
    
            const formData = {
                id_alamat: document.querySelector('input[name="id_alamat"]:checked')?.value || null,
                opsi_pengiriman: document.querySelector('input[name="opsi_pengiriman"]:checked').value,
                total_pembayaran: currentTotal > 0 ? currentTotal : 0,
                harga_ongkir: ongkir,
                total_poin: {{ $totalPoin }},
                poin_ditukar: pointsUsed,
                _token: '{{ csrf_token() }}'
            };

            fetch('{{ route("checkout.process") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    paymentProofContainer.style.display = 'block';
                    countdownContainer.style.display = 'block';
                    paymentButton.style.display = 'none';
                    confirmButton.style.display = 'block';
                    
                    const transactionIdInput = document.createElement('input');
                    transactionIdInput.type = 'hidden';
                    transactionIdInput.name = 'transaction_id';
                    transactionIdInput.value = data.transaction_id;
                    checkoutForm.appendChild(transactionIdInput);

                    startCountdown();
                } else {
                    toastr.error(data.error || 'Gagal memproses pembayaran');
                    paymentButton.disabled = false;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                toastr.error('Terjadi kesalahan saat memproses pembayaran');
                paymentButton.disabled = false;
            });
        });

        confirmButton.addEventListener('click', function() {
            const buktiInput = document.getElementById('bukti_pembayaran');
            if (!buktiInput.files || buktiInput.files.length === 0) {
                toastr.error('Harap upload bukti pembayaran terlebih dahulu');
                return;
            }
            
            clearInterval(countdownInterval);
            
            checkoutForm.action = '{{ route("checkout.upload-proof") }}';
            checkoutForm.method = 'POST';
            checkoutForm.enctype = 'multipart/form-data';
            
            checkoutForm.submit();
        });
    });
</script>
@endsection