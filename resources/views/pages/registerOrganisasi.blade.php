@extends('Layout2')

@section('title', 'Register Organisasi')

@section('content')
<div class="w-75 mx-auto">
	<div class="container mt-5">
		<div class="d-flex justify-content-end align-items-center mb-2 ">
			<h5 class="fw-light">Already have an account?</h5>
			<div class="ms-3">
				<a href="{{url('login')}}" class="btn btn-outline-dark px-5 py-2">Login</a>
			</div>
		</div>
		<div class="mx-5 mt-2">
			<h2 class="display-5 fw-bold">Welcome to ReUseMart</h2>
			<p class="fw-light" style="opacity: 70%;">Daftar Organisasi</p>
		</div>
		<form class="mt-2 mx-5" id="registerForm" enctype="multipart/form-data">
			<div class="mb-3">
				<label for="username" class="form-label fw-bold fs-5">Nama Organisasi</label>
				<input type="text" class="form-control p-3 border-info rounded-3 shadow" id="username" name="username" placeholder="Input Your Organization Name.....">
			</div>
			<div class="mb-3">
				<label for="email" class="form-label fw-bold fs-5">Email</label>
				<input type="email" class="form-control p-3 border-info rounded-3 shadow" id="email" name="email" placeholder="Input Your Email.....">
			</div>
			<div class="mb-3">
				<label for="password" class="form-label fw-bold fs-5">Password</label>
				<input type="password" class="form-control p-3 border-info rounded-3 shadow" id="password" name="password" placeholder="Input Your Password.....">
			</div>
			<div class="mb-3">
				<label for="password_confirmation" class="form-label fw-bold fs-5">Confirm Password</label>
				<input type="password" class="form-control p-3 border-info rounded-3 shadow" 
					id="password_confirmation" name="password_confirmation" 
					placeholder="Confirm Your Password.....">
			</div>
			<div class="mb-3">
				<label for="alamat_organisasi" class="form-label fw-bold fs-5">Alamat Organisasi</label>
				<input type="text" class="form-control p-3 border-info rounded-3 shadow" 
					id="alamat_organisasi" name="alamat_organisasi" 
					placeholder="Input Your Organisasi Address.....">
			</div>
			<div class="mb-3">
				<label for="foto" class="form-label fw-bold fs-5">Foto Organisasi</label>
				<input type="file" class="form-control p-3 border-info rounded-3 shadow" 
					id="foto" name="foto" accept="image/*">
			</div>
			<div class="mb-3">
				<a href="{{ url('/registerPembeli') }}">Register sebagai Pembeli?</a>
			</div>
			<button type="submit" class="btn btn-info text-white py-2 px-5 mt-4" id="submitBtn">Register Organisasi</button>
		</form>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const form = document.getElementById('registerForm');
		const submitBtn = document.getElementById('submitBtn');
		const usernameInput = document.getElementById('username');
		const emailInput = document.getElementById('email');
		const passwordInput = document.getElementById('password');
		const confirmPasswordInput = document.getElementById('password_confirmation');
		const alamatInput = document.getElementById('alamat_organisasi');
		const fotoInput = document.getElementById('foto');

		form.addEventListener('submit', async function(e) {
			e.preventDefault();

			submitBtn.disabled = true;
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

			if (!usernameInput.value.trim()) {
				alert('Username is required');
				return resetButton();
			}

			if (!emailInput.value.includes('@')) {
				alert('Please enter a valid email address');
				return resetButton();
			}

			if (passwordInput.value !== confirmPasswordInput.value) {
				alert('Password and confirmation do not match');
				return resetButton();
			}

			if (passwordInput.value.length < 8) {
				alert('Password must be at least 8 characters');
				return resetButton();
			}

			if (!alamatInput.value.trim()) {
				alert('Alamat Organisasi is required');
				return resetButton();
			}

			if (!fotoInput.files[0]) {
				alert('Foto Organisasi is required');
				return resetButton();
			}

			try {
				const formData = new FormData();
				formData.append('username', usernameInput.value.trim());
				formData.append('email', emailInput.value.trim());
				formData.append('password', passwordInput.value);
				formData.append('alamat_organisasi', alamatInput.value.trim());
				formData.append('foto', fotoInput.files[0]);

				const registerResponse = await fetch('/api/organisasi/register', {
					method: 'POST',
					headers: {
						'Accept': 'application/json'
					},
					body: formData
				});

				const registerData = await registerResponse.json();

				if (!registerResponse.ok) {
					throw new Error(registerData.message || 'Registration failed');
				}

				const loginResponse = await fetch('/api/login', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					body: JSON.stringify({
						email: emailInput.value.trim(),
						password: passwordInput.value
					})
				});

				window.location.href = '/login?success=Registrasi berhasil! Silakan login.';

			} catch (error) {
				console.error('Registration error:', error);
				alert(error.message || 'Terjadi kesalahan. Silakan coba lagi.');
			} finally {
				resetButton();
			}
		});

		function resetButton() {
			submitBtn.disabled = false;
			submitBtn.textContent = 'Register Organisasi';
		}
	});
</script>
@endsection