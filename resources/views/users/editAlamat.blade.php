@extends('pembeliLayout')

@section('title', 'Edit Alamat')

@section('contentUser')
<div class="container">
    <div class="col-12">
        <div class="card mb-3 w-100 px-5 py-3">
            <h2 class="mb-4">Kelola Alamat Anda</h2>
            
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-info text-white" data-bs-toggle="modal" data-bs-target="#addAddressModal">
                    <i class="fa-solid fa-plus me-2"></i>Tambah Alamat Baru
                </button>
            </div>
            
            <div class="row" id="addressList">
                @if($alamat->isEmpty())
                    <div class="col-12">
                        <div class="alert alert-info">
                            Anda belum memiliki alamat. Silakan tambahkan alamat baru.
                        </div>
                    </div>
                @else
                    @foreach($alamat as $address)
                    <div class="col-md-6 mb-3">
                        <div class="card {{ $address->is_default ? 'border-primary' : '' }}">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div>
                                        <h5 class="card-title">
                                            {{ $address->label_alamat }}
                                            @if($address->is_default)
                                                <span class="badge bg-primary ms-2">Utama</span>
                                            @endif
                                        </h5>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn btn-sm" type="button" data-bs-toggle="dropdown">
                                            <i class="fa-solid fa-ellipsis-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item edit-address" href="#" 
                                                data-id="{{ $address->id_alamat }}"
                                                data-label="{{ $address->label_alamat }}"
                                                data-deskripsi="{{ $address->deskripsi_alamat }}"
                                                data-default="{{ $address->is_default }}">Edit</a></li>
                                            <li><a class="dropdown-item text-danger delete-address" href="#" 
                                                data-id="{{ $address->id_alamat }}">Hapus</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <p class="card-text">{{ $address->deskripsi_alamat }}</p>
                                @if(!$address->is_default)
                                    <button class="btn btn-outline-primary btn-sm set-default" 
                                        data-id="{{ $address->id_alamat }}">
                                        Jadikan Alamat Utama
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addAddressModalLabel">Tambah Alamat Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addAddressForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="label_alamat" class="form-label">Label Alamat</label>
                        <input type="text" class="form-control" id="label_alamat" name="label_alamat" required placeholder="Contoh: Rumah, Kantor, Kos">
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi_alamat" class="form-label">Deskripsi Alamat Lengkap</label>
                        <textarea class="form-control" id="deskripsi_alamat" name="deskripsi_alamat" rows="3" required placeholder="Jl. Contoh No. 123, RT/RW, Kelurahan, Kecamatan, Kota"></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_default" name="is_default">
                        <label class="form-check-label" for="is_default">Jadikan sebagai alamat utama</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Alamat</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editAddressModalLabel">Edit Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editAddressForm">
                <input type="hidden" id="edit_id_alamat" name="id_alamat">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_label_alamat" class="form-label">Label Alamat</label>
                        <input type="text" class="form-control" id="edit_label_alamat" name="label_alamat" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_deskripsi_alamat" class="form-label">Deskripsi Alamat Lengkap</label>
                        <textarea class="form-control" id="edit_deskripsi_alamat" name="deskripsi_alamat" rows="3" required></textarea>
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="edit_is_default" name="is_default">
                        <label class="form-check-label" for="edit_is_default">Jadikan sebagai alamat utama</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteAddressModal" tabindex="-1" aria-labelledby="deleteAddressModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAddressModalLabel">Konfirmasi Hapus Alamat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Apakah Anda yakin ingin menghapus alamat ini?
                <input type="hidden" id="delete_id_alamat">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">Hapus</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function(e) {
        document.getElementById('addAddressForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');

            const isDefault = document.getElementById('is_default').checked ? 1 : 0;
            formData.set('is_default', isDefault);

            fetch('/pembeli/alamat', {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal menambahkan alamat: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menambahkan alamat');
            });
        });

        document.getElementById('editAddressForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = document.getElementById('edit_id_alamat').value;
            const formData = new FormData(this);
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            const isDefault = document.getElementById('is_default').checked ? 1 : 0;
            formData.set('is_default', isDefault);

            fetch(`/pembeli/alamat/${id}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal mengupdate alamat: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengupdate alamat');
            });
        });

        document.getElementById('confirmDelete').addEventListener('click', function() {
            const id = document.getElementById('delete_id_alamat').value;
            
            fetch(`/pembeli/alamat/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    window.location.reload();
                } else {
                    alert('Gagal menghapus alamat: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menghapus alamat');
            });
        });

        document.querySelectorAll('.set-default').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                
                fetch(`/pembeli/alamat/${id}/set-default`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        window.location.reload();
                    } else {
                        alert('Gagal mengubah alamat utama: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat mengubah alamat utama');
                });
            });
        });

        document.querySelectorAll('.edit-address').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                const label = this.getAttribute('data-label');
                const deskripsi = this.getAttribute('data-deskripsi');
                const isDefault = this.getAttribute('data-default') === '1';
                
                document.getElementById('edit_id_alamat').value = id;
                document.getElementById('edit_label_alamat').value = label;
                document.getElementById('edit_deskripsi_alamat').value = deskripsi;
                document.getElementById('edit_is_default').checked = isDefault;
                
                const modal = new bootstrap.Modal(document.getElementById('editAddressModal'));
                modal.show();
            });
        });

        document.querySelectorAll('.delete-address').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.getAttribute('data-id');
                document.getElementById('delete_id_alamat').value = id;
                const modal = new bootstrap.Modal(document.getElementById('deleteAddressModal'));
                modal.show();
            });
        });
    });
</script>
@endsection