@extends('ownerlayout')

@section('title', 'Penitip')

@section('content')
<div class="">
    <div class="container-fluid">
        <div class="card" style="height:80vh;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title h2">List Penitip</h5>
                    </div>
                </div>

                <table class="table w-100 text-center mt-4">
                    <thead>
                        <tr>
                            <th>ID Penitip</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>NIK</th>
                            <th>Foto</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                    </tbody>
                </table>
                <nav class="mt-5">
                    <ul class="pagination justify-content-end" id="pagination"></ul>
                </nav>
            </div>
        </div>
    </div>

    <!-- Modal Laporan Penitip -->
    <div class="modal fade" id="laporanModal" tabindex="-1" aria-labelledby="laporanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="laporanModalLabel">Laporan Penitip</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-4">
                        <div class="col-md-12 text-end">
                            <button class="btn btn-info text-white ms-2" onclick="cetakLaporan()">
                                <i class="fa-solid fa-file-pdf me-2"></i>Cetak Laporan
                            </button>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered mt-4" id="report-table">
                            <thead class="table-dark">
                                <tr>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Tanggal Masuk</th>
                                    <th>Tanggal Laku</th>
                                    <th>Harga Jual Bersih</th>
                                    <th>Bonus Terjual Cepat</th>
                                    <th>Pendapatan</th>
                                </tr>
                            </thead>
                            <tbody class="table-group-divider" id="laporan-body">
                                <!-- Data laporan akan dimuat di sini -->
                            </tbody>
                        </table>
                    </div>

                    <nav class="mt-4">
                        <ul class="pagination justify-content-end" id="laporan-pagination"></ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <script>
        const { jsPDF } = window.jspdf;
        let perPage = 7;
        const token = localStorage.getItem('token');


        function fetchData(page = 1, itemsPerPage = perPage) {
            const searchQuery = document.getElementById('search') ? document.getElementById('search').value : ''; 
            axios.get(`/api/penitip?page=${page}&per_page=${itemsPerPage}&search=${searchQuery}`, {
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

                paginatedData.data.forEach(pn => {
                    tableBody.innerHTML += `
                        <tr>
                            <td>${pn.id_penitip}</td>
                            <td>${pn.username}</td>
                            <td>${pn.email}</td>
                            <td>${pn.nik || '-'}</td>
                            <td><img src="/storage/Galery/${pn.foto}" width="50" height="50"></td>
                            <td>
                                <button class="btn btn-primary text-white btn-sm" onclick="showLaporanModal(${pn.id_penitip}, '${pn.username}')">Tampilkan Laporan</button>
                            </td>
                        </tr>
                    `;
                });

                for (let i = 1; i <= paginatedData.last_page; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${i === paginatedData.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                        </li>
                    `;
                }
            }).catch(error => {
                console.error('Gagal ambil data:', error);
            });
        }

        function showLaporanModal(penitipId, username) {
            currentPenitipId = penitipId;
            document.getElementById('laporanModalLabel').textContent = `Laporan Penitip - ${username} - T${penitipId}`;
            const modal = new bootstrap.Modal(document.getElementById('laporanModal'));
            modal.show();
            fetchLaporanData();
        }

        function fetchLaporanData(page = 1, itemsPerPage = 10) {
            if (!currentPenitipId) return;

            axios.get(`/api/penitip/${currentPenitipId}/laporan?page=${page}&per_page=${itemsPerPage}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            }).then(response => {
                console.log('Laporan data response:', response.data);
                const paginatedData = response.data?.data;

                if (!paginatedData || !Array.isArray(paginatedData.data)) {
                    console.error('Data laporan tidak valid:', paginatedData);
                    showErrorToast('Data laporan tidak valid');
                    return;
                }

                const laporanBody = document.getElementById('laporan-body');
                const laporanPagination = document.getElementById('laporan-pagination');
                laporanBody.innerHTML = '';
                laporanPagination.innerHTML = '';

                if (paginatedData.data.length === 0) {
                    laporanBody.innerHTML = `
                         <tr>
                            <td colspan="7" class="text-center">Tidak ada data laporan</td>
                        </tr>
                    `;
                    return;
                }

                paginatedData.data.forEach(item => {
                    laporanBody.innerHTML += `
                        <tr>
                            <td>${item.nama_barang ? item.nama_barang.charAt(0).toUpperCase() : '-'}${item.kode_barang || '-'}</td>
                            <td>${item.nama_barang}</td>
                            <td>${formatDate(item.tanggal_masuk)}</td>
                            <td>${formatDate(item.tanggal_laku) || '-'}</td>
                            <td>${formatRupiah(item.harga_jual_bersih)}</td>
                            <td>${item.bonus_terjual_cepat ? formatRupiah(item.bonus_terjual_cepat) : '-'}</td>
                            <td>${formatRupiah(item.pendapatan)}</td>
                        </tr>
                    `;
                });

                for (let i = 1; i <= paginatedData.last_page; i++) {
                    laporanPagination.innerHTML += `
                        <li class="page-item ${i === paginatedData.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="fetchLaporanData(${i})">${i}</a>
                        </li>
                    `;
                }
            }).catch(error => {
                console.error('Gagal ambil data laporan:', error);
                showErrorToast('Gagal memuat data laporan');
            });
        }

        function formatDate(dateString) {
            if (!dateString) return null;
            const date = new Date(dateString);
            return date.toLocaleDateString('id-ID');
        }

        function cetakLaporan() {
            if (!currentPenitipId) return;
    
            const doc = new jsPDF('landscape');
            const currentDate = new Date().toLocaleDateString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric'
            });
            const username = document.getElementById('laporanModalLabel').textContent.replace('Laporan Penitip - ', '');

            doc.setFontSize(14);
            doc.text("ReUse Mart", 20, 15);
            doc.setFontSize(10);
            doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 20, 22);

            doc.setFontSize(12);
            doc.setFont(undefined, 'bold');
            doc.text(`LAPORAN PENITIP - ${username}`, 20, 32);
            doc.setLineWidth(0.5);
            doc.line(20, 34, 280, 34);

            doc.setFont(undefined, 'normal');
            doc.text(`Tanggal cetak: ${currentDate}`, 20, 40);

            const tableRows = [];
            
            document.querySelectorAll('#laporan-body tr').forEach(row => {
                const cells = row.querySelectorAll('td');
                tableRows.push([
                    cells[0].textContent,
                    cells[1].textContent,
                    cells[2].textContent,
                    cells[3].textContent,
                    cells[4].textContent,
                    cells[5].textContent,
                    cells[6].textContent,
                ]);
            });

            doc.autoTable({
                startY: 55,
                head: [
                    [
                        'Kode Produk', 
                        'Nama Produk', 
                        'Tanggal Masuk', 
                        'Tanggal Laku', 
                        'Harga Jual Bersih', 
                        'Bonus Terjual Cepat',
                        'Pendapatan'
                    ]
                ],
                body: tableRows,
                styles: {
                    lineWidth: 0.2,
                    lineColor: [0, 0, 0],
                    cellPadding: 3,
                    fontSize: 8,
                },
                headStyles: {
                    fillColor: [52, 58, 64],
                    textColor: 255,
                    halign: 'center'
                },
                bodyStyles: {
                    halign: 'left',
                    valign: 'middle'
                },
                theme: 'grid',
                margin: { left: 10 }
            });

            doc.save(`Laporan_Penitip_${username}.pdf`);
        }

        function formatRupiah(amount) {
            if (!amount) return '-';
            return 'Rp ' + amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchData();
        });
    </script>
@endsection