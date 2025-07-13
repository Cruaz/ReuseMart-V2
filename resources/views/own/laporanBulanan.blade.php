@extends('ownerlayout')

@section('title', 'Laporan Penjualan Bulanan')

@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body p-4 mt-5">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title fw-bold fs-2">Laporan Penjualan Bulanan</h5>
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

            <div class="card mb-4">
                <div class="card-header bg-white">
                    <h6 class="m-0 font-weight-bold text-primary">Grafik Penjualan Bulanan</h6>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height:400px; width:100%">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover mt-4" id="report-table">
                    <thead class="table-dark">
                        <tr>
                            <th>Bulan</th>
                            <th class="text-end">Jumlah Terjual</th>
                            <th class="text-end">Total Penjualan</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body">
                        <!-- Data will be loaded here -->
                    </tbody>
                    <tfoot class="table-dark">
                        <tr>
                            <th>TOTAL</th>
                            <th class="text-end" id="total-barang">0</th>
                            <th class="text-end" id="total-penjualan">Rp0</th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="alert alert-info mt-4">
                <i class="fa-solid fa-circle-info me-2"></i>
                <strong>Catatan:</strong> Laporan menampilkan data penjualan per bulan pada tahun yang dipilih.
            </div>
        </div>
    </div>
</div>

<!-- JavaScript Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const { jsPDF } = window.jspdf;
    const token = localStorage.getItem('token');
    let salesChart;

    // Format currency
    const formatRupiah = (number) => {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(number);
    };

    // Fetch data from API
    function fetchData() {
        const tahun = document.getElementById('tahun').value;
        
        axios.get(`/api/laporan/penjualanBulanan?tahun=${tahun}`, {
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'Authorization': `Bearer ${token}`
            }
        })
        .then(response => {
            if (response.data.success) {
                renderTable(response.data.data);
                renderChart(response.data.data);
            } else {
                showError(response.data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error.response ? error.response.data : error.message);
            showError('Gagal memuat data penjualan');
        });
    }

    // Render table data
    function renderTable(data) {
        const tableBody = document.getElementById('table-body');
        tableBody.innerHTML = '';

        let totalBarang = 0;
        let totalPenjualan = 0;

        Object.entries(data.bulanIndo).forEach(([monthNum, monthName]) => {
            const penjualan = data.dataPenjualan[monthName];
            const jumlahTerjual = penjualan.JUMLAH_TERJUAL;
            const total = penjualan.penjualan_kotor;

            totalBarang += jumlahTerjual;
            totalPenjualan += total;

            tableBody.innerHTML += `
                <tr>
                    <td>${monthName}</td>
                    <td class="text-end">${jumlahTerjual > 0 ? jumlahTerjual : '-'}</td>
                    <td class="text-end">${total > 0 ? formatRupiah(total) : '-'}</td>
                </tr>
            `;
        });

        document.getElementById('total-barang').textContent = totalBarang;
        document.getElementById('total-penjualan').textContent = formatRupiah(totalPenjualan);
    }

    // Render chart
    function renderChart(data) {
        const ctx = document.getElementById('salesChart').getContext('2d');
        
        // Destroy previous chart if exists
        if (salesChart) {
            salesChart.destroy();
        }

        // Siapkan data untuk chart
        const labels = [];
        const chartData = [];

        // Loop melalui bulanIndo untuk menjaga urutan bulan
        Object.entries(data.bulanIndo).forEach(([monthNum, monthName]) => {
            labels.push(monthName);
            chartData.push(data.dataPenjualan[monthName].penjualan_kotor || 0);
        });

        salesChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Total Penjualan',
                    data: chartData,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 4,
                    hoverBackgroundColor: 'rgba(54, 162, 235, 0.9)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp' + value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += formatRupiah(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                }
            }
        });
    }

    // Export to PDF
    function cetakLaporan() {
        const tahun = document.getElementById('tahun').value;
        const currentDate = new Date().toLocaleDateString('id-ID', {
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        });

        // Buat elemen sementara untuk render chart
        const tempCanvas = document.createElement('canvas');
        tempCanvas.width = 800;
        tempCanvas.height = 400;
        const tempCtx = tempCanvas.getContext('2d');
        
        // Clone chart ke canvas sementara
        const originalCanvas = document.getElementById('salesChart');
        tempCtx.drawImage(originalCanvas, 0, 0, tempCanvas.width, tempCanvas.height);
        
        const doc = new jsPDF('landscape');
        
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
        doc.text("LAPORAN PENJUALAN BULANAN", 20, 32);
        doc.setLineWidth(0.5);
        doc.line(20, 34, 280, 34);

        // Info
        doc.setFont('helvetica', 'normal');
        doc.text(`Tahun: ${tahun}`, 20, 40);
        doc.text(`Tanggal Cetak: ${currentDate}`, 20, 47);

        // Tambahkan grafik sebagai gambar
        const chartImage = tempCanvas.toDataURL('image/png');
        doc.addImage(chartImage, 'PNG', 20, 60, 250, 80);

        // Table data
        const tableRows = [];
        const headers = ['Bulan', 'Jumlah Terjual', 'Total Penjualan'];
        
        document.querySelectorAll('#table-body tr').forEach(row => {
            const cells = row.querySelectorAll('td');
            tableRows.push([
                cells[0].textContent,
                cells[1].textContent,
                cells[2].textContent
            ]);
        });

        // Add total row
        tableRows.push([
            'TOTAL',
            document.getElementById('total-barang').textContent,
            document.getElementById('total-penjualan').textContent
        ]);

        // Create table
        doc.autoTable({
            startY: 150,
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
                1: { halign: 'right' },
                2: { halign: 'right' }
            },
            margin: { left: 10 }
        });

        // Save PDF
        doc.save(`Laporan_Penjualan_Bulanan_${tahun}.pdf`);
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
</style>
@endsection