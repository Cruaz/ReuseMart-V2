@extends('ownerlayout')

@section('title', 'Laporan Komisi Produk')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold fs-2">Laporan Komisi Bulanan</h5>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="bulan" class="form-label">Bulan</label>
                    <select class="form-select" id="bulan">
                        @foreach(range(1, 12) as $month)
                            <option value="{{ $month }}" {{ $month == date('m') ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $month)->format('F') }}
                            </option>
                        @endforeach
                    </select>
                </div>
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
                            <th>Kode Produk</th>
                            <th>Nama Produk</th>
                            <th>Harga Jual</th>
                            <th>Tanggal Masuk</th>
                            <th>Tanggal Laku</th>
                            <th>Komisi Hunter</th>
                            <th>Komisi ReUse Mart</th>
                            <th>Bonus Penitip</th>
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
                <strong>Catatan:</strong> Laporan menampilkan komisi produk per bulan yang dipilih.
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
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        currentPage = page;
        
        axios.get(`/api/barang/komisiProduk?page=${page}&per_page=${itemsPerPage}&bulan=${bulan}&tahun=${tahun}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            const paginatedData = response.data?.data;

            if (!paginatedData || !Array.isArray(paginatedData.data)) {
                console.error('Data tidak valid:', paginatedData);
                showError('Tidak ada data komisi untuk periode yang dipilih');
                return;
            }

            renderTable(paginatedData);
            renderPagination(paginatedData);
        }).catch(error => {
            console.error('Error fetching data:', error);
            showError('Gagal memuat data komisi');
        });
    }

    // Render table data
    function renderTable(data) {
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';

        if (data.data.length === 0) {
            tableBody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data komisi untuk periode yang dipilih</td>
                </tr>
            `;
            return;
        }

        data.data.forEach((produk, index) => {
            const no = (data.current_page - 1) * perPage + index + 1;

            tableBody.innerHTML += `
                <tr>
                    <td>${produk.kode_produk || 'P-' + produk.id.toString().padStart(3, '0')}</td>
                    <td>${produk.nama_produk || '-'}</td>
                    <td>${produk.harga_jual ? formatRupiah(produk.harga_jual) : '-'}</td>
                    <td>${formatDate(produk.tanggal_masuk)}</td>
                    <td>${formatDate(produk.tanggal_laku)}</td>
                    <td>${produk.komisi_hunter ? formatRupiah(produk.komisi_hunter) : '-'}</td>
                    <td>${produk.komisi_reuse_mart ? formatRupiah(produk.komisi_reuse_mart) : '-'}</td>
                    <td>${produk.bonus_penitip ? formatRupiah(produk.bonus_penitip) : '-'}</td>
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
        const bulan = document.getElementById('bulan').value;
        const tahun = document.getElementById('tahun').value;
        const namaBulan = document.getElementById('bulan').options[document.getElementById('bulan').selectedIndex].text;
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
        doc.text("LAPORAN KOMISI BULANAN", 20, 32);
        doc.setLineWidth(0.5);
        doc.line(20, 34, 280, 34);

        // Info
        doc.setFont('helvetica', 'normal');
        doc.text(`Periode: ${namaBulan} ${tahun}`, 20, 40);
        doc.text(`Tanggal Cetak: ${currentDate}`, 20, 47);

        // Prepare table data
        const tableRows = [];
        const headers = [
            'Kode Produk',
            'Nama Produk',
            'Harga Jual',
            'Tgl Masuk',
            'Tgl Laku',
            'Komisi Hunter',
            'Komisi ReUse Mart',
            'Bonus Penitip'
        ];
        
        document.querySelectorAll('#table-body tr').forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            tableRows.push([
                cells[0].textContent,
                cells[1].textContent,
                cells[2].textContent,
                cells[3].textContent,
                cells[4].textContent,
                cells[5].textContent,
                cells[6].textContent,
                cells[7].textContent
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
                3: { halign: 'right' },
                6: { halign: 'right' },
                7: { halign: 'right' },
                8: { halign: 'right' }
            },
            margin: { left: 10 }
        });

        // Save PDF
        doc.save(`Laporan_Komisi_Produk_${namaBulan}_${tahun}.pdf`);
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