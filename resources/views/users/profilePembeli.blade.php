@extends('pembeliLayout')

@section('title', 'Profile Pembeli')

@section('contentUser')
<div class="container">
    <div class="col-12">
        <div class="card mb-3 w-100 px-5 py-3">
            <div class="row g-0 align-items-center" id="dataProfile">
                <div class="col-md-2">
                    <img src="{{ $pembeli->foto ? asset('storage/Galery/' . $pembeli->foto) : asset('images/null.jpg') }}"
                        class="img-fluid rounded-circle object-fit-cover" style="height:10rem; width:10rem;">
                </div>
                <div class="col-md-10">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title h1">{{ $pembeli->username }}</h5>
                            <a data-bs-toggle="modal" data-bs-target="#modalUpdate" 
                                class="text-center btn btn-info text-white px-4 py-2 update-btn"
                                data-id="{{ $pembeli->id }}"
                                data-username="{{ $pembeli->username }}"
                                data-email="{{ $pembeli->email }}">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit
                            </a>
                        </div>
                        <div class="d-flex flex-wrap" style="width: 25rem;">
                            <p class="card-text mb-0 mt-2 me-4">
                                <i class="fa-solid fa-envelope me-3"></i>{{ $pembeli->email }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-6">
                <div class="card w-100 rounded-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Poin Anda</h5>
                            <h5 class="card-title"><i class="fa-solid fa-star"></i></h5>
                        </div>
                        <h3 class="mb-2 h1 mt-3">{{ $pembeli->poin_pembeli ?? '0' }}</h3>
                        <p class="card-text text-body-secondary mt-3">Semakin banyak transaksi, semakin banyak poin yang Anda kumpulkan</p>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card w-100 rounded-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Riwayat Transaksi</h5>
                            <h5 class="card-title"><i class="fa-solid fa-clock-rotate-left"></i></h5>
                        </div>
                        <h3 class="mb-2 h1 mt-3">Detail</h3>
                        <p class="card-text text-body-secondary mt-3">Lihat riwayat pembelian Anda</p>
                        <a href="#" class="btn btn-info text-white w-100 mt-3" data-bs-toggle="modal" data-bs-target="#historyModal" onclick="fetchHistoryData()">
                            Lihat Detail History
                        </a>
                    </div>
                </div>
            </div>
        </div>


        <div class="d-flex justify-content-between align-items-center mt-4">
            <h5 class="card-title">Alamat Anda</h5>
            <input type="text" id="searchAlamat" class="form-control w-50 ms-3" placeholder="Cari alamat...">
        </div>
        <div class="row mt-4">
            <div class="col-12">
                <div class="card w-100 rounded-4">
                    <div class="card-body p-5">
                        <div class="d-flex justify-content-between">
                            <h5 class="card-title">Alamat Anda</h5>
                            <a href="{{ url('editAlamat') }}" class="btn btn-info text-white px-4 py-2">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Edit Alamat
                            </a>
                        </div>
                        <div class="mt-3" id="alamat-list">
                            @php
                                $alamat = json_decode($pembeli->alamat, true);
                            @endphp
                            
                            @if($alamat && is_array($alamat))
                                @foreach($alamat as $a)
                                    <div class="card mb-3 alamat-item">
                                        <div class="card-body">
                                            <h6 class="card-subtitle mb-2 label">{{ $a['label_alamat'] }}</h6>
                                            <p class="card-text deskripsi">{{ $a['deskripsi_alamat'] }}</p>
                                            @if($a['is_default'])
                                                <span class="badge bg-primary">Alamat Utama</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <p class="card-text">Anda belum memiliki alamat. Silakan tambahkan alamat.</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="submitFormUpdate">
                @csrf
                <input type="hidden" name="id_pembeli" id="update-id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Username</label>
                        <input type="text" class="form-control" name="username" id="update-username">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" class="form-control" name="email" id="update-email">
                    </div>
                    <div class="mb-3">
                        <label>Password</label>
                        <input type="text" class="form-control" name="password" id="update-password">
                    </div>
                    <div class="mb-3">
                        <label>Foto (Kosongkan jika tidak ingin ubah)</label>
                        <input type="file" class="form-control" name="foto">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-info text-white">Simpan</button>
                </div>
            </form>
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
                            <th>ID Alamat</th>
                            <th>Tanggal Transaksi</th>
                            <th>Harga Total Barang</th>
                            <th>Status Transaksi</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Tanggal Lunas</th>
                            <th>Potongan Harga</th>
                            <th>Harga Ongkir</th>
                            <th>Poin Pembeli</th>
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

<script>
    function fetchHistoryData(page = 1) {
        axios.get(`/pembeli/transaksi?page=${page}`)
            .then(response => {
                const data = response.data;
                const tableBody = document.getElementById('table-body');
                tableBody.innerHTML = '';

                data.data.forEach(req => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${req.id_transaksi}</td>
                            <td>${req.id_alamat}</td>
                            <td>${req.tanggal_transaksi}</td>
                            <td>${req.harga_total_barang}</td>
                            <td>${req.status_transaksi}</td>
                            <td>${req.tanggal_pengembalian || '-'}</td>
                            <td>${req.tanggal_lunas || '-'}</td>
                            <td>${req.potongan_harga || 0}</td>
                            <td>${req.harga_ongkir || 0}</td>
                            <td>${req.poin_pembeli || 0}</td>
                        </tr>
                    `;
                });
            })
            .catch(error => {
                console.error('Gagal ambil data:', error);
                toastr.error('Gagal memuat data transaksi');
            });
    }

    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchAlamat');
        const alamatItems = document.querySelectorAll('.alamat-item');

        searchInput.addEventListener('input', function () {
            const keyword = this.value.toLowerCase();

            alamatItems.forEach(item => {
                const deskripsi = item.querySelector('.deskripsi').textContent.toLowerCase();
                const label = item.querySelector('.label').textContent.toLowerCase();
                if (deskripsi.includes(keyword) || label.includes(keyword)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.update-btn')) {
            const btn = e.target.closest('.update-btn');

            document.getElementById('update-id').value = btn.dataset.id;
            document.getElementById('update-username').value = btn.dataset.username;
            document.getElementById('update-email').value = btn.dataset.email;
        }
    });

    document.getElementById('submitFormUpdate').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('update-id').value;

         axios.post(`/pembeli/profile`, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
                'Accept': 'application/json'
            }
        }).then(response => {
            window.location.reload();
        
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalUpdate'));
            modal.hide();

            toastr.success('Data berhasil diperbarui!');
        }).catch(error => {
            if (error.response && error.response.status === 400) {
                const errors = error.response.data.message;
                for (let field in errors) {
                    if (errors.hasOwnProperty(field)) {
                        toastr.error(errors[field][0]);
                    }
                }
            } else if (error.response && error.response.data && error.response.data.message) {
                toastr.error(error.response.data.message);
            } else {
                toastr.error('Terjadi kesalahan saat mengupdate data.');
            }
        });
    });
</script>
@endsection
