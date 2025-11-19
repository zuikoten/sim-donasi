<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Informasi Donatur')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    @stack('styles')

    <style>
        /* PREMIUM NAVBAR */
        .premium-nav {
            background: linear-gradient(135deg, #0047FF, #6A00FF, #00D9FF);
            background-size: 300% 300%;
            animation: gradientFlow 12s ease infinite;
            box-shadow: 0 4px 18px rgba(0, 0, 0, 0.25);
        }

        @keyframes gradientFlow {
            0% {
                background-position: 0% 50%;
            }

            50% {
                background-position: 100% 50%;
            }

            100% {
                background-position: 0% 50%;
            }
        }

        /* NAV LINKS */
        .premium-link {
            color: #ffffff;
            font-weight: 500;
            padding: 8px 14px;
            transition: all 0.2s ease;
        }

        .premium-link:hover {
            color: #ffffff;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.7);
            transform: translateY(-1px);
        }

        /* PREMIUM LOGIN BUTTON */
        .premium-btn-login {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(8px);
            font-weight: 500;
            color: #fff;
            border: 1px solid rgba(255, 255, 255, 0.4);
            transition: all 0.3s ease;
        }

        .premium-btn-login:hover {
            background: rgba(255, 255, 255, 0.35);
            transform: scale(1.05);
            font-weight: bolder;
            color: #0800a9;
        }

        /* SHRINK ON SCROLL */
        #premiumNavbar.navbar-shrink {
            padding-top: 4px !important;
            padding-bottom: 4px !important;
            backdrop-filter: blur(6px);
        }
    </style>
</head>

<body>
    <nav id="premiumNavbar" class="navbar navbar-expand-lg premium-nav py-3">
        <div class="container">

            <!-- LOGO -->
            <a class="navbar-brand d-flex align-items-center text-white" href="{{ route('home') }}">
                <img src="{{ isset($settings['logo']) && $settings['logo']
                    ? asset('public/storage/' . $settings['logo'])
                    : asset('public/images/default-logo.png') }}"
                    alt="Logo" height="52" class="me-2 rounded">
                <span class="fw-bold" style="font-size: 1.35rem;">
                    {{ $settings['site_title'] ?? 'Sistem Donasi' }}
                </span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler text-white" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- MENU -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">

                    <li class="nav-item mx-2">
                        <a class="nav-link premium-link" href="{{ route('home') }}">Beranda</a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link premium-link" href="{{ route('public.reports') }}">Transparansi</a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link premium-link" href="{{ route('about') }}">Tentang</a>
                    </li>

                    <li class="nav-item mx-2">
                        <a class="nav-link premium-link" href="{{ route('contact') }}">Kontak</a>
                    </li>

                    @guest
                        <li class="nav-item ms-3 mt-3 mt-lg-0">
                            <a href="{{ route('login') }}" class="btn premium-btn-login px-4 py-2 rounded-pill">
                                Login
                            </a>
                        </li>
                    @endguest

                    @auth
                        <li class="nav-item dropdown ms-3">
                            <a class="nav-link dropdown-toggle premium-link" href="#" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg">

                                @if (Auth::user()->isDonatur())
                                    <li>
                                        <a class="dropdown-item" href="{{ route('donatur.dashboard') }}">
                                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                        </a>
                                    </li>
                                @else
                                    <li>
                                        <a class="dropdown-item" href="{{ route('dashboard') }}">
                                            <i class="bi bi-speedometer2 me-1"></i> Dashboard
                                        </a>
                                    </li>
                                @endif

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item" href="{{ route('profile.details') }}">
                                        <i class="bi bi-person me-1"></i> Profil Saya
                                    </a>
                                </li>

                                <li>
                                    <hr class="dropdown-divider">
                                </li>

                                <li>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-1"></i> Logout
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

                    {{-- ============================================ --}}
                    {{-- 🆕 UPDATED: Multi-Contact Fields Support    --}}
                    {{-- ============================================ --}}

                    {{-- ADDRESS dengan Backward Compatibility --}}
                    @if (isset($address) && $address)
                        {{-- 🆕 NEW: Multi-field support --}}
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            {{ $address['value'] }}
                        </p>
                    @elseif(isset($settings['office_address']))
                        {{-- ♻️ LEGACY: Fallback ke data lama --}}
                        <p>
                            <i class="bi bi-geo-alt"></i>
                            {{ $settings['office_address'] }}
                        </p>
                    @else
                        <p><i class="bi bi-geo-alt"></i> Alamat Belum Diatur</p>
                    @endif

                    {{-- PHONE dengan Backward Compatibility --}}
                    @if (isset($phone) && $phone)
                        {{-- 🆕 NEW: Multi-field support dengan formatted number --}}
                        <p>
                            <i class="bi bi-telephone"></i>
                            <a href="tel:+{{ $phone['raw'] }}" class="text-white text-decoration-none">
                                {{ $phone['formatted'] }}
                            </a>
                        </p>
                    @elseif(isset($settings['office_phone']))
                        {{-- ♻️ LEGACY: Fallback ke data lama --}}
                        <p>
                            <i class="bi bi-telephone"></i>
                            @php
                                $phoneSetting = App\Models\Setting::where('key', 'office_phone')->first();
                            @endphp
                            {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] }}
                        </p>
                    @else
                        <p><i class="bi bi-telephone"></i> Telepon Belum Diatur</p>
                    @endif

                    {{-- EMAIL dengan Backward Compatibility --}}
                    @if (isset($email) && $email)
                        {{-- 🆕 NEW: Multi-field support --}}
                        <p>
                            <i class="bi bi-envelope"></i>
                            <a href="mailto:{{ $email['value'] }}" class="text-white text-decoration-none">
                                {{ $email['value'] }}
                            </a>
                        </p>
                    @elseif(isset($settings['office_email']))
                        {{-- ♻️ LEGACY: Fallback ke data lama --}}
                        <p>
                            <i class="bi bi-envelope"></i>
                            {{ $settings['office_email'] }}
                        </p>
                    @else
                        <p><i class="bi bi-envelope"></i> Email Belum Diatur</p>
                    @endif
                </div>
            </div>
            <hr class="bg-white">
            <div class="text-center">
                <p>&copy; {{ date('Y') }} {{ setting('site_title', 'Sistem Donasi') }}. All rights reserved.</p>
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

    <script>
        document.addEventListener("scroll", function() {
            const nav = document.getElementById("premiumNavbar");
            if (window.scrollY > 20) {
                nav.classList.add("navbar-shrink");
            } else {
                nav.classList.remove("navbar-shrink");
            }
        });
    </script>


    @stack('scripts')


</body>

</html>
