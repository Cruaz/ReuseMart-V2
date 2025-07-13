@extends('cslayout')

@section('title', 'Jawab Diskusi')

@section('content')
<div class="">
    <div class="container-fluid">
        <div class="card" style="height:80vh;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title h2">Daftar Diskusi</h5>
                    </div>
                </div>

                <table class="table w-100 text-center mt-4">
                    <thead>
                        <tr>
                            <th>ID Diskusi</th>
                            <th>Pembeli</th>
                            <th>Barang</th>
                            <th>Pertanyaan</th>
                            <th>Jawaban</th>
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

    <!-- Modal Jawab -->
    <div class="modal fade" id="modalJawab" tabindex="-1" aria-labelledby="modalJawabLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Jawab Diskusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="submitFormJawab">
                    @csrf
                    <input type="hidden" name="id_diskusi" id="jawab-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Pertanyaan</label>
                            <textarea class="form-control" name="pertanyaan" id="jawab-pertanyaan" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label>Jawaban</label>
                            <textarea class="form-control" name="jawaban_diskusi" id="jawaban-diskusi" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-info text-white">Simpan Jawaban</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Delete -->
    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Diskusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin menghapus diskusi ini?</p>
                    <input type="hidden" id="delete-id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" id="confirmDelete">Hapus</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let perPage = 7;
        const token = localStorage.getItem('token');

        function fetchData(page = 1, itemsPerPage = perPage) {
            const searchQuery = document.getElementById('search') ? document.getElementById('search').value : ''; 
           axios.get(`/api/diskusi?page=${page}&per_page=${itemsPerPage}&search=${searchQuery}&unanswered=true`, {
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

                paginatedData.data.forEach(diskusi => {
                    const row = `
                        <tr>
                            <td>${diskusi.id_diskusi}</td>
                            <td>${diskusi.pembeli?.username || 'Pembeli tidak ditemukan'}</td>
                            <td>${diskusi.barang?.nama_barang || 'Barang tidak ditemukan'}</td>
                            <td>${diskusi.pertanyaan_diskusi}</td>
                            <td>${diskusi.jawaban_diskusi || '<span class="text-danger">Belum dijawab</span>'}</td>
                            <td>
                                <div class="dropdown">
                                    <a class="btn dropdown" href="#" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </a>
                                    <ul class="dropdown-menu">
                                        ${diskusi.jawaban_diskusi ? '' : `
                                        <li><a class="dropdown-item jawab-btn" href="#modalJawab" 
                                            data-bs-toggle="modal" data-bs-target="#modalJawab"
                                            data-id="${diskusi.id_diskusi}"
                                            data-pertanyaan="${diskusi.pertanyaan_diskusi}">
                                            Jawab
                                        </a></li>
                                        `}
                                        <li><a class="dropdown-item text-danger delete-btn" href="#modalDelete" 
                                            data-bs-toggle="modal" data-bs-target="#modalDelete"
                                            data-id="${diskusi.id_diskusi}">
                                            Delete
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    `;
                    tableBody.innerHTML += row;
                });

                for (let i = 1; i <= paginatedData.last_page; i++) {
                    pagination.innerHTML += `
                        <li class="page-item ${i === paginatedData.current_page ? 'active' : ''}">
                            <a class="page-link" href="#" onclick="fetchData(${i})">${i}</a>
                        </li>
                    `;
                }

                // Add event listeners for jawab buttons
                document.querySelectorAll('.jawab-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('jawab-id').value = this.dataset.id;
                        document.getElementById('jawab-pertanyaan').value = this.dataset.pertanyaan;
                        document.getElementById('jawaban-diskusi').value = '';
                    });
                });

                // Add event listeners for delete buttons
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', function() {
                        document.getElementById('delete-id').value = this.dataset.id;
                    });
                });

            }).catch(error => {
                console.error('Gagal ambil data:', error);
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            fetchData();
        });

        // Form Jawab
        document.getElementById('submitFormJawab').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const id = document.getElementById('jawab-id').value;
            const jawaban = formData.get('jawaban_diskusi');

            // Validasi jawaban
            if (jawaban.trim() === '') {
                alert('Jawaban tidak boleh kosong');
                return;
            }

            axios.post(`/api/diskusi/jawab/${id}`, {
                jawaban_diskusi: jawaban
            }, {
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                }
            }).then(response => {
                fetchData();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalJawab'));
                modal.hide();
            }).catch(error => {
                console.error('Gagal mengirim jawaban:', error);
            });
        });

        // Delete
        document.getElementById('confirmDelete').addEventListener('click', function() {
            const id = document.getElementById('delete-id').value;

            axios.delete(`/api/diskusi/${id}`, {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            }).then(response => {
                fetchData();
                const modal = bootstrap.Modal.getInstance(document.getElementById('modalDelete'));
                modal.hide();
            }).catch(error => {
                console.error('Gagal hapus:', error);
            });
        });
    </script>
</div>
@endsection