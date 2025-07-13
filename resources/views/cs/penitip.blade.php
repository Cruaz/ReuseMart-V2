@extends('cslayout')

@section('title', 'Penitip')

@section('content')
<div class="">
	<div class="container-fluid">
		<div class="card" style="height:80vh;">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between">
					<div class="d-flex align-items-center">
						<h5 class="card-title h2">Penitip</h5>
					</div>
					<a href="" data-bs-toggle="modal" data-bs-target="#modalInsert">
						<button type="button" class="px-4 text-white py-2 btn btn-info"><i class="fa-solid fa-plus me-3"></i>Tambah</button>
					</a>
				</div>

				<table class="table w-100 text-center mt-4">
					<thead>
						<tr>
							<th>ID Penitip</th>
							<th>Username</th>
							<th>Email</th>
							<th>NIK</th>
							<th>Foto</th>
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
					<h5 class="modal-title">Tambah Penitip</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="submitFormInsert">
					@csrf
					<div class="modal-body">
						<div class="mb-3">
							<label>Username</label>
							<input type="text" class="form-control" name="username">
						</div>
						<div class="mb-3">
							<label>Email</label>
							<input type="text" class="form-control" name="email">
						</div>
						<div class="mb-3">
							<label>Password</label>
							<input type="text" class="form-control" name="password">
						</div>
						<div class="mb-3">
							<label>NIK</label>
							<input type="text" class="form-control" name="nik" pattern="\d*" inputmode="numeric">
						</div>
						<div class="mb-3">
							<label>Foto</label>
							<input type="file" class="form-control" name="foto">
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
					<h5 class="modal-title">Update Penitip</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="submitFormUpdate">
					@csrf
					<input type="hidden" name="id_penitip" id="update-id">
					<div class="modal-body">
						<div class="mb-3">
							<label>Username</label>
							<input type="text" class="form-control" name="username" id="update-nama">
						</div>
						<div class="mb-3">
							<label>Email</label>
							<input type="text" class="form-control" name="email" id="update-email">
						</div>
						<div class="mb-3">
							<label>Password</label>
							<input type="text" class="form-control" name="password" id="update-password">
						</div>
						<div class="mb-3">
							<label>NIK</label>
							<input type="text" class="form-control" name="nik" id="update-nik" pattern="\d*" inputmode="numeric">
						</div>
						<div class="mb-3">
							<label>Foto</label>
							<input type="file" class="form-control" name="foto" id="update-foto">
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
					<h5 class="modal-title">Hapus Penitip</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p>Apakah kamu yakin ingin menghapus penitip ini?</p>
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
			axios.get(`/api/penitip?page=${page}&per_page=${itemsPerPage}&search=${searchQuery}`, {
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

				paginatedData.data.forEach(pn => {
					tableBody.innerHTML += `
						<tr>
							<td>${pn.id_penitip}</td>
							<td>${pn.username}</td>
							<td>${pn.email}</td>
							<td>${pn.nik || '-'}</td>
							<td><img src="/storage/Galery/${pn.foto}" width="50" height="50"></td>
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

		// Validasi Form Tambah
		document.getElementById('submitFormInsert').addEventListener('submit', function(e) {
			e.preventDefault();
			const formData = new FormData(this);
			
			const username = formData.get('username');
			const email = formData.get('email');
			const password = formData.get('password');
			const nik = formData.get('nik');
			const foto = formData.get('foto');

			// Validasi Username
			if (username.trim() === '') {
				alert('Username tidak boleh kosong');
				return;
			}

			// Validasi Email
			const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			if (!emailRegex.test(email)) {
				alert('Email tidak valid');
				return;
			}

			// Validasi Password
			if (password.trim() === '') {
				alert('Password tidak boleh kosong');
				return;
			}

			// Validasi NIK (hanya angka dan panjang NIK)
			if (!/^\d+$/.test(nik)) {
				alert('NIK hanya boleh berisi angka');
				return;
			}

			// Validasi Foto (opsional, jika foto ada maka harus ada file yang diupload)
			if (foto && foto.size === 0) {
				alert('Foto harus diupload');
				return;
			}

			axios.post('/api/penitip', formData, {
				headers: {
					'Authorization': `Bearer ${token}`,
					'Content-Type': 'multipart/form-data'
				}
			}).then(response => {
				fetchData();
				this.reset();
				const modal = bootstrap.Modal.getInstance(document.getElementById('modalInsert'));
				modal.hide();
			}).catch(error => {
				console.error('Gagal submit:', error);
			});
		});

		// Validasi Form Update
		document.getElementById('submitFormUpdate').addEventListener('submit', function(e) {
			e.preventDefault();
			const formData = new FormData(this);
			
			const username = formData.get('username');
			const email = formData.get('email');
			const password = formData.get('password');
			const nik = formData.get('nik');
			const foto = formData.get('foto');

			// Validasi Username
			if (username.trim() === '') {
				alert('Username tidak boleh kosong');
				return;
			}

			// Validasi Email
			const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
			if (!emailRegex.test(email)) {
				alert('Email tidak valid');
				return;
			}

			// Validasi Password
			if (password.trim() === '') {
				alert('Password tidak boleh kosong');
				return;
			}

			// Validasi NIK (hanya angka dan panjang NIK)
			if (!/^\d+$/.test(nik)) {
				alert('NIK hanya boleh berisi angka');
				return;
			}

			// Validasi Foto (opsional, jika foto ada maka harus ada file yang diupload)
			if (foto && foto.size === 0) {
				alert('Foto harus diupload');
				return;
			}

			const id = document.getElementById('update-id').value;

			axios.post(`/api/penitip/${id}`, formData, {
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

			axios.delete(`/api/penitip/${id}`, {
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
