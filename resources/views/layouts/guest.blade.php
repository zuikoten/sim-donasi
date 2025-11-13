<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Donatur')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo"
                style="height: 40px; margin-right: 2px;"><a class="navbar-brand"
                href="{{ route('home') }}">{{ $settings['site_title'] ?? 'Sistem Donasi' }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Link untuk Publik (Selalu Terlihat) -->
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('home') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('public.reports') }}">Transparansi</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('about') }}">Tentang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('contact') }}">Kontak</a>
                    </li>

                    <!-- KONDISI: JIKA BELUM LOGIN -->
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                    @endguest

                    <!-- KONDISI: JIKA SUDAH LOGIN -->
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <!-- Logika link dashboard untuk yang donatur dan yang bukan -->
                                @if (Auth::user()->isDonatur())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('donatur.dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2"></i> Dashboard
                                        </a>
                                    </li>
                                @endif
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                        <i class="bi bi-person"></i> Profil Saya
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5>{{ $settings['site_title'] ?? 'Sistem Donasi' }}</h5>
                    <p>{{ $settings['site_description'] ?? 'Platform transparansi donasi untuk program ZISWAF' }}</p>
                </div>
                <div class="col-md-4">
                    <h5>Link Cepat</h5>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-white">Beranda</a></li>
                        <li><a href="{{ route('public.reports') }}" class="text-white">Transparansi</a></li>
                        <li><a href="{{ route('about') }}" class="text-white">Tentang</a></li>
                        <li><a href="{{ route('contact') }}" class="text-white">Kontak</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Kontak</h5>
                    <p><i class="bi bi-geo-alt"></i> {{ $settings['office_address'] ?? 'Alamat Belum Diatur' }}</p>
                    <p><i class="bi bi-telephone"></i> @php $phoneSetting = App\Models\Setting::where('key', 'office_phone')->first();@endphp
                        {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] ?? 'Telepon Belum Diatur' }}
                    </p>
                    <p><i class="bi bi-envelope"></i> {{ $settings['office_email'] ?? 'Email Belum Diatur' }}</p>
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} Sistem Donasi. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Form Logout (Diperlukan untuk link logout) -->
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TAMBAHKAN SCRIPT INI -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Fungsi untuk toggle password
            function setupPasswordToggle(toggleButtonId, inputId, iconId) {
                const toggleButton = document.getElementById(toggleButtonId);
                const passwordInput = document.getElementById(inputId);
                const icon = document.getElementById(iconId);

                if (toggleButton && passwordInput && icon) {
                    toggleButton.addEventListener('click', function() {
                        const type = passwordInput.getAttribute('type') === 'password' ? 'text' :
                            'password';
                        passwordInput.setAttribute('type', type);

                        // Toggle icon
                        if (type === 'text') {
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        } else {
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        }
                    });
                }
            }

            // Cek apakah kita berada di halaman login atau register
            if (document.getElementById('password')) {
                setupPasswordToggle('toggle-password', 'password', 'toggle-password-icon');
            }

            if (document.getElementById('password_confirmation')) {
                setupPasswordToggle('toggle-password_confirmation', 'password_confirmation',
                    'toggle-password_confirmation-icon');
            }
        });
    </script>

    @stack('scripts')


</body>

</html>
