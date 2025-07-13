@extends('Layout2')

@section('title', 'Login')

@section('content')
<div class="w-75 mx-auto">
	<div class="container mt-5">
		<div class="d-flex justify-content-end align-items-center mb-5">
			<h5 class="fw-light">Doesn’t have an account?</h5>
			<div class="ms-3">
				<a href="{{ url('registerPembeli') }}" class="btn btn-outline-dark px-5 py-2">Register</a>
			</div>
		</div>
		<div class="mx-5 mt-5">
			<h2 class="display-5 fw-bold">Welcome to ReuseMart</h2>
			<p class="fw-light" style="opacity: 70%;">Log In to your account</p>
		</div>

		@if (request()->has('success'))
			<div class="alert alert-success mt-3">
				{{ request()->get('success') }}
			</div>
		@endif

		@if (session('success'))
			<div class="alert alert-success mt-3">
				{{ session('success') }}
			</div>
		@endif

		@if (session('error'))
			<div class="alert alert-danger mt-3">
				{{ session('error') }}
			</div>
		@endif

		<form method="POST" action="{{ route('login') }}" class="mt-5 mx-5">
			@csrf
			<div class="mb-3">
				<label class="form-label fw-bold fs-5 mt-3">Email</label>
				<input type="email" class="form-control p-3 border-info rounded-3 shadow" name="email" placeholder="Input Your Email ....." required>
			</div>
			<div class="mb-3">
				<label class="form-label fw-bold fs-5 mt-3">Password</label>
				<input type="password" class="form-control p-3 border-info rounded-3 shadow mb-2" name="password" placeholder="Input Your Password....." required>
				<div class="d-flex justify-content-between align-items-center">
					<a href="{{ url('/reset-password') }}">Forgot Password?</a>
					
					{{-- Tombol login pegawai --}}
					<a href="{{ route('loginPegawai') }}" class="btn btn-sm btn-outline-secondary">Login sebagai Pegawai</a>
				</div>
			</div>

			<button type="submit" class="btn btn-info text-white py-2 px-5 mt-4">Login</button>
		</form>
	</div>
</div>
@endsection
