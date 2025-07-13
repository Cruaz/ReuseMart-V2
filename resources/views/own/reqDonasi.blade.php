@extends('ownerlayout')

@section('title', 'Request Donasi')

@section('content')
<div class="">
	<div class="container-fluid">
		<div class="card" style="height:80vh;">
			<div class="card-body p-4">
				<div class="d-flex justify-content-between">
					<div class="d-flex align-items-center">
						<h5 class="card-title h2">Request Donasi</h5>
					</div>
				</div>

				<table class="table w-100 text-center mt-4">
					<thead>
						<tr>
							<th>ID Request</th>
							<th>ID Organisasi</th>
							<th>Tanggal Request</th>
							<th>Status Request</th>
							<th>Deskripsi Request</th>
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

	<!-- Modal Update -->
	<div class="modal fade" id="modalUpdate" tabindex="-1" aria-labelledby="modalUpdateLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title">Update Request</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<form id="submitFormUpdate">
					@csrf
					<input type="hidden" name="id_request" id="update-id">
					<div class="modal-body">
						<div class="mb-3">
							<label>Tanggal Request</label>
							<input type="text" class="form-control" name="tanggal_request" id="update-tanggal">
						</div>
						<div class="mb-3">
							<label>Status Request</label>
							<input type="text" class="form-control" name="status_request" id="update-status">
						</div>
						<div class="mb-3">
							<label>Deskripsi Request</label>
							<input type="text" class="form-control" name="deskripsi_request" id="update-deskripsi">
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
					<h5 class="modal-title">Hapus Request</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
				</div>
				<div class="modal-body">
					<p>Apakah kamu yakin ingin menghapus request ini?</p>
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
			const search = document.getElementById('search')?.value || '';

			axios.get(`/api/request?page=${page}&per_page=${itemsPerPage}&search=${encodeURIComponent(search)}`, {
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

				paginatedData.data.forEach(req => {
					tableBody.innerHTML += `
						<tr>
							<td>${req.id_request}</td>
							<td>${req.id_organisasi}</td>
							<td>${req.tanggal_request}</td>
							<td>${req.status_request}</td>
							<td>${req.deskripsi_request}</td>
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

		document.getElementById('submitFormUpdate').addEventListener('submit', function (e) {
			e.preventDefault();
			const formData = new FormData(this);
			const id = document.getElementById('update-id').value;

			axios.post(`/api/status/${id}`, formData, {
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

		document.getElementById('confirmDelete').addEventListener('click', function () {
			const id = document.getElementById('delete-id').value;

			axios.delete(`/api/status/${id}`, {
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
