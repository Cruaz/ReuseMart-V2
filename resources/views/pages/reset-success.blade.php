@extends('Layout2')

@section('title', 'Reset Email Sent')

@section('content')
<div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
    <div class="text-center">
        <h2 class="fw-bold">Check Your Email</h2>
        <p class="mt-3">We’ve sent a password reset link to your email. Please check your inbox or spam folder.</p>
        <a href="{{ url('/login') }}" class="btn btn-primary mt-4">Back to Login</a>
    </div>
</div>
@endsection