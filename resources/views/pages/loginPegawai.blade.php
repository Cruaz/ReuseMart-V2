@extends('Layout2')

@section('title', 'LoginPegawai')

@section('content')
<div class="w-75 mx-auto">
	<div class="container mt-5">
		<div class="mx-5 mt-5">
			<h2 class="display-5 fw-bold">Welcome to ReuseMart</h2>
			<p class="fw-light" style="opacity: 70%;">Log In to your account</p>
		</div>

		{{-- Tampilkan error jika ada --}}
		@if(session('error'))
			<div class="alert alert-danger mx-5 mt-3">{{ session('error') }}</div>
		@endif

		<form method="POST" action="{{ route('loginPegawai') }}" class="mt-5 mx-5">
			@csrf
			<div class="mb-3">
				<label class="form-label fw-bold fs-5 mt-3">Username</label>
				<input type="text" class="form-control p-3 border-info rounded-3 shadow" name="username" placeholder="Input Your Username....." required>

			</div>
			<div class="mb-3">
				<label class="form-label fw-bold fs-5 mt-3">Password</label>
				<input type="password" class="form-control p-3 border-info rounded-3 shadow mb-2" name="password" placeholder="Input Your Password....." required>

				<div class="d-flex justify-content-between align-items-center">
					<a href="{{ url('/reset-passwordPegawai') }}">Forgot Password?</a>
					<a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Login sebagai User</a>
				</div>
			</div>

			<button type="submit" class="btn btn-info text-white py-2 px-5 mt-4">Login</button>
		</form>
	</div>
</div>
@endsection
