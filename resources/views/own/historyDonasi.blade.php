@extends('ownerlayout')

@section('title', 'History Donasi')

@section('content')
<div class="">
    <div class="container-fluid">
        <div class="card" style="height:80vh;">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title h2">Donasi Disetujui</h5>
                    </div>
                </div>

                <table class="table w-100 text-center mt-4">
                    <thead>
                        <tr>
                            <th>Tanggal Donasi</th>
                            <th>Nama Penerima</th>
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
</div>

<script>
    let perPage = 7;
    const token = localStorage.getItem('token');

    function changeItemsPerPage(newPerPage) {
        perPage = newPerPage;
        document.getElementById('items-per-page-btn').innerText = newPerPage;
        fetchData(1, perPage);
    }

    function fetchData(page = 1, itemsPerPage = perPage) {
        const search = document.getElementById('search')?.value ?? "";

        axios.get(`/api/donasi/approved?page=${page}&per_page=${itemsPerPage}&search=${encodeURIComponent(search)}`, {
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

            paginatedData.data.forEach(dog => {
                tableBody.innerHTML += `
                    <tr>
                        <td>${dog.tanggal_donasi}</td>
                        <td>${dog.nama_penerima}</td>
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

    document.addEventListener('DOMContentLoaded', () => {
        fetchData();
    });
</script>
@endsection
