<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>ReuseMart - Dashboard - @yield('title')</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
	<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

	<style>
	</style>
</head>

<body style="height:100vh;" class="overflow-x-hidden">
	<nav id="navbar" class="d-md-block d-none navbar navbar-expand-lg bg-white shadow py-3 fixed-top z-1">
		<div class="container-fluid gx-5 px-5">
			<a class="navbar-brand mb-0 h1 col-5" href="#">
				<img src="{{asset('Logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
				ReuseMart
			</a>
			<div class="row col-5 justify-content-end" id="navbarText">
				 <div class="col-9">
					<input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="search" oninput="fetchData(1)">
				</div>

			</div>
		</div>
	</nav>

	<div class="row vh-100 align-items-center">
		<div class="col-2">
			<header class="h-100 fixed-top z-0">
				<div class="d-flex flex-column flex-shrink-0 p-3 text-black h-100 bg-white shadow" style="width: 280px;">
					<ul class="nav nav-pills flex-column mb-auto" style="margin-top: 5rem;">
						<li class="nav-item">
							<a href="{{url('/reqDonasi')}}" class="nav-link shadow mt-3 border {{ (request()->is('reqDonasi')) ? 'active border-white' : 'border-info text-black' }}" aria-current="page">
								<i class="fa-solid fa-house me-3"></i>Request
							</a>
						</li>
						<li>
							<a href="{{url('/donasi')}}" class="nav-link shadow mt-3 border {{ (request()->is('donasi')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Donasi
							</a>
						</li>
						<li>
							<a href="{{url('/alokasi')}}" class="nav-link shadow mt-3 border {{ (request()->is('alokasi')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Barang
							</a>
						</li>
						<li>
							<a href="{{url('/historyDonasi')}}" class="nav-link shadow mt-3 border {{ (request()->is('historyDonasi')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>History
							</a>
						</li>
						<li>
							<a href="{{url('/laporanBarang')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanBarang')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Barang 
							</a>
						</li>
						<li>
							<a href="{{url('/laporanPenitipan')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanPenitipan')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Penitipan
							</a>
						</li>
						<li>
							<a href="{{url('/laporanDonasi')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanDonasi')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Donasi Barang
							</a>
						</li>
						<li>
							<a href="{{url('/laporanRequest')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanRequest')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Request Donasi
							</a>
						</li>
						<li>
							<a href="{{url('/listPenitipLaporan')}}" class="nav-link shadow mt-3 border {{ (request()->is('listPenitipLaporan')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Transaksi Penitip
							</a>
						</li>
						<li>
							<a href="{{url('/laporanBulanan')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanBulanan')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Penjualan Bulanan Keseluruhan
							</a>
						</li>
						<li>
							<a href="{{url('/laporanKomisiProduk')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanKomisiProduk')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Komisi Bulanan per Produk
							</a>
						</li>
						<li>
							<a href="{{url('/laporanStok')}}" class="nav-link shadow mt-3 border {{ (request()->is('laporanStok')) ? 'active border-white' : 'border-info text-black' }}">
								<i class="fa-solid fa-briefcase me-3"></i>Laporan Stok Gudang
							</a>
						</li>
					</ul>
					<hr>
					<div class="d-flex justify-content-end">
						<a class="text-end text-black" href="javascript:void(0)" onclick="logout()">
							<i class="fa-solid fa-arrow-right-from-bracket me-2"></i>Logout
						</a>
					</div>
				</div>
			</header>
		</div>
		<div class="col-10">
			<div class="container-fluid">
				@yield('content')
			</div>
		</div>
	</div>

	<footer class="fixed-bottom" style="z-index: -1;">
		<div class="text-center p-4">
			Copyright ©2024 All right reserved to kelompok 4
		</div>
	</footer>

	<script>
		function logout() {
			const token = localStorage.getItem('token');
			if (token) {
				axios.post('/api/logout', {}, {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
				.then(() => {
					localStorage.removeItem('token');
					window.location.href = '/login';
				})
				.catch(error => {
					console.error('Error during logout:', error);
					localStorage.removeItem('token');
					window.location.href = '/login';
				});
			} else {
				window.location.href = '/login';
			}
		}
	</script>
</body>

</html>
