@extends('ownerlayout')

@section('title', 'Laporan Stok Gudang')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold fs-2">Laporan Stok Gudang</h5>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="tahun" class="form-label">Tahun</label>
                    <select class="form-select" id="tahun">
                        @for($i = date('Y'); $i >= 2020; $i--)
                            <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>{{ $i }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary mt-3" onclick="fetchData()">Tampilkan Data</button>
                    <button class="btn btn-info text-white mt-3 ms-2" onclick="cetakLaporan()">
                        <i class="fa-solid fa-file-pdf me-2"></i>Cetak Laporan
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-4" id="report-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>ID Penitip</th>
                            <th>Nama Penitip</th>
                            <th>Tanggal Masuk</th>
                            <th>Perpanjangan</th>
                            <th>ID Hunter</th>
                            <th>Nama Hunter</th>
                            <th>Harga</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-end" id="pagination"></ul>
            </nav>

            <div class="alert alert-info mt-4">
                <i class="fa-solid fa-circle-info me-2"></i>
                <strong>Catatan:</strong> Stok yang ditampilkan adalah stok per tahun yang dipilih.
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const { jsPDF } = window.jspdf;
    const token = localStorage.getItem('token');
    let currentPage = 1;
    const perPage = 10;

    // Format currency
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    // Format date
    const formatDate = (dateString) => {
        if (!dateString) return '-';
        const options = { day: '2-digit', month: '2-digit', year: 'numeric' };
        return new Date(dateString).toLocaleDateString('id-ID', options);
    };

    // Fetch data from API
    function fetchData(page = 1, itemsPerPage = perPage) {
        const tahun = document.getElementById('tahun').value;
        currentPage = page;
        
        axios.get(`/api/barang/stok?page=${page}&per_page=${itemsPerPage}&tahun=${tahun}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            const paginatedData = response.data.data;

            if (!paginatedData || !Array.isArray(paginatedData.data)) {
                console.error('Data tidak valid:', paginatedData);
                showError('Tidak ada data stok untuk tahun yang dipilih');
                return;
            }

            renderTable(paginatedData);
            renderPagination(paginatedData);
        }).catch(error => {
            console.error('Error fetching data:', error);
            showError('Gagal memuat data stok');
        });
    }

    // Render table data
    function renderTable(data) {
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';

        if (data.data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data stok untuk tahun yang dipilih</td>
                </tr>
            `;
            return;
        }

        data.data.forEach((barang, index) => {
            const no = (data.current_page - 1) * perPage + index + 1;
            const penitipanBarang = barang.penitipan_barang?.[0];
            const penitipan = penitipanBarang?.penitipan;
            const penitip = penitipan?.penitip;
            const hunter = penitipan?.hunter;
            const perpanjangan = penitipanBarang?.perpanjangan ? 'Ya' : 'Tidak';

            tableBody.innerHTML += `
                <tr>
                    <td>${barang?.nama_barang ? barang.nama_barang.charAt(0).toUpperCase() : '-'}${barang?.id_barang || '-'}</td>
                    <td>${barang.nama_barang || '-'}</td>
                    <td>T${penitip?.id_penitip || '-'}</td>
                    <td>${penitip?.username || '-'}</td>
                    <td>${penitipan.tanggal_penitipan}</td>
                    <td class="text-center">${perpanjangan}</td>
                    <td>${hunter?.id_pegawai || '-'}</td>
                    <td>${hunter?.username || '-'}</td>
                    <td>${barang.harga_barang ? formatRupiah(barang.harga_barang) : '-'}</td>
                </tr>
            `;
        });
    }

    // Render pagination
    function renderPagination(data) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (data.last_page <= 1) return;

        // Previous button
        pagination.innerHTML += `
            <li class="page-item ${data.current_page === 1 ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="fetchData(${data.current_page - 1})">
                    &laquo;
                </a>
            </li>
        `;

        // Page numbers
        for (let i = 1; i <= data.last_page; i++) {
            pagination.innerHTML += `
                <li class="page-item ${i === data.current_page ? 'active' : ''}">
                    <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                </li>
            `;
        }

        // Next button
        pagination.innerHTML += `
            <li class="page-item ${data.current_page === data.last_page ? 'disabled' : ''}">
                <a class="page-link" href="#" onclick="fetchData(${data.current_page + 1})">
                    &raquo;
                </a>
            </li>
        `;
    }

    // Export to PDF
    function cetakLaporan() {
        const doc = new jsPDF('landscape');
        const tahun = document.getElementById('tahun').value;
        const currentDate = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        // Header
        doc.setFontSize(14);
        doc.setTextColor(0, 0, 0);
        doc.setFont('helvetica', 'bold');
        doc.text("ReUse Mart", 20, 15);
        doc.setFontSize(10);
        doc.setFont('helvetica', 'normal');
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 20, 22);

        // Title
        doc.setFontSize(12);
        doc.setFont('helvetica', 'bold');
        doc.text("LAPORAN STOK GUDANG", 20, 32);
        doc.setLineWidth(0.5);
        doc.line(20, 34, 280, 34);

        // Info
        doc.setFont('helvetica', 'normal');
        doc.text(`Tahun: ${tahun}`, 20, 40);
        doc.text(`Tanggal Cetak: ${currentDate}`, 20, 47);

        // Prepare table data
        const tableRows = [];
        const headers = [
            'Kode Barang',
            'Nama Barang',
            'ID Penitip',
            'Nama Penitip',
            'Tgl Masuk',
            'Perpanjang',
            'ID Hunter',
            'Nama Hunter',
            'Harga'
        ];
        
        document.querySelectorAll('#table-body tr').forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            tableRows.push([
                cells[0].textContent, // Kode Barang
                cells[1].textContent, // Nama Barang
                cells[2].textContent, // ID Penitip
                cells[3].textContent, // Nama Penitip
                cells[4].textContent, // Tanggal Masuk
                cells[5].textContent, // Perpanjangan
                cells[6].textContent, // ID Hunter
                cells[7].textContent, // Nama Hunter
                cells[8].textContent  // Harga
            ]);
        });

        // Create table
        doc.autoTable({
            startY: 55,
            head: [headers],
            body: tableRows,
            styles: {
                fontSize: 8,
                cellPadding: 3,
                lineWidth: 0.2,
                lineColor: [0, 0, 0],
            },
            headStyles: {
                fillColor: [52, 58, 64],
                textColor: 255,
                halign: 'center',
                valign: 'middle'
            },
            bodyStyles: {
                halign: 'left',
                valign: 'middle'
            },
            columnStyles: {
                0: { halign: 'center', cellWidth: 20 },
                5: { halign: 'center', cellWidth: 15 },
                6: { halign: 'center', cellWidth: 15 }
            },
            margin: { left: 10 }
        });

        // Save PDF
        doc.save(`Laporan_Stok_Gudang_${tahun}.pdf`);
    }

    // Show error message
    function showError(message) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: message,
            confirmButtonColor: '#3085d6',
        });
    }

    // Initial load
    document.addEventListener('DOMContentLoaded', function() {
        fetchData();
    });
</script>

<style>
    .table th {
        vertical-align: middle;
        white-space: nowrap;
    }
    .table td {
        vertical-align: middle;
    }
    .badge {
        font-size: 0.85em;
        padding: 5px 8px;
    }
</style>
@endsection