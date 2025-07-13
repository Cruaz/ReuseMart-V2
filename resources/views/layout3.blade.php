<!DOCTYPE html>
<html lang="en" data-bs-theme="lignt">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet"/>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body class="@yield('body-class')">
	<nav id="navbar" class="navbar navbar-expand-lg py-3 fixed-top text-white navbar-dark bg-dark">
		<div class="container-fluid px-5">
			<a class="navbar-brand mb-0 h1" href="#">
				<img src="{{asset('Logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
				ReuseMart
			</a>
			<div class="collapse navbar-collapse" id="navbarText">
				<div class="d-flex align-items-center ms-auto">
					<a href="{{url('profilePembeli')}}" id="profileButton" class="">
						<button class="btn btn-outline-light px-5 py-2" id="profileBtn">Profile</button>
					</a>
				</div>
			</div>
		</div>
	</nav>
	<style>
		.with-navbar-padding {
			padding-top: 70px;
		}
	</style>

	<div class="w-100 h-100">
		@yield('content')
	</div>
	<footer class="text-center text-lg-start bg-body-tertiary text-muted">
		<section class="">
			<div class="container text-center text-md-start mt-5">
				<div class="row mt-3">
					<div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4">
						<h6 class="fs-5 fw-bold mb-4">
							<img src="{{asset('Logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">ReuseMart
						</h6>
						<p>
							Here you can use rows and columns to organize your footer content. Lorem ipsum
							dolor sit amet, consectetur adipisicing elit.
						</p>

					</div>
					<div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
						<h6 class=" fw-bold mb-4 fs-5 ">Contact</h6>
						<p><i class="fa-solid fa-location-pin me-3"></i>Jl. Babarsari No.43, Janti, Caturtunggal, Kec. Depok, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55281</p>
						<p>
							<i class="fas fa-envelope me-3"></i>
							ReuseMart@gmail.com
						</p>
						<p><i class="fas fa-phone me-3"></i>+62 812-6806-5625</p>
					</div>
				</div>
			</div>
		</section>
		<div class="text-center p-4">
			Copyright ©2024 All right reverved to kelompok 4
		</div>
	</footer>
	<script src="{{ asset('js/Script.js') }}"></script>
	@if (session('success'))
		<script>
			toastr.success("{{ session('success') }}");
		</script>
	@endif

	@if (session('error'))
		<script>
			toastr.error("{{ session('error') }}");
		</script>
	@endif
</body>
<script>
	document.addEventListener("DOMContentLoaded", function() {
		const token = localStorage.getItem('token');
		if (token) {
			fetch('/api/UserData', { 
				method: 'GET',
				headers: {
					'Authorization': `Bearer ${token}`
				}
			})
			.then(response => response.json())
			.then(data => {
				if (data.message === "User of "+data.data.name+" Retrieved") {
					const user = data.data;
					console.log(user);
					const profilePicUrl = user.foto==null?"{{asset('images/null.jpg')}}":`/storage/profile/${user.foto}`;
					document.getElementById('profilePic').src = profilePicUrl;
					
					const profileLink = document.getElementById('profileLink');
					const profileButton = document.getElementById('profileButton');
					
					if (profileLink) profileLink.style.display = 'inline-block';
					if (profileButton) profileButton.style.display = 'none';
				}
			})
			.catch(error => console.error('Error fetching profile:', error));
		} else {
			const profileLink = document.getElementById('profileLink');
			if (profileLink) {
				profileLink.style.display = 'none';
			}
		}
	});
</script>
</html>