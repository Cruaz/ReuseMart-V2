<!DOCTYPE html>
<html lang="en" data-bs-theme="lignt">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>@yield('title')</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
	<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet" crossorigin="anonymous">
	<link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>

<body>
	<nav id="navbar" class="navbar navbar-expand-lg py-3 fixed-top text-white navbar-dark bg-dark">
		<div class="container-fluid px-5">
			<a class="navbar-brand mb-0 h1" href="#">
				<img src="{{asset('Logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
				ReuseMart
			</a>
			<div class="collapse navbar-collapse" id="navbarText">
				<div class="d-flex align-items-center ms-auto">
					<a href="{{url('profilePenitip')}}" id="profileButton" class="">
						<button class="btn btn-outline-light px-5 py-2" id="profileBtn">Profile</button>
					</a>
				</div>
			</div>
		</div>
	</nav>

	<div class="w-100 h-100">
		@yield('content')
	</div>
	<script src="{{ asset('js/Script.js') }}"></script>
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
						document.getElementById('profileLink').style.display = 'inline-block';
						document.getElementById('profileButton').style.display = 'none';
					}
				})
				.catch(error => console.error('Error fetching profile:', error));
		} else {

		}
	});
</script>

</html>