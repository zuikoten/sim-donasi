@extends('layouts.guest')

@section('title', 'Login - Sistem Donasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Sistem Donasi</h2>
                        <p class="text-muted">Masuk ke akun Anda</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-info" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                                    <i class="bi bi-eye-slash" id="toggle-password-icon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me -->
                        <div class="mb-3 form-check">
                            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                            <label class="form-check-label" for="remember_me">
                                Ingat Saya
                            </label>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                Masuk
                            </button>
                        </div>
                    </form>

                    <!-- Link ke Register dan Forgot Password -->
                    <div class="text-center mt-3">
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none me-3" href="{{ route('password.request') }}">
                                Lupa Password?
                            </a>
                        @endif
                        
                        <span class="text-muted">Belum punya akun?</span>
                        <a href="{{ route('register') }}" class="text-decoration-none fw-bold">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection