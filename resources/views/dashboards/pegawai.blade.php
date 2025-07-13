@extends('AdminLayout')

@section('title', 'Pegawai')

@section('content')
<div class="">
	<div class="container-fluid">
		<div class="card" style="height:80vh;">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between">
					<div class="d-flex align-items-center">
						<h5 class="card-title h2">Pegawai</h5>
					</div>
					<a href="" data-bs-toggle="modal" data-bs-target="#modalInsert">
						<button type="button" class="px-4 text-white py-2 btn btn-info"><i class="fa-solid fa-plus me-3"></i>Tambah</button>
					</a>
				</div>

				<table class=" table w-100 text-center mt-4">
					<thead>
						<tr>
							<th>ID Pegawai</th>
                            <th>ID Jabatan</th>
							<th>Username</th>
							<th>Tanggal Lahir</th>
							<th>Nomor Telepon</th>
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
					<h5 class="modal-title">Tambah Pegawai</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="submitFormInsert">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" name="id_jabatan">
                        </div>
						<div class="mb-3">
							<label>Username</label>
							<input type="text" class="form-control" name="username">
						</div>
						<div class="mb-3">
							<label>Password</label>
							<input type="text" class="form-control" name="password">
						</div>
						<div class="mb-3">
							<label>Tanggal Lahir</label>
							<input type="date" class="form-control" name="tanggal_lahir_pegawai">
						</div>
						<div class="mb-3">
							<label>Nomor Telepon</label>
							<input type="text" class="form-control" name="nomor_telepon_pegawai">
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
                    <h5 class="modal-title">Update Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="submitFormUpdate">
                    @csrf
                    <input type="hidden" name="id_pegawai" id="update-id">
                    <div class="modal-body">
						<div class="mb-3">
                            <label>Jabatan</label>
                            <input type="text" class="form-control" name="id_jabatan" id="update-id_jabatan">
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" class="form-control" name="username" id="update-user">
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="text" class="form-control" name="password" id="update-password">
                        </div>
                        <div class="mb-3">
							<label>Tanggal Lahir</label>
							<input type="date" class="form-control" name="tanggal_lahir_pegawai" id="update-tanggal">
						</div>
						<div class="mb-3">
							<label>Nomor Telepon</label>
							<input type="text" class="form-control" name="nomor_telepon_pegawai" id="update-telepon">
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

	<div class="modal fade" id="modalReset" tabindex="-1" aria-labelledby="modalResetLabbel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reset Password Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin mereset password pegawai ini?</p>
                    <input type="hidden" id="reset-id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" id="confirmReset">Reset</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalDelete" tabindex="-1" aria-labelledby="modalDeleteLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Pegawai</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah kamu yakin ingin menghapus pegawai ini?</p>
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

		axios.get(`/api/pegawai?page=${page}&per_page=${itemsPerPage}&search=${encodeURIComponent(search)}`, {
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

			paginatedData.data.forEach(peg => {
				tableBody.innerHTML += `
					<tr>
						<td>${peg.id_pegawai}</td>
                        <td>${peg.id_jabatan}</td>
						<td>${peg.username}</td>
                        <td>${peg.tanggal_lahir_pegawai}</td>
                        <td>${peg.nomor_telepon_pegawai}</td>
						<td>
							<div class="dropdown">
								<a class="btn dropdown" href="#" data-bs-toggle="dropdown">
									<i class="fa-solid fa-ellipsis-vertical"></i>
								</a>
								<ul class="dropdown-menu">
									<li><a class="dropdown-item update-btn" href="#modalUpdate" data-bs-toggle="modal" data-bs-target="#modalUpdate">Update</a></li>
									<li><a class="dropdown-item reset-btn" href="#modalReset" data-bs-toggle="modal" data-bs-target="#modalReset">Reset Password</a></li>
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
		axios.post('/api/pegawai', formData, {
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
            document.getElementById('update-user').value = cells[2].textContent;
            document.getElementById('update-tanggal').value = cells[3].textContent;
            document.getElementById('update-telepon').value = cells[4].textContent;
        }

        if (e.target.classList.contains('delete-btn')) {
            const row = e.target.closest('tr');
            const id = row.querySelector('td').textContent;
            document.getElementById('delete-id').value = id;
        }

		if (e.target.classList.contains('reset-btn')) {
			const row = e.target.closest('tr');
			const id = row.querySelector('td').textContent;
			document.getElementById('reset-id').value = id;
		}
    });

    document.getElementById('submitFormUpdate').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('update-id').value;

        axios.post(`/api/pegawai/${id}`, formData, {
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

	document.getElementById('confirmReset').addEventListener('click', function() {
		const id = document.getElementById('reset-id').value;

		axios.post('/api/reset-passwordPegawai', {
			id_pegawai: id
		}, {
			headers: {
				'Authorization': `Bearer ${token}`
			}
		}).then(response => {
			fetchData();
			const modal = bootstrap.Modal.getInstance(document.getElementById('modalReset'));
			modal.hide();
		}).catch(error => {
			console.error('Gagal reset:', error);
		});
	});

    document.getElementById('confirmDelete').addEventListener('click', function() {
        const id = document.getElementById('delete-id').value;

        axios.delete(`/api/pegawai/${id}`, {
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