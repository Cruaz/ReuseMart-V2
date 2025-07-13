@extends('penitipLayout')

@section('title', 'Profile Penitip')

@section('contentUser')
<div class="container">
    <div class="col-12">
        <div class="card mb-3 w-100 px-5 py-3">
            <div class="row g-0 align-items-center" id="dataProfile">
                <div class="col-md-2">
                    <img src="{{ $penitip->foto ? asset('storage/Galery/' . $penitip->foto) : asset('images/null.jpg') }}"
                        class="img-fluid rounded-circle object-fit-cover" style="height:10rem; width:10rem;">
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title h1">{{ $penitip->username }}</h5>
                            <a href="{{ url('Edit') }}" class="text-center btn btn-info text-white px-4 py-2">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit
                            </a>
                        </div>
                        <div class="d-flex flex-wrap" style="width: 25rem;">
                            <p class="card-text mb-0 mt-2 me-4">
                                <i class="fa-solid fa-envelope me-3"></i>{{ $penitip->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
           <div class="col-12 border border-success rounded px-4 py-3 mt-4 bg-light">
                <h5 class="mb-2">
                    <i class="fa-solid fa-wallet me-3"></i>Saldo Akun Anda
                </h5>
                <h3 class="text-success fw-bold">Rp {{ number_format($penitip->saldo ?? 0, 2, ',', '.') }}</h3>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card w-100 rounded-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Poin Performa</h5>
                            <h5 class="card-title"><i class="fa-solid fa-star"></i></h5>
                        </div>
                        <h3 class="mb-2 h1 mt-3">{{ $penitip->poin_performa ?? '0' }}</h3>
                        <p class="card-text text-body-secondary mt-3">Semakin tinggi poin, semakin baik performa Anda</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card w-100 rounded-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Riwayat Penjualan</h5>
                            <h5 class="card-title"><i class="fa-solid fa-clock-rotate-left"></i></h5>
                        </div>
                        <h3 class="mb-2 h1 mt-3">Detail</h3>
                        <p class="card-text text-body-secondary mt-3">Lihat riwayat penjualan barang Anda</p>
                        <a href="#" class="btn btn-info text-white w-100 mt-3" data-bs-toggle="modal" data-bs-target="#historyModal" onclick="fetchHistoryData()">
                            Lihat Detail History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historyModal" tabindex="-1" aria-labelledby="historyModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="historyModalLabel">Daftar Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table w-100 text-center">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Tanggal Transaksi</th>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>Status Transaksi</th>
                            <th>Komisi Anda</th>
                            <th>Total Diterima</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                    </tbody>
                </table>

                <nav class="mt-3">
                    <ul class="pagination justify-content-end" id="pagination"></ul>
                </nav>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    function fetchHistoryData(page = 1) {
        axios.get(`/penitip/transaksi?page=${page}`)
            .then(response => {
                const data = response.data;
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';

                data.data.forEach(transaksi => {
                    transaksi.barang.forEach(barang => {
                        tableBody.innerHTML += `
                            <tr>
                                <td>${transaksi.id_transaksi}</td>
                                <td>${transaksi.tanggal_transaksi}</td>
                                <td>${barang.nama_barang}</td>
                                <td>Rp ${formatRupiah(barang.harga_barang)}</td>
                                <td>${transaksi.status_transaksi}</td>
                                <td>Rp ${formatRupiah(barang.komisi.komisi_hunter + barang.komisi.komisi_reusemart)}</td>
                                <td>Rp ${formatRupiah(barang.total_diterima_penitip)}</td>
                            </tr>
                        `;
                    });
                });

                const pagination = document.getElementById('pagination');
                pagination.innerHTML = '';
                
                if (data.last_page > 1) {
                    for (let i = 1; i <= data.last_page; i++) {
                        pagination.innerHTML += `
                            <li class="page-item ${i === data.current_page ? 'active' : ''}">
                                <a class="page-link" href="#" onclick="fetchHistoryData(${i})">${i}</a>
                            </li>
                        `;
                    }
                }
            })
            .catch(error => {
                console.error('Gagal ambil data:', error);
                toastr.error('Gagal memuat data transaksi');
            });
    }

    function formatRupiah(amount) {
        return new Intl.NumberFormat('id-ID', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(amount);
    }
</script>
@endsection