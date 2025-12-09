<!-- Awal Sidebar -->
<div class="sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center py-3" style="padding-left: 1.25rem;">

        <h5 class="text-white m-0">Menu</h5>
        <!-- TOMBOL TUTUP (Hanya terlihat di mobile) -->
        <button class="btn btn-link text-white d-md-none" id="sidebarClose">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <ul class="nav flex-column">

        <li class="nav-item">
            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                <i class="bi bi-speedometer2"></i>&nbsp; Dashboard
            </a>
        </li>


        <!-- Awal Accordion Laporan -->
        @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('laporan/*') ? 'active' : '' }}" href="#"
                    data-bs-toggle="collapse" data-bs-target="#laporanSubmenu" role="button"
                    aria-expanded="{{ request()->is('laporan/*') ? 'true' : 'false' }}" aria-controls="laporanSubmenu">
                    <i class="bi bi-file-earmark-text"></i>&nbsp; Laporan
                    <i class="bi bi-chevron-down ms-auto" id="laporanChevron"></i>
                </a>
                <div class="collapse {{ request()->is('laporan/*') ? 'show' : '' }}" id="laporanSubmenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('laporan/donasi*') ? 'active' : '' }}"
                                href="{{ route('reports.donations') }}">
                                <i class="bi bi-circle"></i> Laporan Donasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('laporan/penyaluran*') ? 'active' : '' }}"
                                href="{{ route('reports.distributions') }}">
                                <i class="bi bi-circle"></i> Laporan Distribusi
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('laporan/program*') ? 'active' : '' }}"
                                href="{{ route('reports.programs') }}">
                                <i class="bi bi-circle"></i> Laporan Program
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('laporan/bank-accounts*') ? 'active' : '' }}"
                                href="{{ route('reports.bank-accounts') }}">
                                <i class="bi bi-circle"></i> Laporan Rekening Bank
                            </a>
                        </li>

                    </ul>
                </div>
            </li>
        @endif
        <!-- Akhir Accordion Laporan -->

        @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('programs*') ? 'active' : '' }}"
                    href="{{ route('programs.index') }}">
                    <i class="bi bi-collection"></i>&nbsp; Program Donasi
                </a>
            </li>
        @endif
        <li class="nav-item">
            <a class="nav-link {{ request()->is('donations*') ? 'active' : '' }}"
                href="{{ route('donations.index') }}">
                <i class="bi bi-cash-stack"></i>&nbsp; Donasi
            </a>
        </li>

        @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('beneficiaries*') ? 'active' : '' }}"
                    href="{{ route('beneficiaries.index') }}">
                    <i class="bi bi-people"></i>&nbsp; Penerima Manfaat
                </a>
            </li>

            <li class="nav-item">
                <a class="nav-link {{ request()->is('distributions*') ? 'active' : '' }}"
                    href="{{ route('distributions.index') }}">
                    <i class="bi bi-truck"></i>&nbsp; Penyaluran Donasi
                </a>
            </li>
        @endif

        <li class="nav-item">
            <a class="nav-link {{ request()->is('notifications*') ? 'active' : '' }}"
                href="{{ route('notifications.index') }}">
                <i class="bi bi-bell"></i>&nbsp; Notifikasi
                @if (Auth::user()->notifications()->where('status_baca', false)->count() > 0)
                    <span
                        class="badge bg-danger">{{ Auth::user()->notifications()->where('status_baca', false)->count() }}</span>
                @endif
            </a>
        </li>


        <!-- Awal Accordion Manajemen Pengguna -->
        @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
            <li class="nav-item">
                <a class="nav-link {{ request()->is('users*') || request()->is('roles*') ? 'active' : '' }}"
                    href="#" data-bs-toggle="collapse" data-bs-target="#manajemenPenggunaSubmenu" role="button"
                    aria-expanded="{{ request()->is('users*') || request()->is('roles*') ? 'true' : 'false' }}"
                    aria-controls="manajemenPenggunaSubmenu">
                    <i class="bi bi-people"></i>&nbsp; Manajemen Pengguna
                    <i class="bi bi-chevron-down ms-auto" id="manajemenPenggunaChevron"></i>
                </a>
                <div class="collapse {{ request()->is('users*') || request()->is('roles*') ? 'show' : '' }}"
                    id="manajemenPenggunaSubmenu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}">
                                <i class="bi bi-circle"></i> Pengguna
                            </a>
                        </li>

                        @if (Auth::user()->isSuperAdmin())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('roles*') ? 'active' : '' }}"
                                    href="{{ route('roles.index') }}">
                                    <i class="bi bi-circle"></i> Role
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </li>
        @endif
        <!-- Akhir Accordion Manajemen Pengguna -->


        <li class="nav-item mt-3">
            <a class="nav-link" href="{{ route('home') }}" target="_blank">
                <i class="bi bi-globe"></i>&nbsp; Lihat Situs Publik
            </a>
        </li>
    </ul>
