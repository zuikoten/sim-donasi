@extends('layouts.app')

@section('title', 'Tambah Pengguna Baru')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Tambah Pengguna Baru</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('users.store') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">Username</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" required pattern="^\S+$"
                            title="Username tidak boleh mengandung spasi">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="role_id" class="form-label">Role</label>
                        <select class="form-select @error('role_id') is-invalid @enderror" id="role_id" name="role_id"
                            required>
                            <option value="" selected disabled>-- Pilih Role --</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}"
                                    {{ old('role_id') == $role->id || $role->name == 'donatur' ? 'selected' : '' }}>
                                    {{ $role->name }}</option>
                            @endforeach
                        </select>
                        @error('role_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" id="status" name="status"
                            required>
                            <option value="aktif" {{ old('status') == 'aktif' ? 'selected' : '' }}>Aktif</option>
                            <option value="non-aktif" {{ old('status') == 'non-aktif' ? 'selected' : '' }}>Non-aktif
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
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
