@extends('AdminLayout')

@section('title', 'Dashboard')

@section('content')

<div class="">
	<div class="row ">
		<div class="col-3">
			<div class="card w-100 rounded-4">
				<div class="card-body p-5">
					<div class="d-flex justify-content-between">
						<h5 class="card-title">Booked Rooms</h5>
						<h5 class="card-title"><i class="fa-solid fa-door-open"></i></h5>
					</div>
					<h3 class="mb-2 h1  mt-3" id="BookingText"></h3>
					<p class="card-text text-body-secondary mt-3">Lorem Ipsum Dor adawa</p>
				</div>
			</div>
		</div>
		<div class="col-3">
			<div class="card w-100 rounded-4">
				<div class="card-body p-5">
					<div class="d-flex justify-content-between">
						<h5 class="card-title">Reserved Service</h5>
						<h5 class="card-title"><i class="fa-solid fa-bell-concierge"></i></h5>
					</div>
					<h3 class="mb-2 h1  mt-3" id="ReservationText"></h3>
					<p class="card-text text-body-secondary mt-3">Lorem Ipsum Dor adawa</p>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card w-100">
				<div class="card-body p-5">
					<div class="row mt-4 align-items-center">
						<div class="col-md-4">
							<canvas id="DonutUserView"></canvas>
						</div>
						<div class="col-md-8">
							<h5 class="card-title ">Users</h5>
							<h3 class="mb-2 h1 " id="PengggunaText"></h3>
							<p class="card-text text-body-secondary ">Since Last Week</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row mt-2">
		<div class="col-6">
			<div class="card w-100">
				<div class="card-body p-5">
					<h5 class="card-title">Room And Service Popularity</h5>
					<canvas id="BarRoomService"></canvas>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card w-100">
				<div class="card-body p-5">
					<div class="d-flex justify-content-between">
						<h5 class="card-title">On Going Orders</h5>
						<h5 class="card-title"><i class="fa-solid fa-arrows-rotate"></i></h5>
					</div>
					<table class=" table w-100">
						<thead>
							<tr>
								<th scope="col">User</th>
								<th scope="col">Tipe</th>
								<th scope="col">Date</th>
								<th scope="col">Status</th>
								<th scope="col">Total Harga</th>
							</tr>
						</thead>
						<tbody class="table-group-divider" id="table-body">

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.3/Chart.js"></script>
<script>
	var colors = ['#1EB7AF', '#BEA690', '#333333'];
	var BarRoomService = document.getElementById("BarRoomService");
	if (BarRoomService) {
		new Chart(BarRoomService, {
			type: 'bar',
			data: {
				labels: ["Kamar 1", "Kamar 1", "Kamar 1", "Kamar 1", "Kamar 1", "Kamar 1", "Kamar 1"],
				datasets: [{
						label: 'Online Perception',
						data: [589, 445, 483, 503, 689, 692, 634],
						backgroundColor: colors[0]
					},
					{
						label: 'Offline Perception',
						data: [639, 465, 493, 478, 589, 632, 674],
						backgroundColor: colors[1],
					}
				]
			},
			options: {
				legend: {
					position: 'bottom',
					padding: 5,
					labels: {
						pointStyle: 'circle',
						usePointStyle: true
					}
				},
				scales: {
					xAxes: [{
						barPercentage: 0.4,
						categoryPercentage: 0.5
					}]
				},
			}
		});
	}
	var donutOptions = {
		cutoutPercentage: 85,
		legend: false
	};

	var DonutUser = {
		labels: ['New', 'Active', 'Inactive'],
		datasets: [{
			backgroundColor: colors.slice(0, 3),
			borderWidth: 0,
			data: [40, 45, 30]
		}]
	};
	var DonutUserView = document.getElementById("DonutUserView");
	if (DonutUserView) {
		new Chart(DonutUserView, {
			type: 'pie',
			data: DonutUser,
			options: donutOptions
		});
	}
</script>
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
	let perPage = 7;
	const token = localStorage.getItem('token');
	function truncate(text, maxLength) {
		return text.length > maxLength ? text.substring(0, maxLength) + "..." : text;
	}
	function fetchData() {
		fetch('/api/BookingPesanan', {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
			.then(response => response.json())
			.then(data => {
				const tableBody = document.querySelector('tbody');
				data.data.forEach(item => {
					const row = `
                <tr class="mt-5 shadow">
                    <td>${item.id_users}</td>
                    <td>${item.type}</td>
                    <td>${item.type === 'Booking' ? item.CheckIn : item.Tgl_pemesanan}</td>
                    <td>${item.Status}</td>
                    <td>${item.total_harga}</td>
                </tr>
            `;
					tableBody.insertAdjacentHTML('beforeend', row);
				});
			})
			.catch(error => {
				console.error('Error fetching data:', error);
			});
		fetch('/api/Pemesanan/Count')
			.then(response => response.json())
			.then(data => {
				const select = document.getElementById('ReservationText');
				select.textContent = data.count;
			})
			.catch(error => {
				console.error('Error fetching jenis data:', error);
			});
		fetch('/api/Booking/Count')
			.then(response => response.json())
			.then(data => {
				const select = document.getElementById('BookingText');
				select.textContent = data.count;
			})
			.catch(error => {
				console.error('Error fetching jenis data:', error);
			});
		fetch('/api/User/Count', {
					headers: {
						'Authorization': `Bearer ${token}`
					}
				})
			.then(response => response.json())
			.then(data => {
				const select = document.getElementById('PengggunaText');
				select.textContent = data.count;
			})
			.catch(error => {
				console.error('Error fetching jenis data:', error);
			});
	}
	document.addEventListener('DOMContentLoaded', () => {
		fetchData();
	});
</script>
@endsection