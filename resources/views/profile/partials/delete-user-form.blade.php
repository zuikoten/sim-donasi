<section class="space-y-6">
    <header>
        <h6 class="mt-1 text-sm text-gray-600">Setelah akun Anda dihapus, semua sumber daya dan datanya akan dihapus secara permanen.</h6>
    </header>

    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
        Hapus Akun
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteAccountModalLabel">Konfirmasi Hapus Akun</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin menghapus akun Anda? Masukkan password Anda untuk konfirmasi.</p>
                        <div class="mt-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" type="password" class="form-control" placeholder="Password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Hapus Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>