@extends('layouts.guest')

@section('title', 'Register - Sistem Donasi')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h2 class="fw-bold">Sistem Donasi</h2>
                            <p class="text-muted">Buat akun baru</p>
                        </div>

                        <form method="POST" action="{{ route('register') }}">
                            @csrf

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label">Username</label>
                                <input id="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" name="name"
                                    value="{{ old('name') }}" required autofocus autocomplete="name" pattern="^\S+$"
                                    title="Username tidak boleh mengandung spasi">
                                @error('name')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input id="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="username">
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
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
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

                            <!-- Confirm Password -->
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <div class="input-group">
                                    <input id="password_confirmation" type="password"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        name="password_confirmation" required autocomplete="new-password">
                                    <button class="btn btn-outline-secondary" type="button"
                                        id="toggle-password_confirmation">
                                        <i class="bi bi-eye-slash" id="toggle-password_confirmation-icon"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    Daftar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('name');

            // fungsi untuk buat tooltip
            function showTooltip(message) {
                // hapus tooltip lama kalau ada
                const oldTooltip = document.querySelector('.tooltip-error');
                if (oldTooltip) oldTooltip.remove();

                // buat elemen tooltip baru
                const tooltip = document.createElement('div');
                tooltip.className = 'tooltip-error';
                tooltip.textContent = message;

                // styling balon merah
                Object.assign(tooltip.style, {
                    position: 'absolute',
                    background: '#f44336',
                    color: 'white',
                    padding: '6px 10px',
                    borderRadius: '6px',
                    fontSize: '0.85rem',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.2)',
                    top: (input.offsetTop + input.offsetHeight + 6) + 'px',
                    left: input.offsetLeft + 'px',
                    zIndex: 1000,
                    animation: 'fadeIn 0.2s ease'
                });

                // tambahkan ke body
                input.parentElement.appendChild(tooltip);

                // otomatis hilang setelah 2 detik
                setTimeout(() => tooltip.remove(), 2000);
            }

            // cegah spasi dan tampilkan tooltip
            input.addEventListener('keydown', function(e) {
                if (e.key === ' ') {
                    e.preventDefault();
                    showTooltip('❌ Username tidak boleh mengandung spasi');
                }
            });
        });
    </script>
@endpush
