@extends('AdminLayout')

@section('title', 'Merchandise')

@section('content')
<div class="">
	<div class="container-fluid">
		<div class="card" style="height:80vh;">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between">
					<div class="d-flex align-items-center">
						<h5 class="card-title h2">Merchandise</h5>
					</div>
					<a href="" data-bs-toggle="modal" data-bs-target="#modalInsert">
						<button type="button" class="px-4 text-white py-2 btn btn-info"><i class="fa-solid fa-plus me-3"></i>Tambah</button>
					</a>
				</div>

				<table class=" table w-100 text-center mt-4">
					<thead>
						<tr>
							<th>ID Merchandise</th>
							<th>Nama Merchandise</th>
							<th>Poin Redeem</th>
							<th>Stok Merchandise</th>
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

	<!-- Modal Insert -->
	<div class="modal fade" id="modalInsert" tabindex="-1" aria-labelledby="modalInsertLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Tambah Merchandise</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="submitFormInsert">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label>Nama Merchandise</label>
							<input type="text" class="form-control" name="nama_merchandise">
						</div>
						<div class="mb-3">
							<label>Poin Redeem</label>
							<input type="text" class="form-control" name="poin_redeem">
						</div>
						<div class="mb-3">
							<label>Stok Merchandise</label>
							<input type="text" class="form-control" name="stok_merchandise">
						</div>
					</div>
					<div class="modal-footer">
						<button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
						<button class="btn btn-info text-white">Simpan</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Modal Update -->
    <div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Merchandise</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="submitFormUpdate">
                    @csrf
                    <input type="hidden" name="id_merchandise" id="update-id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Merchandise</label>
                            <input type="text" class="form-control" name="nama_merchandise" id="update-nama">
                        </div>
                        <div class="mb-3">
                            <label>Poin poin_redeem</label>
                            <input type="text" class="form-control" name="poin_redeem" id="update-poin">
                        </div>
                        <div class="mb-3">
							<label>Stok Merchandise</label>
							<input type="text" class="form-control" name="stok_merchandise" id="update-stok">
						</div>
					
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-info text-white">Simpan</button>
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
                    <h5 class="modal-title">Hapus Merchandise</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin menghapus merchandise ini?</p>
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

	function changeItemsPerPage(newPerPage) {
		perPage = newPerPage;
		document.getElementById('items-per-page-btn').innerText = newPerPage;
		fetchData(1, perPage);
	}

	function fetchData(page = 1, itemsPerPage = perPage) {
		const search = document.getElementById('search').value;

		axios.get(`/api/merchandise?page=${page}&per_page=${itemsPerPage}&search=${encodeURIComponent(search)}`, {
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

			paginatedData.data.forEach(merch => {
				tableBody.innerHTML += `
					<tr>
						<td>${merch.id_merchandise}</td>
						<td>${merch.nama_merchandise}</td>
						<td>${merch.poin_redeem}</td>
                        <td>${merch.stok_merchandise}</td>
						<td>
							<div class="dropdown">
								<a class="btn dropdown" href="#" data-bs-toggle="dropdown">
									<i class="fa-solid fa-ellipsis-vertical"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item update-btn" href="#modalUpdate" data-bs-toggle="modal" data-bs-target="#modalUpdate">Update</a></li>
									<li><a class="dropdown-item text-danger delete-btn" href="#modalDelete" data-bs-toggle="modal" data-bs-target="#modalDelete">Delete</a></li>
								</ul>
							</div>
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

	document.addEventListener('DOMContentLoaded', () => {
		fetchData();
	});

	document.getElementById('submitFormInsert').addEventListener('submit', function(e) {
		e.preventDefault();
		const formData = new FormData(this);
		axios.post('/api/merchandise', formData, {
			headers: {
				'Authorization': `Bearer ${token}`,
				'Content-Type': 'multipart/form-data'
			}
		}).then(response => {
			console.log(response.data);
			fetchData();
			this.reset();
			const modal = bootstrap.Modal.getInstance(document.getElementById('modalInsert'));
			modal.hide();
		}).catch(error => {
			console.error('Gagal submit:', error);
		});
	});

    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('update-btn')) {
            const row = e.target.closest('tr');
            const cells = row.querySelectorAll('td');

            document.getElementById('update-id').value = cells[0].textContent;
            document.getElementById('update-nama').value = cells[1].textContent;
            document.getElementById('update-poin').value = cells[2].textContent;
            document.getElementById('update-stok').value = cells[3].textContent;
        }

        if (e.target.classList.contains('delete-btn')) {
            const row = e.target.closest('tr');
            const id = row.querySelector('td').textContent;
            document.getElementById('delete-id').value = id;
        }
    });

    document.getElementById('submitFormUpdate').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('update-id').value;

        axios.post(`/api/stok/${id}`, formData, {
            headers: {
                'Authorization': `Bearer ${token}`,
                'Content-Type': 'multipart/form-data'
            }
        }).then(response => {
            fetchData();
            const modal = bootstrap.Modal.getInstance(document.getElementById('modalUpdate'));
            modal.hide();
        }).catch(error => {
            console.error('Gagal update:', error);
        });
    });

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const id = document.getElementById('delete-id').value;

        axios.delete(`/api/stok/${id}`, {
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
@endsection