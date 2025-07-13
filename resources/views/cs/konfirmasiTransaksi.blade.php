@extends('cslayout')

@section('title', 'Konfirmasi Pembayaran')

@section('content')
<div class="">
    <div class="container-fluid">
        <div class="card" style="height: 80vh;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title h2">Daftar Transaksi Menunggu Konfirmasi</h5>
                </div>

                <table class="table w-100 text-center mt-4">
                    <thead>
                        <tr>
                            <th>ID Transaksi</th>
                            <th>Nomor Transaksi</th>
                            <th>Pembeli</th>
                            <th>Tanggal Transaksi</th>
                            <th>Harga Total</th>
                            <th>Status Transaksi</th>
                            <th>Poin Spent</th>
                            <th>Poin Pembeli</th>
                            <th>Opsi Pengiriman</th>
                            <th>Bukti Pembayaran</th>
                            <th>Barang</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body"></tbody>
                </table>

                <nav class="mt-4">
                    <ul class="pagination justify-content-end" id="pagination"></ul>
                </nav>
            </div>
        </div>

        <div class="modal fade" id="buktiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img id="modalBuktiImage" src="" class="img-fluid" alt="Bukti Pembayaran">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="konfirmasiModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pembayaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin mengkonfirmasi pembayaran ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-success" id="confirmButton">Konfirmasi</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="cancelModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Batalkan Transaksi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Apakah Anda yakin ingin membatalkan transaksi ini? Bukti pembayaran tidak valid.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-danger" id="cancelButton">Batalkan Transaksi</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="modal fade" id="resultModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="resultModalTitle">Hasil Konfirmasi</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="resultModalBody">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>

<script>
    let perPage = 7;
    const token = localStorage.getItem('token');
    const buktiModal = new bootstrap.Modal(document.getElementById('buktiModal'));
    const konfirmasiModal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
    const cancelModal = new bootstrap.Modal(document.getElementById('cancelModal'));
    const resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
    
    let currentTransactionId = null;

    function fetchData(page = 1, itemsPerPage = perPage) {
        axios.get(`/api/transaksi2?page=${page}&per_page=${itemsPerPage}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            
            const paginatedData = response.data?.data;

            if (!paginatedData || !Array.isArray(paginatedData.data)) {
                console.error('Data tidak valid:', paginatedData);
                return;
            }

            const tableBody = document.getElementById('table-body');
            const pagination = document.getElementById('pagination');
            tableBody.innerHTML = '';
            pagination.innerHTML = '';

            const filteredData = paginatedData.data.filter(req => 
                req.status_transaksi === 'Menunggu Konfirmasi'
            );

            filteredData.forEach(req => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${req.id_transaksi}</td>
                        <td>${req.nomor_transaksi || '-'}</td>
                        <td>${req.username}</td>
                        <td>${req.tanggal_transaksi}</td>
                        <td>Rp${req.harga_total_barang}</td>
                        <td>${req.status_transaksi}</td>
                        <td>${req.poin_spent || 0}</td>
                        <td>${req.poin_pembeli || 0}</td>
                        <td>${req.opsi_pengiriman == 1 ? 'Dikirim Kurir' : 'Diambil Sendiri'}</td>
                        <td>
                            ${req.bukti_pembayaran 
                                ? `<img src="/storage/Galery/${req.bukti_pembayaran}" 
                                    alt="Bukti Pembayaran" 
                                    width="50" 
                                    height="50" 
                                    style="cursor:pointer; object-fit:cover;"
                                    onclick="showBuktiModal('/storage//Galery/${req.bukti_pembayaran}')">`
                                : '-'
                            }
                        </td>
                        <td>
                            ${req.barang.length > 0 
                                ? req.barang.map(b => `
                                    <div style="margin-bottom:5px;">
                                        <img src="/storage/barang/${b.gambar_barang}" 
                                            alt="${b.nama_barang}" 
                                            width="50" 
                                            height="50" 
                                            style="object-fit:cover;"/>
                                        <br/>
                                        <small>${b.nama_barang}</small><br/>
                                        <small><strong>Rp${b.harga_barang}</strong></small>
                                    </div>
                                `).join('')
                                : '-'
                            }
                        </td>
                        <td>
                            <button class="btn btn-success btn-sm" 
                                    onclick="showKonfirmasiModal(${req.id_transaksi})">
                                Konfirm
                            </button>
                            <button class="btn btn-danger btn-sm ms-1" 
                                    onclick="showCancelModal(${req.id_transaksi})">
                                Batalkan
                            </button>
                        </td>
                    </tr>
                `;
            });

            for (let i = 1; i <= paginatedData.last_page; i++) {
                pagination.innerHTML += `
                    <li class="page-item ${i === paginatedData.current_page ? 'active' : ''}">
                        <a class="page-link" href="#" onclick="fetchData(${i}); return false;">${i}</a>
                    </li>
                `;
            }
        }).catch(error => {
            console.error('Gagal ambil data:', error);
        });
    }

    function showBuktiModal(imageSrc) {
        document.getElementById('modalBuktiImage').src = imageSrc;
        buktiModal.show();
    }
    
    function showKonfirmasiModal(idTransaksi) {
        currentTransactionId = idTransaksi;
        konfirmasiModal.show();
    }

    function konfirmasiPembayaran() {
        axios.put(`/api/transaksi/${currentTransactionId}/konfirmasi`, {}, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            konfirmasiModal.hide();
            
            document.getElementById('resultModalTitle').textContent = 'Berhasil';
            document.getElementById('resultModalBody').textContent = 'Pembayaran berhasil dikonfirmasi!';
            resultModal.show();
            
            fetchData();
        }).catch(error => {
            konfirmasiModal.hide();

            document.getElementById('resultModalTitle').textContent = 'Gagal';
            document.getElementById('resultModalBody').textContent = 
                'Gagal mengkonfirmasi pembayaran: ' + (error.response?.data?.message || error.message);
            resultModal.show();
        });
    }

    function showCancelModal(idTransaksi) {
        currentTransactionId = idTransaksi;
        cancelModal.show();
    }

    function cancelTransaction() {
        axios.delete(`/api/transaksi/${currentTransactionId}/cancel`, {}, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            cancelModal.hide();
            
            document.getElementById('resultModalTitle').textContent = 'Berhasil';
            document.getElementById('resultModalBody').textContent = 'Transaksi berhasil dibatalkan!';
            resultModal.show();
            
            fetchData();
        }).catch(error => {
            cancelModal.hide();

            document.getElementById('resultModalTitle').textContent = 'Gagal';
            document.getElementById('resultModalBody').textContent = 
                'Gagal membatalkan transaksi: ' + (error.response?.data?.message || error.message);
            resultModal.show();
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        fetchData();
        document.getElementById('confirmButton').addEventListener('click', konfirmasiPembayaran);
        document.getElementById('cancelButton').addEventListener('click', cancelTransaction);
    });
</script>
@endsection