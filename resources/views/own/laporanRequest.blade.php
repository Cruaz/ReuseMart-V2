@extends('ownerlayout')

@section('title', 'Laporan Request Donasi')

@section('content')
<div class="container-fluid">
    <div class="card" style="height: 140vh">
        <div class="card-body p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title h2">Laporan Request Donasi</h5>
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
                            <th>ID Organisasi</th>
                            <th>Nama Organisasi</th>
                            <th>Alamat</th>
                            <th>Tanggal Request</th>
                            <th>Deskripsi Request</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                        <!-- Data akan dimuat di sini -->
                    </tbody>
                </table>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-end" id="pagination"></ul>
            </nav>
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
    let perPage = 10;

    function fetchData(page = 1, itemsPerPage = perPage) {
        const tahun = document.getElementById('tahun').value;
        
        axios.get(`/api/request/unfulfilled?page=${page}&per_page=${itemsPerPage}&tahun=${tahun}`, {
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

            paginatedData.data.forEach(request => {
                tableBody.innerHTML += `
                    <tr>
                        <td>ORG${request.id_organisasi || '-'}</td>
                        <td>${request.organisasi?.username || '-'}</td>
                        <td>${request.organisasi?.alamat_organisasi || '-'}</td>
                        <td>${request.tanggal_request}</td>
                        <td>${request.deskripsi_request || '-'}</td>
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

    function cetakLaporan() {
        const doc = new jsPDF('landscape');
        const tahun = document.getElementById('tahun').value;
        const currentDate = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        doc.setFontSize(14);
        doc.text("ReUse Mart", 20, 15);
        doc.setFontSize(10);
        doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 20, 22);

        doc.setFontSize(12);
        doc.setFont(undefined, 'bold');
        doc.text("LAPORAN REQUEST DONASI", 20, 32);
        doc.setLineWidth(0.5);
        doc.line(20, 34, 280, 34);

        doc.setFont(undefined, 'normal');
        doc.text(`Tahun : ${tahun}`, 20, 40);
        doc.text(`Tanggal cetak: ${currentDate}`, 20, 47);

        const tableRows = [];
        
        document.querySelectorAll('#table-body tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            tableRows.push([
                cells[0].textContent,
                cells[1].textContent,
                cells[2].textContent,
                cells[3].textContent,
                cells[4].textContent,
            ]);
        });

        doc.autoTable({
            startY: 55,
            head: [
                [
                    'ID Organisasi', 
                    'Nama Organisasi', 
                    'Alamat', 
                    'Tanggal Request', 
                    'Deskripsi Request', 
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

        doc.save(`Laporan_Request_Donasi_Belum_Terpenuhi_${tahun}.pdf`);
    }

    document.addEventListener('DOMContentLoaded', fetchData);
</script>
@endsection