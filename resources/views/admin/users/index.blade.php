@extends('layouts.app')

@section('title', 'Manajemen Pengguna')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Manajemen Pengguna</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary">
                <i class="bi bi-plus-circle"></i> Tambah Pengguna Baru
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <!-- Dropdown untuk memilih jumlah data per halaman -->
            <div class="col-md-3">
                <label for="perPage" class="form-label">Tampilkan</label>
                <select id="perPage" class="form-select form-select-sm">
                    <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                    <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                    <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                    <option value="500" {{ request('perPage') == 500 ? 'selected' : '' }}>500</option>
                    <option value="999999" {{ request('perPage') == 999999 ? 'selected' : '' }}>Semua</option>
                </select>
            </div>
            <!-- Akhir Dropdown -->
            @if ($users->count() > 0)
                <div class="table-responsive" style="max-height: 70vh; overflow-y: auto;">
                    <table class="table table-striped table-hover table-sticky-header">
                        <thead class="table-light">
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Tanggal Bergabung</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td><span class="badge bg-info">{{ $user->role->name }}</span></td>
                                    <td>
                                        @if ($user->status === 'aktif')
                                            <span class="badge bg-success">Aktif</span>
                                        @else
                                            <span class="badge bg-danger">Non-aktif</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y') }}</td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user->id) }}"
                                                class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user->id) }}"
                                                class="btn btn-sm btn-outline-warning" title="Edit">
                                                <i class="bi bi-pencil"></i>
                                            </a>

                                            @if ($user->id !== auth()->id())
                                                <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger"
                                                        title="Hapus">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                                <!-- tombol di setiap row -->
                                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                    data-bs-target="#editProfileModal"
                                                    data-user='@json($user)'>
                                                    Edit Profil
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            @else
                <div class="text-center py-4">
                    <p>Belum ada data pengguna.</p>
                    <a href="{{ route('users.create') }}" class="btn btn-primary">Tambah Pengguna Pertama</a>
                </div>
            @endif
        </div>
        <!-- Start Pagination -->
        <div class="card-footer d-flex justify-content-between align-items-center">
            <span class="text-muted">
                Menampilkan {{ $users->firstItem() }} hingga {{ $users->lastItem() }} dari
                {{ $users->total() }} data.
            </span>
            {{ $users->appends(request()->query())->links() }}

        </div>
        <!-- End Pagination -->
    </div>
    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">
                <div class="modal-header bg-primary text-white rounded-top-4">
                    <h5 class="modal-title fw-bold" id="editProfileModalLabel">
                        <i class="bi bi-person-lines-fill me-2"></i> Edit Profil
                        &#40;<span id="nama_user_modal">Nama_user</span>&#41;
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <form id="editProfileForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="user_id" id="modal_user_id">

                    <div class="modal-body px-4 py-3">
                        <div class="row g-3">

                            <div class="col-md-6">
                                <label for="modal_nama_lengkap" class="form-label fw-semibold">Nama
                                    Lengkap</label>
                                <input type="text" class="form-control" id="modal_nama_lengkap" name="nama_lengkap"
                                    required>
                            </div>

                            <div class="col-md-6">
                                <label for="modal_telepon" class="form-label fw-semibold">Nomor
                                    Telepon</label>
                                <input type="tel" class="form-control" id="modal_telepon" name="no_telepon"
                                    placeholder="+62...">
                            </div>

                            <div class="col-md-6">
                                <label for="modal_tanggal_lahir" class="form-label fw-semibold">Tanggal
                                    Lahir</label>
                                <input type="date" class="form-control" id="modal_tanggal_lahir" name="tanggal_lahir">
                            </div>

                            <div class="col-md-6">
                                <label for="modal_gender" class="form-label fw-semibold">Jenis
                                    Kelamin</label>
                                <select class="form-select" id="modal_gender" name="jenis_kelamin">
                                    <option value="">-- Pilih --</option>
                                    <option value="Laki-laki">Laki-laki</option>
                                    <option value="Perempuan">Perempuan</option>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label for="modal_alamat" class="form-label fw-semibold">Alamat
                                    Lengkap <em>&#40;*opsional&#41;</em></label>
                                <textarea class="form-control" id="modal_alamat" name="alamat" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label for="modal_pekerjaan" class="form-label fw-semibold">Pekerjaan
                                    <em>&#40;*opsional&#41;</em></label>
                                <input type="text" class="form-control" id="modal_pekerjaan" name="pekerjaan">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Foto Profil
                                    <em>(*opsional)</em></label>

                                <!-- Tombol custom -->
                                <button type="button" id="choosePhotoBtn"
                                    class="btn btn-outline-primary btn-sm w-100 mb-2">
                                    <i class="bi bi-image"></i> Pilih Foto
                                </button>

                                <!-- Preview hasil crop -->
                                <img id="croppedPreview" class="rounded-circle border d-block mx-auto mt-2"
                                    style="width: 120px; height: 120px; object-fit: cover; display: none;" alt="Preview">

                                <!-- Input file disembunyikan -->
                                <input type="file" id="modal_foto" name="foto" accept="image/*"
                                    style="display:none;">

                                <!-- Hasil crop disimpan di hidden input -->
                                <input type="hidden" id="cropped_image" name="cropped_image">
                            </div>

                        </div>
                    </div>

                    <div class="modal-footer bg-light rounded-bottom-4">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save2"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <!-- Sub-modal untuk Crop Image -->
    <div class="modal fade" id="cropImageModal" tabindex="-1" aria-labelledby="cropImageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered"> <!-- ubah ke modal-lg -->
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header bg-dark text-white rounded-top-4">
                    <h5 class="modal-title" id="cropImageModalLabel">
                        <i class="bi bi-crop"></i> Crop Foto Profil
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Tutup"></button>
                </div>

                <div class="modal-body p-3 text-center" style="min-height: 400px;">
                    <div class="crop-container mx-auto"
                        style="max-width: 100%; width: 100%; height: 100%; overflow: hidden;">
                        <img id="cropperPreview"
                            style="max-width: 100%; max-height: 70vh; display: none; object-fit: contain;"
                            alt="Preview Foto">
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="button" id="cropSaveBtn" class="btn btn-primary">
                        <i class="bi bi-check2-square"></i> Simpan Crop
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="https://unpkg.com/cropperjs@1.5.13/dist/cropper.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editProfileModal = document.getElementById('editProfileModal');
            if (!editProfileModal) return;

            editProfileModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var user = button.dataset.user ? JSON.parse(button.dataset.user) : null;
                if (!user) return;

                // Isi data otomatis
                document.getElementById('nama_user_modal').textContent = user.profile?.nama_lengkap ?? '';
                document.getElementById('modal_user_id').value = user.id;
                document.getElementById('modal_nama_lengkap').value = user.profile?.nama_lengkap ?? '';
                document.getElementById('modal_gender').value = user.profile?.jenis_kelamin ?? '';
                document.getElementById('modal_telepon').value = user.profile?.no_telepon ?? '';
                document.getElementById('modal_tanggal_lahir').value = (user.profile?.tanggal_lahir ?? '')
                    .substring(0, 10);
                document.getElementById('modal_alamat').value = user.profile?.alamat ?? '';
                document.getElementById('modal_pekerjaan').value = user.profile?.pekerjaan ?? '';

                // Set action URL dinamis
                var form = document.getElementById('editProfileForm');
                form.action = '/admin/users/' + user.id + '/profile';
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const choosePhotoBtn = document.getElementById("choosePhotoBtn");
            const fileInput = document.getElementById("modal_foto");
            const cropperModalEl = document.getElementById("cropImageModal");
            const cropperPreview = document.getElementById("cropperPreview");
            const cropSaveBtn = document.getElementById("cropSaveBtn");
            const croppedPreview = document.getElementById("croppedPreview");
            const croppedInput = document.getElementById("cropped_image");

            const cropperModal = new bootstrap.Modal(cropperModalEl);
            let cropper;
            let imageReady = false; // untuk menandai kapan gambar siap di-crop

            // Klik tombol custom → trigger file input
            choosePhotoBtn.addEventListener("click", () => fileInput.click());

            // Setelah user pilih file
            fileInput.addEventListener("change", (e) => {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = (ev) => {
                    cropperPreview.src = ev.target.result;
                    cropperPreview.style.display = "block";
                    imageReady = true; // gambar sudah siap di-crop
                    cropperModal.show(); // tampilkan modal
                };
                reader.readAsDataURL(file);
            });

            // ⬇️ Inisialisasi cropper setelah modal benar-benar tampil
            cropperModalEl.addEventListener("shown.bs.modal", () => {
                if (!imageReady) return;
                if (cropper) cropper.destroy();

                // Tambahkan sedikit delay agar animasi modal selesai dulu
                setTimeout(() => {
                    cropper = new Cropper(cropperPreview, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 1,
                    });
                }, 200);
            });

            // Simpan hasil crop
            cropSaveBtn.addEventListener("click", () => {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                });

                canvas.toBlob((blob) => {
                    const reader = new FileReader();
                    reader.onloadend = () => {
                        const base64 = reader.result;
                        croppedInput.value = base64;
                        cropperModal.hide();
                        croppedPreview.src = base64;
                        croppedPreview.style.display = "block";
                        fileInput.value = ""; // reset file input
                    };
                    reader.readAsDataURL(blob);
                }, "image/jpeg", 0.9);
            });

            // Hapus cropper saat modal ditutup
            cropperModalEl.addEventListener("hidden.bs.modal", () => {
                if (cropper) {
                    cropper.destroy();
                    cropper = null;
                }
                imageReady = false;
            });
        });
    </script>

    <script>
        // Reload Tabel saat Paginasi berubah
        document.getElementById('perPage').addEventListener('change', function() {
            const currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('perPage', this.value); // ubah perPage
            window.location.href = currentUrl.toString(); // reload halaman dengan semua filter tetap
        });
    </script>
@endpush
