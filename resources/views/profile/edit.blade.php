{{-- Menggunakan layout aplikasi kita --}}
@extends('layouts.app')

@section('title', 'Profil Saya')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Profil Saya</h1>
</div>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <!-- Update Profile Information Form -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Informasi Profil</h4>
            </div>
            <div class="card-body">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>
        </div>

        <!-- Update Password Form -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Perbarui Password</h4>
            </div>
            <div class="card-body">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>

        <!-- Delete User Form -->
        <div class="card shadow-sm">
            <div class="card-header">
                <h4 class="mb-0">Hapus Akun</h4>
            </div>
            <div class="card-body">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Fungsi untuk toggle password
        function setupPasswordToggle(toggleButtonId, inputId, iconId) {
            const toggleButton = document.getElementById(toggleButtonId);
            const passwordInput = document.getElementById(inputId);
            const icon = document.getElementById(iconId);

            if (toggleButton && passwordInput && icon) {
                toggleButton.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
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
            } else {
                console.error('Elemen tidak ditemukan untuk salah satu ID. Periksa HTML Anda.');
            }
        }

        // Inisialisasi toggle untuk setiap field password
        setupPasswordToggle('toggle-current-password', 'current_password', 'toggle-current-password-icon');
        setupPasswordToggle('toggle-password', 'password', 'toggle-password-icon');
        setupPasswordToggle('toggle-password_confirmation', 'password_confirmation', 'toggle-password_confirmation_icon');
    });
</script>
@endpush
@endsection