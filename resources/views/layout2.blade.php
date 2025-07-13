<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

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
    <div class="row g-0">
        <div class="col-4 position-relative">
            <div class="position-absolute z-1 mt-5 mx-5 mb-0">
                <a href="{{url('/')}}" class="text-decoration-none text-white" style="font-size: 1.25rem;">
                    <img src="{{asset('logo.png')}}" alt="Logo" width="30" height="24" class="d-inline-block align-text-top">
                    ReuseMart
                </a>
            </div>
            <div id="carouselExampleIndicators" class="carousel slide " data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="{{asset('images/bekas1.png')}}" class="object-fit-cover d-block w-100 vh-100 img-fluid" style="filter: brightness(50%)" alt="{{asset('images/images.png')}}">
                    </div>
                    <div class="carousel-item">
                        <img src="{{asset('images/bekas2.png')}}" class="object-fit-cover d-block w-100 vh-100 img-fluid" style="filter: brightness(50%)" alt="{{asset('images/images.png')}}">
                    </div>
                    <div class="carousel-item">
                        <img src="{{asset('images/bekas3.png')}}" class="object-fit-cover d-block w-100 vh-100 img-fluid" style="filter: brightness(50%)" alt="{{asset('images/images.png')}}">
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" hidden aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" hidden aria-hidden="true"></span>
                </button>
            </div>
        </div>

        <div class="col-8">
            @yield('content')
        </div>
    </div>
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="errorToast" class="toast align-items-center text-bg-danger border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body" id="toastBody">
					Error
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <script>
        function showToast(message) {
            const toastBody = document.getElementById('toastBody');
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(document.getElementById('errorToast'));
            toast.show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            
            if (loginForm) {
                loginForm.addEventListener('submit', async function(e) {
                    e.preventDefault();
                    
                    const email = document.getElementById('formGroupExampleInput').value;
                    const password = document.getElementById('formGroupExampleInput2').value;

                    try {
                        const response = await fetch('/api/login', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                email: email,
                                password: password
                            })
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            throw new Error(data.message || 'Login failed');
                        }

                        if (data.access_token) {
                            localStorage.setItem('token', data.access_token);
                            const role = Number(data.user.role);
                            window.location.href = role === 0 ? '/' : '/Dashboard';
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        showToast(error.message || 'Something went wrong. Please try again.');
                    }
                });
            }
        });
    </script>
</body>
</html>