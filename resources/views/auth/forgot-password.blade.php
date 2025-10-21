@extends('layouts.guest')

@section('title', 'Reset Password - Sistem Donasi')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-4">
            <div class="card shadow">
                <div class="card-body p-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Sistem Donasi</h2>
                        <p class="text-muted">Reset Password</p>
                    </div>

                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-info" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autofocus autocomplete="email">
                            @error('email')
                                <div class="invalid-feedback d-block">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                Kirim Link Reset Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection