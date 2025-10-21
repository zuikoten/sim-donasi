<section>
    <header>
        <h6 class="mt-1 text-sm text-gray-600">Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.</h6>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="current_password" class="form-label">Password Saat Ini</label>
            <div class="input-group">
                <input id="current_password" name="current_password" type="password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" id="toggle-current-password">
                    <i class="bi bi-eye-slash" id="toggle-current-password-icon"></i>
                </button>
            </div>
            @error('current_password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label for="password" class="form-label">Password Baru</label>
            <div class="input-group">
                <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="toggle-password">
                    <i class="bi bi-eye-slash" id="toggle-password-icon"></i>
                </button>
            </div>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- ... -->
        <div>
            <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
            <div class="input-group">
                <input id="password_confirmation" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="new-password">
                <button class="btn btn-outline-secondary" type="button" id="toggle-password_confirmation">
                    <i class="bi bi-eye-slash" id="toggle-password_confirmation_icon"></i>
                </button>
            </div>
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        <!-- ... -->

        <div class="flex items-center gap-4">
            <button type="submit" class="btn btn-primary">Simpan</button>

            @if (session('status') === 'password-updated')
                <div class="alert alert-success mt-2" role="alert">
                    Tersimpan.
                </div>
            @endif
        </div>
    </form>
</section>