@extends('homeGudang')

@section('title', 'Daftar Transaksi')

@section('content')
<div class="container-fluid">
    <div class="card" style="height: 140vh;">
        <div class="card-body p-4">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="card-title h2">Daftar Transaksi Pengiriman</h5>
            </div>

            <div class="table-responsive" style="width: 100%; overflow-x: auto;">
                <table class="table mt-4" style="width: 100%; min-width: 1500px;">
                    <thead>
                        <tr>
                            <th style="width: 5%;">ID Transaksi</th>
                            <th style="width: 10%;">Deskripsi Alamat</th>
                            <th style="width: 8%;">Email Pembeli</th>
                            <th style="width: 7%;">Nama Pembeli</th>
                            <th style="width: 7%;">Tanggal Transaksi</th>
                            <th style="width: 6%;">Harga Total</th>
                            <th style="width: 6%;">Status</th>
                            <th style="width: 6%;">Opsi Pengiriman</th>
                            <th style="width: 7%;">Tanggal Pengambilan</th>
                            <th style="width: 7%;">Tanggal Lunas</th>
                            <th style="width: 6%;">Potongan</th>
                            <th style="width: 6%;">Ongkir</th>
                            <th style="width: 5%;">Poin</th>
                            <th style="width: 8%;">Penjadwalan</th>
                            <th style="width: 10%;">Barang</th>
                            <th style="width: 6%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-group-divider" id="table-body"></tbody>
                </table>
            </div>

            <nav class="mt-4">
                <ul class="pagination justify-content-end" id="pagination"></ul>
            </nav>
        </div>
    </div>


    <!-- Modal Tambah Jadwal -->
    <div class="modal fade" id="jadwalModal" tabindex="-1" aria-labelledby="jadwalModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="jadwalForm">
                    <div class="modal-header">
                        <h5 class="modal-title" id="jadwalModalLabel">Penjadwalan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="jadwalTransaksiId">
                        <input type="hidden" id="jenisPenjadwalan">
                        <div class="mb-3" id="kurirSelectContainer">
                            <label for="kurirSelect" class="form-label">Pilih Kurir</label>
                            <select id="kurirSelect" class="form-select"></select>
                        </div>
                        <div class="mb-3">
                            <label for="jadwalTanggal" class="form-label">Jadwal</label>
                            <input type="datetime-local" id="jadwalTanggal" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Script CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        let perPage = 7;
        const token = localStorage.getItem('token');

        function fetchData(page = 1, itemsPerPage = perPage) {
            axios.get(`/api/transaksi?page=${page}&per_page=${itemsPerPage}`, {
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
                    req.status_transaksi === 'Disiapkan' ||
                    req.status_transaksi === 'Diambil Sendiri' ||
                    req.status_transaksi === 'Diterima' ||
                    req.status_transaksi === 'Hangus' ||
                    req.status_transaksi === 'Sedang Dikirim'
                );

                filteredData.forEach(req => {
                    let penjadwalanIsi = '-';
                    const needsSchedule = req.status_transaksi === 'Disiapkan' && !req.jadwal_pengiriman;
                    
                    if (req.opsi_pengiriman == 1 && req.pegawai && req.jadwal_pengiriman) {
                        penjadwalanIsi = `${new Date(req.jadwal_pengiriman).toLocaleString('id-ID')} - ${req.pegawai.username}`;
                    } else if (req.opsi_pengiriman == 0 && req.jadwal_pengiriman) {
                        penjadwalanIsi = `${new Date(req.jadwal_pengiriman).toLocaleString('id-ID')} - Diambil Sendiri`;
                    }

                    tableBody.innerHTML += `
                        <tr>
                            <td>${req.id_transaksi}</td>
                            <td style="min-width: 200px; text-align: left;">${req.deskripsi_alamat ?? '-'}</td>
                            <td>${req.pembeli?.email ?? '-'}</td>
                            <td>${req.pembeli?.username ?? '-'}</td>
                            <td style="min-width: 100px; text-align: left;">${req.tanggal_transaksi}</td>
                            <td>Rp${req.harga_total_barang}</td>
                            <td>${req.status_transaksi}</td>
                            <td>${req.opsi_pengiriman == 1 ? 'Dikirim Kurir' : 'Diambil Sendiri'}</td>
                            <td>${req.tanggal_pengambilan || '-'}</td>
                            <td style="min-width: 100px; text-align: left;">${req.tanggal_lunas || '-'}</td>
                            <td>Rp${req.potongan_harga ?? 0}</td>
                            <td>Rp${req.harga_ongkir ?? 0}</td>
                            <td>${req.poin_pembeli ?? 0}</td>
                            <td>
                                ${needsSchedule 
                                    ? `<button class="btn btn-warning btn-sm" onclick='bukaModalJadwal(${JSON.stringify(req)})'>
                                        ${req.opsi_pengiriman == 1 ? 'Tambah Jadwal & Kurir' : 'Tambah Jadwal Pengambilan'}
                                       </button>`
                                    : penjadwalanIsi
                                }
                            </td>
                            <td>
    ${req.barang.length > 0 
        ? req.barang.map(b => {
            let statusTampil = '';
            let statusColor = '';

            if (req.status_transaksi === 'Sedang Dikirim') {
                statusTampil = 'Terjual';
                statusColor = 'green';
            } else if (req.status_transaksi === 'Diambil Sendiri' || req.status_transaksi === 'Diterima') {
                statusTampil = 'Diambil';
                statusColor = 'blue';
            }

            return `
                <div style="margin-bottom:5px;">
                    <img src="/storage/barang/${b.gambar_barang}" alt="${b.nama_barang}" width="50" height="50" style="object-fit:cover;"/>
                    <br/>
                    <small>${b.nama_barang}</small><br/>
                    <small><strong>Rp${b.harga_barang}</strong></small><br/>
                    ${statusTampil 
                        ? `<small style="color:${statusColor}; font-weight:bold;">Status: ${statusTampil}</small>` 
                        : ''
                    }
                </div>
            `;
        }).join('')
        : '-'
    }
</td>
                           <td>
    ${
        req.status_transaksi === 'Disiapkan'
        ? `
            <button class="btn btn-primary btn-sm" onclick='cetakNotaSedangDikirim(${JSON.stringify(req)})'>Cetak Nota</button>
            <br/>
            ${req.opsi_pengiriman === 1 
                ? `<button class="btn btn-success btn-sm mt-1" onclick='konfirmasiKirim(${req.id_transaksi})'>Konfirmasi Kirim</button>`
                : `<button class="btn btn-success btn-sm mt-1" onclick='konfirmasiDiambil(${req.id_transaksi})'>Konfirmasi Diambil</button>`
            }
        `
        : req.status_transaksi === 'Sedang Dikirim'
        ? `
            <button class="btn btn-primary btn-sm" onclick='cetakNotaSedangDikirim(${JSON.stringify(req)})'>Cetak Nota</button>
        `
        : (req.status_transaksi === 'Diambil Sendiri' || req.status_transaksi === 'Diterima')
        ? `
            <button class="btn btn-secondary btn-sm" onclick='cetakNotaDiambilSendiri(${JSON.stringify(req)})'>Cetak Nota</button>
            ${req.status_transaksi === 'Diambil Sendiri' 
                ? `<br/><button class="btn btn-success btn-sm mt-1" onclick='konfirmasiDiambil(${req.id_transaksi})'>Konfirmasi Diambil</button>` 
                : '' }
        `
        : `-`
    }
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

        function bukaModalJadwal(transaksi) {
            document.getElementById('jadwalTransaksiId').value = transaksi.id_transaksi;
            document.getElementById('jadwalForm').reset();
            
            const isDelivery = transaksi.opsi_pengiriman == 1;
            document.getElementById('jenisPenjadwalan').value = isDelivery ? 'pengiriman' : 'pengambilan';
            
            const kurirContainer = document.getElementById('kurirSelectContainer');
            const kurirSelect = document.getElementById('kurirSelect');
            
            if (isDelivery) {
                kurirContainer.style.display = 'block';
                kurirSelect.setAttribute('required', 'required');
                document.getElementById('jadwalModalLabel').textContent = 'Penjadwalan Pengiriman & Kurir';
                fetchKurirOptions();
            } else {
                kurirContainer.style.display = 'none';
                kurirSelect.removeAttribute('required');
                document.getElementById('jadwalModalLabel').textContent = 'Penjadwalan Pengambilan Sendiri';
            }
            
            new bootstrap.Modal(document.getElementById('jadwalModal')).show();
        }

        function fetchKurirOptions() {
            axios.get('/api/kurir', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => {
                const select = document.getElementById('kurirSelect');
                select.innerHTML = res.data.map(kurir => `
                    <option value="${kurir.id_pegawai}">${kurir.username}</option>
                `).join('');
            })
            .catch(err => {
                console.error('Gagal ambil kurir:', err);
                const select = document.getElementById('kurirSelect');
                select.innerHTML = `<option disabled selected>Error memuat kurir</option>`;
            });
        }

        document.getElementById('jadwalForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const id = document.getElementById('jadwalTransaksiId').value;
            const jenis = document.getElementById('jenisPenjadwalan').value;
            const jadwal = document.getElementById('jadwalTanggal').value;

            if(jenis === 'pengiriman') {
                const idKurir = document.getElementById('kurirSelect').value;
                axios.post(`/api/transaksi/${id}/penjadwalan`, {
                    id_pegawai: idKurir,
                    jadwal_pengiriman: jadwal
                }, {
                    headers: { 'Authorization': `Bearer ${token}` }
                }).then(() => {
                    alert('Penjadwalan pengiriman berhasil ditambahkan!');
                    bootstrap.Modal.getInstance(document.getElementById('jadwalModal')).hide();
                    fetchData();
                }).catch(err => {
                    console.error(err);
                    alert('Gagal menambahkan penjadwalan pengiriman!');
                });
            } else if (jenis === 'pengambilan') {
                axios.post(`/api/transaksi/${id}/penjadwalanPengambilan`, {
                    jadwal_pengiriman: jadwal
                }, {
                    headers: { 'Authorization': `Bearer ${token}` }
                }).then(() => {
                    alert('Penjadwalan pengambilan berhasil ditambahkan!');
                    bootstrap.Modal.getInstance(document.getElementById('jadwalModal')).hide();
                    fetchData();
                }).catch(err => {
                    console.error(err);
                    alert('Gagal menambahkan penjadwalan pengambilan!');
                });
            }
        });

        function konfirmasiDiambil(id) {
            if (confirm('Apakah Anda yakin ingin mengkonfirmasi barang sudah diambil?')) {
                axios.put(`/api/transaksi/${id}/konfirmasiDiambil`, {}, {
                    headers: { 'Authorization': `Bearer ${token}` }
                }).then(() => {
                    alert('Status transaksi berhasil diperbarui.');
                    fetchData();
                }).catch(err => {
                    console.error(err);
                    alert('Gagal memperbarui status transaksi.');
                });
            }
        }

        function konfirmasiKirim(id) {
            if (confirm('Apakah Anda yakin ingin mengkonfirmasi pengiriman dan mengubah status menjadi Sedang Dikirim?')) {
                axios.put(`/api/transaksi/${id}/konfirmasiKirim`, {}, {
                    headers: { 'Authorization': `Bearer ${token}` }
                }).then(() => {
                    alert('Status transaksi berhasil diperbarui menjadi Sedang Dikirim.');
                    fetchData();
                }).catch(err => {
                    console.error(err);
                    alert('Gagal memperbarui status transaksi.');
                });
            }
        }

         async function cetakNotaSedangDikirim(dataString) {
            const data = typeof dataString === 'string' ? JSON.parse(dataString) : dataString;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            let y = 10;

            const formatRupiah = (angka) => `Rp${new Intl.NumberFormat('id-ID').format(angka ?? 0)}`;

            const tanggal = new Date(data.tanggal_transaksi);
            const tahun = tanggal.getFullYear();
            const bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
            const noNota = `${tahun}.${bulan}.${String(data.id_transaksi).padStart(3, '0')}`;

            doc.setFontSize(14);
            doc.text("ReUse Mart", 10, y); y += 7;
            doc.setFontSize(10);
            doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 10, y); y += 10;

            doc.setFontSize(12);
            doc.text(`No Nota       : ${data.nomor_transaksi}`, 10, y); y += 6;
            doc.text(`Tanggal pesan : ${data.tanggal_transaksi}`, 10, y); y += 6;
            doc.text(`Lunas pada    : ${data.tanggal_lunas ?? '-'}`, 10, y); y += 6;
            doc.text(`Tanggal kirim : ${data.jadwal_pengiriman ?? '-'}`, 10, y); y += 6;
           

            doc.text(`Pembeli : ${data.pembeli?.email ?? '-'} / ${data.pembeli?.username ?? '-'}`, 10, y); y += 6;
            doc.text(`${data.deskripsi_alamat ?? '-'}`, 10, y); y += 6;
            doc.text(`Delivery: ${data.opsi_pengiriman == 1 ? `Kurir ReUseMart (${data.pegawai?.username ?? '-'})` : 'Diambil Sendiri'}`, 10, y); y += 10;

            data.barang.forEach(b => {
                doc.text(`${b.nama_barang}`, 10, y);
                doc.text(formatRupiah(b.harga_barang), 200, y, { align: 'right' });
                y += 6;
            });

            y += 4;
            doc.text(`Total             : ${formatRupiah(data.harga_total_barang)}`, 10, y); y += 6;
            doc.text(`Ongkos Kirim      : ${formatRupiah(data.harga_ongkir)}`, 10, y); y += 6;
            doc.text(`Potongan Harga    : ${formatRupiah(data.potongan_harga)}`, 10, y); y += 6;
            doc.text(`Poin Pembeli      : ${data.poin_pembeli ?? 0}`, 10, y); y += 6;
            const totalAkhir = (data.harga_total_barang + (data.harga_ongkir ?? 0)) - (data.potongan_harga ?? 0);
            doc.text(`Total Pembayaran  : ${formatRupiah(totalAkhir)}`, 10, y); y += 10;

            doc.text(`QC oleh: Fitri Handayani`, 10, y); y += 15;
            doc.text(`Diterima oleh:`, 10, y); y += 25;
            doc.text("(..................................)", 10, y); y += 6;
            doc.text("Tanggal: ..........................", 10, y);

            doc.save(`nota_kirim_${data.id_transaksi}.pdf`);
        }

        async function cetakNotaDiambilSendiri(dataString) {
            const data = typeof dataString === 'string' ? JSON.parse(dataString) : dataString;
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF();
            let y = 10;

            const formatRupiah = (angka) => `Rp${new Intl.NumberFormat('id-ID').format(angka ?? 0)}`;

            const tanggal = new Date(data.tanggal_transaksi);
            const tahun = tanggal.getFullYear();
            const bulan = String(tanggal.getMonth() + 1).padStart(2, '0');
            const noNota = `${tahun}.${bulan}.${String(data.id_transaksi).padStart(3, '0')}`;

            doc.setFontSize(14);
            doc.text("ReUse Mart", 10, y); y += 7;
            doc.setFontSize(10);
            doc.text("Jl. Green Eco Park No. 456 Yogyakarta", 10, y); y += 10;

            doc.setFontSize(12);
            doc.text(`No Nota        : ${data.nomor_transaksi}`, 10, y); y += 6;
            doc.text(`Tanggal pesan  : ${data.tanggal_transaksi}`, 10, y); y += 6;
            doc.text(`Lunas pada     : ${data.tanggal_lunas ??'-'}`, 10, y); y += 6;
            doc.text(`Tanggal ambil  : ${data.jadwal_pengiriman ??'-'}`, 10, y); y += 6;
            
            doc.text(`Pembeli : ${data.pembeli?.email ?? '-'} / ${data.pembeli?.username ?? '-'}`, 10, y); y += 6;
            doc.text(`${data.deskripsi_alamat ?? '-'}`, 10, y); y += 10;
            doc.text(`Delivery: -(diambil sendiri)-`, 10, y); y += 10;

            data.barang.forEach(b => {
                doc.text(`${b.nama_barang}`, 10, y);
                doc.text(formatRupiah(b.harga_barang), 200, y, { align: 'right' });
                y += 6;
            });

            y += 4;
            doc.text(`Total             : ${formatRupiah(data.harga_total_barang)}`, 10, y); y += 6;
            doc.text(`Potongan Harga    : ${formatRupiah(data.potongan_harga)}`, 10, y); y += 6;
            doc.text(`Poin Pembeli      : ${data.poin_pembeli ?? 0}`, 10, y); y += 6;
            const totalAkhir = data.harga_total_barang - (data.potongan_harga ?? 0);
            doc.text(`Total Pembayaran  : ${formatRupiah(totalAkhir)}`, 10, y); y += 10;

            doc.text(`QC oleh: Fitri Handayani`, 10, y); y += 15;
            doc.text(`Diterima oleh:`, 10, y); y += 25;
            doc.text("(..................................)", 10, y); y += 6;
            doc.text("Tanggal: ..........................", 10, y);

            doc.save(`nota_ambil_${data.id_transaksi}.pdf`);
        }

        // Fetch data awal saat halaman dimuat
        fetchData();
    </script>
</div>
@endsection