@extends('Layout2')

@section('title', 'Register Pembeli')

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
			<p class="fw-light" style="opacity: 70%;">Register your account</p>
		</div>
		<form class="mt-2 mx-5" id="registerForm">
			<div class="mb-3">
				<label for="username" class="form-label fw-bold fs-5">Username</label>
				<input type="text" class="form-control p-3 border-info rounded-3 shadow" id="username" name="username" placeholder="Input Your Username.....">
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
				<input type="password" class="form-control p-3 border-info rounded-3 shadow mb-2" 
					id="password_confirmation" name="password_confirmation" 
					placeholder="Confirm Your Password.....">
				<a href="{{ url('/registerOrganisasi') }}">Ingin register sebagai organisasi?</a>
			</div>
			<button type="submit" class="btn btn-info text-white py-2 px-5 mt-4" id="submitBtn">Sign Up</button>
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

		form.addEventListener('submit', async function(e) {
			e.preventDefault();

			submitBtn.disabled = true;
			submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Processing...';

			const formData = {
				username: usernameInput.value.trim(),
				email: emailInput.value.trim(),
				password: passwordInput.value
			};

			if (!formData.username) {
				alert('Username is required');
				return resetButton();
			}

			if (!formData.email.includes('@')) {
				alert('Please enter a valid email address');
				return resetButton();
			}

			if (formData.password !== confirmPasswordInput.value) {
				alert('Password and confirmation do not match');
				return resetButton();
			}

			if (formData.password.length < 8) {
				alert('Password must be at least 8 characters');
				return resetButton();
			}

			try {
				const registerResponse = await fetch('/api/pembeli/register', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/json',
						'Accept': 'application/json'
					},
					body: JSON.stringify(formData)
				});

				const registerData = await registerResponse.json();

				if (!registerResponse.ok) {
					throw new Error(registerData.message || 'Registrasi gagal');
				}

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
			submitBtn.textContent = 'Sign Up';
		}
	});
</script>
@endsection