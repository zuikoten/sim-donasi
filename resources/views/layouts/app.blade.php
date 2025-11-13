<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Informasi Donatur')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/pagination.css') }}" rel="stylesheet">

    {{-- Dynamic Title --}}
    <title>@yield('title', setting('site_title', 'Sistem Informasi Donatur'))</title>

    {{-- Dynamic Favicon --}}
    @if (setting('favicon'))
        @php
            $faviconPath = asset('storage/' . setting('favicon'));
            $extension = pathinfo(setting('favicon'), PATHINFO_EXTENSION);
            $mimeType = match ($extension) {
                'png' => 'image/png',
                'svg' => 'image/svg+xml',
                'ico' => 'image/x-icon',
                default => 'image/x-icon',
            };
        @endphp
        <link rel="icon" href="{{ $faviconPath }}" type="{{ $mimeType }}">
        <link rel="shortcut icon" href="{{ $faviconPath }}" type="{{ $mimeType }}">
    @endif

    @stack('styles')

    <style>
        /* --- CSS untuk Memperbaiki Tampilan Pagination --- */
        /* Perbaiki untuk pagination Bootstrap 5.3+ */
        .pagination {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            padding-left: 0;
            list-style: none;
        }

        .pagination .page-link {
            position: relative;
            display: block;
            color: #0d6efd;
            text-decoration: none;
            background-color: #fff;
            border: 1px solid #dee2e6;
            transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out;
            padding: 0.375rem 0.75rem;
            /* Ukuran tombol lebih kecil */
            font-size: 0.875rem;
            /* Ukuran font lebih kecil */
        }

        .pagination .page-link:hover {
            z-index: 2;
            color: #0a58ca;
            background-color: #e9ecef;
            border-color: #dee2e6;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Perbaiki untuk tombol Previous/Next */
        .pagination .page-item:not(:first-child) .page-link,
        .pagination .page-item:not(:last-child) .page-link {
            padding: 0.375rem 0.75rem;
            font-size: 0.875rem;
        }
    </style>
</head>


<body>
    @include('layouts.sidebar')

    <!-- Overlay untuk menutup sidebar saat di luar area -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="main-content">
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container-fluid">
                <!-- TOMBOL HAMBURGER (DIUBAH KELASNYA) -->
                <button class="btn btn-link d-block me-2" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                @if (setting('logo'))
                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo"
                        style="height: 40px; margin-right: 2px;">
                @endif
                <a class="navbar-brand" href="{{ route('dashboard') }}">
                    <h5 class="text-black text-center m-0">{{ setting('site_title', 'Sistem Donasi') }}</h5>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle"></i>
                                {{ Auth::user()->profile->nama_lengkap ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.details') }}"><i
                                            class="bi bi-person"></i> Profil</a></li>
                                <li><a class="dropdown-item" href="{{ route('notifications.index') }}">
                                        <i class="bi bi-bell"></i> Notifikasi
                                        @if (Auth::user()->notifications()->where('status_baca', false)->count() > 0)
                                            <span
                                                class="badge bg-danger">{{ Auth::user()->notifications()->where('status_baca', false)->count() }}</span>
                                        @endif
                                    </a></li>
                                <li><a class="dropdown-item" href="{{ route('admin.settings.index') }}"><i
                                            class="bi bi-gear"></i> Settings</a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right"></i> Logout
                                    </a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <div class="container-fluid p-4">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>

    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- JAVASCRIPT UNTUK SIDEBAR -->
    <script>
        // KODE BARU YANG SUDAH DIPERBAIKI
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarClose = document.getElementById('sidebarClose');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            function openSidebar() {
                body.classList.add('sidebar-open');
            }

            function closeSidebar() {
                body.classList.remove('sidebar-open');
            }

            // --- PERBAIKAN: Tombol hamburger sekarang benar-benar toggle ---
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    body.classList.toggle('sidebar-open');
                });
            }

            // Tutup sidebar saat tombol 'X' diklik
            if (sidebarClose) {
                sidebarClose.addEventListener('click', closeSidebar);
            }

            // Tutup sidebar saat overlay diklik
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', closeSidebar);
            }
        });
    </script>

    @stack('scripts')
</body>

</html>
