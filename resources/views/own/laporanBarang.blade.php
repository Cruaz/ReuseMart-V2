@extends('ownerlayout')

@section('title', 'Laporan Barang per Kategori')

@section('content')
<div class="container-fluid">
    <div class="card" style="height: 140vh">
        <div class="card-body p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title h2">Laporan Barang per Kategori</h5>
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
                <table class="table table-bordered mt-4" id="report-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Kategori Barang</th>
                            <th>Jumlah Item Terjual</th>
                            <th>Jumlah Item Gagal Terjual</th>
                            <th>Total per Kategori</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                        <!-- Data akan dimuat di sini -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

<script>
    const { jsPDF } = window.jspdf;
    const token = localStorage.getItem('token');

    function fetchData() {
        const tahun = document.getElementById('tahun').value;
        const tableBody = document.getElementById('table-body');
        
        // Show loading state
        tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Memuat data...</td></tr>';
        
        axios.get(`/api/barang/kategoriLaporan?tahun=${tahun}`, {
            headers: { 'Authorization': `Bearer ${token}` }
        }).then(response => {
            const data = response.data?.data?.data;

            if (!data || !Array.isArray(data) || data.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Tidak ada data yang ditemukan</td></tr>';
                return;
            }

            tableBody.innerHTML = '';

            data.forEach((item, index) => {
                // Baris terakhir adalah total
                const isTotalRow = index === data.length - 1;
                
                tableBody.innerHTML += `
                    <tr ${isTotalRow ? 'class="table-active fw-bold"' : ''}>
                        <td>${item.kategori}</td>
                        <td>${item.terjual}</td>
                        <td>${item.gagal}</td>
                        <td>${item.total}</td>
                    </tr>
                `;
            });
        }).catch(error => {
            console.error('Gagal ambil data:', error);
            tableBody.innerHTML = '<tr><td colspan="4" class="text-center">Gagal memuat data</td></tr>';
        });
    }

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
        doc.text("ReUse Mart", 20, 15);
        doc.setFontSize(10);
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 20, 22);

        // Judul Laporan
        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.text("LAPORAN BARANG PER KATEGORI", 20, 32);
        doc.setLineWidth(0.5);
        doc.line(20, 34, 280, 34);

        // Informasi Tahun dan Tanggal Cetak
        doc.setFont(undefined, 'normal');
        doc.text(`Tahun : ${tahun}`, 20, 40);
        doc.text(`Tanggal cetak: ${currentDate}`, 20, 47);

        // Persiapan Data Tabel
        const tableRows = [];
        const rows = document.querySelectorAll('#table-body tr');
        
        rows.forEach((row, index) => {
            const cells = row.querySelectorAll('td');
            const isTotalRow = index === rows.length - 1;
            
            tableRows.push([
                { content: cells[0].textContent, styles: isTotalRow ? { fontStyle: 'bold' } : {} },
                { content: cells[1].textContent, styles: isTotalRow ? { fontStyle: 'bold' } : {} },
                { content: cells[2].textContent, styles: isTotalRow ? { fontStyle: 'bold' } : {} },
                { content: cells[3].textContent, styles: isTotalRow ? { fontStyle: 'bold' } : {} }
            ]);
        });

        // Membuat Tabel
        doc.autoTable({
            startY: 55,
            head: [
                ['Kategori Barang', 'Jumlah Item Terjual', 'Jumlah Item Gagal Terjual', 'Total per Kategori']
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
                halign: 'center',
                valign: 'middle'
            },
            theme: 'grid',
            margin: { left: 10 }
        });

        // Footer
        doc.setFontSize(8);
        doc.setTextColor(150);
        doc.text("Dicetak oleh Sistem ReUse Mart", 20, doc.internal.pageSize.height - 10);

        // Simpan PDF
        doc.save(`Laporan_Barang_Kategori_${tahun}.pdf`);
    }

    // Load data saat halaman pertama kali dibuka
    document.addEventListener('DOMContentLoaded', fetchData);
</script>

<style>
    .table-active {
        background-color: #f8f9fa;
    }
    .fw-bold {
        font-weight: bold;
    }
</style>
@endsection