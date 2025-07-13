@extends('homeGudang')

@section('title', 'Penitipan Barang')

@section('content')
<div class="container-fluid">
    <div class="card" style="max-height: 80vh; overflow-y: auto;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title h2">Daftar Transaksi Penitipan Barang</h5>
            </div>

            <table class="table w-100 text-center mt-4">
                <thead>
                    <tr>
                        <th>ID Penitipan</th>
                        <th>Nama Barang</th> 
                        <th>Tanggal Masuk</th>
                        <th>Masa Penitipan</th>
                        <th>Batas Pengambilan</th>
                        <th>Tanggal Konfirmasi</th>
                        <th>Status Ambil</th>
                        <th>Cetak Nota</th> 
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

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- ... (kode modal dari poin 1) ... -->
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let perPage = 7;
        const token = localStorage.getItem('token');
        let currentEditId = null;

        // Modal HTML (add this right after the opening of the content section)
        const modalHTML = `
        <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Penitipan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="editForm">
                            <input type="hidden" id="edit_id">
                            <div class="mb-3">
                                <label class="form-label">Tanggal Penitipan</label>
                                <input type="datetime-local" class="form-control" id="edit_tanggal" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Masa Penitipan (hari)</label>
                                <input type="number" class="form-control" id="edit_masa" min="1" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn btn-primary" onclick="updatePenitipan()">Simpan</button>
                    </div>
                </div>
            </div>
        </div>`;

        // Add modal to DOM when page loads
        document.addEventListener('DOMContentLoaded', () => {
            document.body.insertAdjacentHTML('beforeend', modalHTML);
            fetchData();
        });

        function fetchData(page = 1, itemsPerPage = perPage) {
            axios.get(`/api/penitipan?page=${page}&per_page=${itemsPerPage}`, {
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

                paginatedData.data.forEach(item => {
                    let tanggalPenitipanDisplay = 'Tidak tersedia';
                    let batasPengambilanDisplay = 'Tidak tersedia';
                    let sisaHariDisplay = 'N/A';
                    let sisaHariClass = '';

                    function normalizeDate(date) {
                        const d = new Date(date);
                        d.setHours(0, 0, 0, 0);
                        return d;
                    }

                    try {
                        const tanggalPenitipan = item.tanggal_penitipan ? new Date(item.tanggal_penitipan) : null;

                        if (tanggalPenitipan) {
                            tanggalPenitipanDisplay = tanggalPenitipan.toISOString().split('T')[0];

                            const batasPengambilan = new Date(tanggalPenitipan.getTime() + (30 * 24 * 60 * 60 * 1000));
                            batasPengambilanDisplay = batasPengambilan.toISOString().split('T')[0];

                            const now = normalizeDate(new Date());
                            const batas = normalizeDate(batasPengambilan);

                            const selisihMs = batas.getTime() - now.getTime();
                            const sisaHari = Math.ceil(selisihMs / (1000 * 60 * 60 * 24));

                            if (sisaHari < 0) {
                                sisaHariDisplay = 'Sudah lewat';
                                sisaHariClass = 'text-danger';
                            } else if (sisaHari <= 3) {
                                sisaHariDisplay = `${sisaHari} hari lagi`;
                                sisaHariClass = 'text-warning';
                            } else {
                                sisaHariDisplay = `${sisaHari} hari lagi`;
                                sisaHariClass = 'text-success';
                            }
                        }
                    } catch (e) {
                        console.error('Gagal memproses tanggal:', e);
                    }

                    const namaBarang = item.barang?.map(b => `
                        <div>
                            <img src="/storage/barang/${b.gambar_barang}" width="50" height="50" style="object-fit:cover;" />
                            <br><small>${b.nama_barang}</small>
                        </div>
                    `).join('') || '<i>Barang tidak tersedia</i>';

                    const tanggalKonfirmasi = item.tanggal_konfirmasi_pengambilan 
                        ? new Date(item.tanggal_konfirmasi_pengambilan).toISOString().split('T')[0]
                        : '<i>Belum dikonfirmasi</i>';

                    const statusAmbil = item.tanggal_konfirmasi_pengambilan
                        ? '<span class="badge bg-success">Diambil Kembali</span>'
                        : '<span class="badge bg-secondary">Belum Diambil</span>';

                    tableBody.innerHTML += `
                        <tr>
                            <td>${item.id_penitipan}</td>
                            <td>${namaBarang}</td>
                            <td>${tanggalPenitipanDisplay}</td>
                            <td class="${sisaHariClass}">${sisaHariDisplay}</td>
                            <td>${batasPengambilanDisplay}</td>
                            <td>${tanggalKonfirmasi}</td>
                            <td>${statusAmbil}</td>
                            <td>
                                <button class="btn btn-primary btn-sm" onclick='cetakNota(${JSON.stringify(item)})'>Cetak Nota</button>
                            </td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="showEditModal(${item.id_penitipan})">Edit</button>
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

        function showEditModal(id) {
            currentEditId = id;
            axios.get(`/api/penitipan/${id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            }).then(response => {
                const data = response.data.data;
                document.getElementById('edit_id').value = data.id_penitipan;
                
                // Format date for datetime-local input
                const tanggal = data.tanggal_penitipan ? new Date(data.tanggal_penitipan) : null;
                if (tanggal) {
                    const offset = tanggal.getTimezoneOffset();
                    const adjustedDate = new Date(tanggal.getTime() - (offset * 60 * 1000));
                    document.getElementById('edit_tanggal').value = adjustedDate.toISOString().slice(0, 16);
                }
                
                document.getElementById('edit_masa').value = data.masa_penitipan;
                
                // Show modal
                const modal = new bootstrap.Modal(document.getElementById('editModal'));
                modal.show();
                
            }).catch(error => {
                console.error('Error:', error);
                alert('Gagal memuat data untuk diedit');
            });
        }

        function updatePenitipan() {
            const formData = {
                tanggal_penitipan: document.getElementById('edit_tanggal').value,
                masa_penitipan: document.getElementById('edit_masa').value
            };
            
            axios.put(`/api/penitipan/${currentEditId}`, formData, {
                headers: { 'Authorization': `Bearer ${token}` }
            }).then(response => {
                // Hide modal
                const modal = bootstrap.Modal.getInstance(document.getElementById('editModal'));
                modal.hide();
                
                // Refresh data
                fetchData();
                
                alert('Data berhasil diperbarui');
            }).catch(error => {
                console.error('Error:', error);
                alert('Gagal memperbarui data');
            });
        }

        async function cetakNota(data) {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            let y = 10;

            doc.setFont('helvetica');
            doc.setFontSize(16);
            doc.setFont(undefined, 'bold');
            doc.text("ReUse Mart", 14, y); y += 8;
            
            doc.setFontSize(10);
            doc.setFont(undefined, 'normal');
            doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 14, y); y += 15;

            const now = new Date();
            const day = now.getDate().toString().padStart(2, '0');
            const month = (now.getMonth() + 1).toString().padStart(2, '0');
            const yearShort = now.getFullYear().toString().slice(-2);
            const notaNumber = `${day}.${month}.${yearShort}01`;
            
            doc.setFontSize(12);
            doc.text(`No Nota                   : ${notaNumber}`, 14, y); y += 8;
            
            const formatDateTime = (dateString) => {
                if (!dateString) return 'Tidak tersedia';
                const date = new Date(dateString);
                if (isNaN(date.getTime())) return 'Tidak valid';
                
                const day = date.getDate().toString().padStart(2, '0');
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const year = date.getFullYear();
                const hours = date.getHours().toString().padStart(2, '0');
                const minutes = date.getMinutes().toString().padStart(2, '0');
                
                return `${day}/${month}/${year} ${hours}:${minutes}`;
            };
            
            doc.text(`Tanggal penitipan             : ${formatDateTime(data.tanggal_penitipan)}`, 14, y); y += 8;
            
            const endDate = new Date(data.tanggal_penitipan);
            endDate.setDate(endDate.getDate() + 30);
            doc.text(`Masa penitipan sampai     : ${endDate.toLocaleDateString('id-ID')}`, 14, y); y += 15;
            
            doc.setFont(undefined, 'bold');
            const penitipId = data.penitip?.id_penitip || 'ID_TIDAK_TERDEFINISI';
            const penitipNama = data.penitip?.nama_penitip || 'Nama Tidak Diketahui';
            doc.text(`Penitip : ${penitipId}/${penitipNama}`, 14, y); y += 7;
            
            doc.setFont(undefined, 'normal');
            doc.text("Perumahan Margonda 2/50", 14, y); y += 7;
            doc.text("Caturtunggal, Depok, Sleman", 14, y); y += 7;
            doc.text("Delivery: Kurir ReUseMart (Cahyono)", 14, y); y += 15;
            
            if (data.barang && data.barang.length > 0) {
                data.barang.forEach((item) => {
                    doc.setFontSize(12);
                    doc.text(`${item.nama_barang} ${item.harga_barang.toLocaleString('id-ID')}`, 14, y);
                    y += 7;
                    
                    if (item.tanggal_habis_garansi) {
                        const garansiDate = new Date(item.tanggal_habis_garansi);
                        const monthNames = ["Januari", "Februari", "Maret", "April", "Mei", "Juni",
                                        "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
                        const garansiText = `Garansi ON ${monthNames[garansiDate.getMonth()]} ${garansiDate.getFullYear()}`;
                        doc.text(garansiText, 14, y);
                        y += 7;
                    }
                    
                    if (item.berat_barang) {
                        doc.text(`Berat barang: ${item.berat_barang} kg`, 14, y);
                        y += 7;
                    }
                    
                    y += 3;
                });
            }
            
            y += 10;
            const pegawaiId = data.id_pegawai || 'P00';
            const pegawaiNama = data.pegawai?.nama_pegawai || 'Nama Pegawai Tidak Diketahui';
            doc.text("Diterima dan QC oleh:", 14, y); y += 7;
            doc.text(`${pegawaiId} - ${pegawaiNama}`, 14, y);
            
            doc.save(`nota_penitipan_${notaNumber}.pdf`);
        }
    </script>
</div>
@endsection