</div>
<!-- Akhir Sidebar -->

<!-- resources/views/layouts/sidebar.blade.php -->

<style>
    .sidebar,
    .sidebar * {
        pointer-events: auto !important;
    }

    /* Nonaktifkan klik ketika sidebar tersembunyi */
    .sidebar.closed {
        pointer-events: none;
    }

    /* Aktifkan klik ketika sidebar terbuka */
    .sidebar.open {
        pointer-events: auto;
    }

    /* --- Sidebar --- */
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        width: 250px;
        background-color: #343a40;
        z-index: 1040;
        overflow-y: auto;
        /* Default (Desktop): Sidebar terlihat */
        transform: translateX(0);
        transition: transform 0.3s ease-in-out;
    }

    .sidebar-header {
        background-color: #212529;
    }

    .sidebar .nav-link {
        color: rgba(255, 255, 255, 0.8);
        padding: 12px 20px;
        display: flex;
        align-items-center;
    }

    /* --- Overlay --- */
    .sidebar-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1035;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        /* Default (Desktop): Overlay disembunyikan */
        display: none;
    }

    /* --- Main Content --- */
    .main-content {
        /* Default (Desktop): Konten memiliki margin */
        margin-left: 250px;
        min-height: 100vh;
        transition: margin-left 0.3s ease-in-out;
    }

    /* --- Style Link (Tidak Berubah) --- */
    .sidebar .nav-item>.nav-link:hover {
        color: white;
        background-color: #0a58ca;
    }

    .sidebar .nav-item>.nav-link.active {
        color: white;
        background-color: #0d6efd;
    }

    .sidebar .nav-link[data-bs-toggle="collapse"] {
        cursor: pointer;
    }

    .sidebar .nav-link[data-bs-toggle="collapse"]>i.bi-chevron-down {
        transition: transform 0.3s ease;
    }

    .sidebar .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]>i.bi-chevron-down {
        transform: rotate(180deg);
    }

    .sub-menu {
        padding-left: 2.5rem;
    }

    .sub-menu .nav-link {
        font-size: 0.9em;
        padding: 8px 20px;
    }

    .sub-menu .nav-link i.bi-circle {
        font-size: 0.6rem;
        margin-right: 10px;
    }

    .sidebar .sub-menu .nav-link:hover {
        color: white;
        background-color: #5a9aff;
    }

    .sidebar .sub-menu .nav-link.active {
        color: white;
        background-color: #6aa6ff;
    }

    /* --- Tombol --- */
    #sidebarToggle,
    #sidebarClose {
        border: none;
        padding: 0.25rem 0.5rem;
    }

    #sidebarToggle:hover,
    #sidebarClose:hover {
        background-color: rgba(0, 0, 0, 0.05);
    }

    /* --- PERBAIKAN: ATURAN UNTUK LAYAR MOBILE --- */
    @media (max-width: 767.98px) {

        /* 1. Set status awal untuk Mobile */
        .sidebar {
            transform: translateX(-100%);
            /* Awalnya tersembunyi */
        }

        .main-content {
            margin-left: 0;
            /* Awalnya tidak ada margin */
        }

        .sidebar-overlay {
            display: block;
            /* Siapkan overlay untuk mobile */
        }

        /* 2. Aturan TOGGLE untuk Mobile */
        body.sidebar-open .sidebar {
            transform: translateX(0);
            /* Tampilkan sidebar */
        }

        body.sidebar-open .sidebar-overlay {
            opacity: 1;
            visibility: visible;
        }
    }

    /* --- PERBAIKAN: ATURAN TOGGLE UNTUK DESKTOP --- */
    @media (min-width: 768px) {

        /* Aturan TOGGLE untuk Desktop */
        body.sidebar-open .sidebar {
            transform: translateX(-100%);
            /* Sembunyikan sidebar */
        }

        body.sidebar-open .main-content {
            margin-left: 0;
            /* Perluas konten utama */
        }

        /* Pastikan overlay tidak muncul di desktop saat toggle */
        body.sidebar-open .sidebar-overlay {
            display: none !important;
        }
    }
</style>
