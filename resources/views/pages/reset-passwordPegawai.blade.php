@extends('Layout2')

@section('title', 'Login')

@section('content')
<div class="w-75 mx-auto">
	<div class="container mt-5">
		<div class="d-flex justify-content-end align-items-center mb-5">
			<h5 class="fw-light">Doesn’t have an account?</h5>
			<div class="ms-3">
				<a href="{{url('registerPembeli')}}" class="btn btn-outline-dark px-5 py-2">Register</a>
			</div>
		</div>
		<div class="mx-5 mt-5">
			<h2 class="display-5 fw-bold">Welcome to ReuseMart</h2>
			<p class="fw-light" style="opacity: 70%;">Reset Your Password</p>
		</div>
		<form class="mt-5 mx-5" action="{{ url('reset-passwordPegawai') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="username" class="form-label fw-bold fs-5">Username</label>
                <input type="text" name="username" class="form-control p-3 border-info rounded-3 shadow" id="username" placeholder="Input Your Username..." required>
            </div>
            <button type="submit" class="btn btn-info text-white py-2 px-5 mt-5">Confirm</button>
        </form>
	</div>

</div>

@endsection