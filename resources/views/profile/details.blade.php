@extends('layouts.app')

@section('content')
    <input type="text" id="dummyFocus" style="position:absolute; opacity:0; height:0; width:0;" autofocus>

    <div class="container py-4">
        <div class="card shadow-sm p-4 mx-auto border-primary" style="max-width:70%;">
            <div class="text-center mb-4">
                <div class="position-relative d-inline-block">
                    <img id="profile-photo"
                        src="{{ $profile->foto ? asset('storage/' . $profile->foto) : asset('images/default-avatar.png') }}"
                        class="rounded-circle border" style="width:150px; height:150px; object-fit:cover; cursor:pointer;"
                        alt="Foto Profil" data-bs-toggle="modal" data-bs-target="#photoModal">
                    <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle p-2"
                        style="cursor:pointer;" data-bs-toggle="modal" data-bs-target="#photoModal">
                        <i class="bi bi-camera-fill"></i>
                    </div>
                </div>
                <h4 class="mt-3">{{ $profile->nama_lengkap ?? 'Nama belum diisi' }}</h4>
            </div>

            <form id="profileForm" action="{{ route('profile.details.update') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="list-group list-group-flush">

                    {{-- Nama Lengkap --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>Nama Lengkap:</strong>
                            <span id="nama_lengkap_text">{{ $profile->nama_lengkap }}</span>
                            <input type="text" class="form-control d-none mt-2" name="nama_lengkap"
                                id="nama_lengkap_input" value="{{ $profile->nama_lengkap }}" required>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn"
                                data-field="nama_lengkap">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>Jenis Kelamin:</strong>
                            <span id="jenis_kelamin_text">{{ $profile->jenis_kelamin }}</span>
                            <select class="form-select d-none mt-2" name="jenis_kelamin" id="jenis_kelamin_input" required>
                                <option value="">Pilih</option>
                                <option value="Laki-laki" {{ $profile->jenis_kelamin == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan" {{ $profile->jenis_kelamin == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn"
                                data-field="jenis_kelamin">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>Tanggal Lahir:</strong>
                            <span id="tanggal_lahir_text">
                                {{ $profile->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->format('d-m-Y') : '-' }}
                            </span>
                            <input type="date" class="form-control d-none mt-2" name="tanggal_lahir"
                                id="tanggal_lahir_input"
                                value="{{ $profile->tanggal_lahir ? \Carbon\Carbon::parse($profile->tanggal_lahir)->format('Y-m-d') : '' }}"
                                required>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn"
                                data-field="tanggal_lahir">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- No Telepon --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>No. Telepon:</strong>
                            <span id="no_telepon_text">{{ $profile->no_telepon }}</span>
                            <input type="text" class="form-control d-none mt-2" name="no_telepon" id="no_telepon_input"
                                value="{{ $profile->no_telepon }}" required>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn"
                                data-field="no_telepon">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Alamat --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>Alamat:</strong>
                            <span id="alamat_text">{{ $profile->alamat ?? '-' }}</span>
                            <textarea class="form-control d-none mt-2" name="alamat" id="alamat_input">{{ $profile->alamat }}</textarea>
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn" data-field="alamat">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Pekerjaan --}}
                    <div class="list-group-item profile-item">
                        <div class="content">
                            <strong>Pekerjaan:</strong>
                            <span id="pekerjaan_text">{{ $profile->pekerjaan ?? '-' }}</span>
                            <input type="text" class="form-control d-none mt-2" name="pekerjaan" id="pekerjaan_input"
                                value="{{ $profile->pekerjaan }}">
                        </div>
                        <div class="actions">
                            <button type="button" class="btn btn-sm btn-outline-secondary edit-btn"
                                data-field="pekerjaan">
                                <i class="bi bi-pencil"></i>
                            </button>
                        </div>
                    </div>

                </div>


                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        <div class="card shadow-sm p-4 mt-3 mx-auto" style="max-width:70%;">
            <h5 class="mb-3">Ubah Username & Email</h5>
            @include('profile.partials.update-profile-information-form')
        </div>

        <div class="card shadow-sm p-4 mt-3 mx-auto" style="max-width:70%;">
            <h5 class="mb-3">Ubah Password</h5>
            @include('profile.partials.update-password-form')
        </div>

        <div class="card shadow-sm p-4 mt-3 mx-auto border-danger" style="max-width:70%;">
            <h5 class="mb-3 text-danger">Hapus Akun</h5>
            @include('profile.partials.delete-user-form')
        </div>

    </div>

    {{-- Modal Ubah Foto --}}
    <div class="modal fade" id="photoModal" tabindex="-1" aria-labelledby="photoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title">Ubah Foto Profil</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <input type="file" id="photoInput" accept="image/*" class="form-control mb-3">
                    <div>
                        <img id="cropPreview" style="max-width:100%; display:none;" />
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" id="savePhoto" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
    <script src="https://unpkg.com/browser-image-compression@2.0.2/dist/browser-image-compression.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inline edit toggle
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const field = this.dataset.field;
                    const textEl = document.getElementById(field + '_text');
                    const inputEl = document.getElementById(field + '_input');

                    if (inputEl.classList.contains('d-none')) {
                        inputEl.classList.remove('d-none');
                        textEl.classList.add('d-none');
                        this.innerHTML = '<i class="bi bi-x-circle"></i>';
                    } else {
                        inputEl.classList.add('d-none');
                        textEl.classList.remove('d-none');
                        this.innerHTML = '<i class="bi bi-pencil"></i>';
                    }
                });
            });

            // Cropper setup
            let cropper;
            const photoInput = document.getElementById('photoInput');
            const cropPreview = document.getElementById('cropPreview');
            const savePhotoBtn = document.getElementById('savePhoto');

            photoInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(event) {
                    cropPreview.src = event.target.result;
                    cropPreview.style.display = 'block';

                    if (cropper) cropper.destroy();
                    cropper = new Cropper(cropPreview, {
                        aspectRatio: 1,
                        viewMode: 1,
                    });
                };
                reader.readAsDataURL(file);
            });

            savePhotoBtn.addEventListener('click', async function() {
                if (!cropper) return;

                const canvas = cropper.getCroppedCanvas({
                    width: 300,
                    height: 300
                });

                canvas.toBlob(async (blob) => {
                    const compressed = await imageCompression(blob, {
                        maxSizeMB: 1,
                        maxWidthOrHeight: 300
                    });

                    const formData = new FormData();
                    formData.append('foto', compressed);
                    formData.append('_token', '{{ csrf_token() }}');
                    formData.append('_method', 'PUT');

                    fetch('{{ route('profile.details.update') }}', {
                            method: 'POST',
                            body: formData
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Ganti langsung foto profil tanpa reload
                                document.getElementById('profile-photo').src = data
                                    .foto_url;

                                // Tutup modal
                                const modal = bootstrap.Modal.getInstance(document
                                    .getElementById('photoModal'));
                                modal.hide();
                            } else {
                                alert('Gagal menyimpan foto.');
                            }
                        })
                        .catch(err => {
                            console.error('Upload error:', err);
                            alert('Terjadi kesalahan saat upload foto.');
                        });

                }, 'image/jpeg');
            });

        });
    </script>
@endpush

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" rel="stylesheet">
    <style>
        /* Struktur umum setiap item profil */
        .profile-item {
            display: flex !important;
            flex-direction: row;
            justify-content: space-between;
            /* Biar tombol edit tetap sejajar di awal teks */
            align-items: flex-start;
            flex-wrap: nowrap;
            padding: 0.75rem 0;
            border-bottom: 1px solid #dee2e6;
        }

        /* Kolom isi utama */
        .profile-item .content {
            flex: 1 1 auto;
            max-width: 80%;
            margin-right: 0.75rem;
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Kolom untuk tombol edit */
        .profile-item .actions {
            flex: 0 0 15%;
            text-align: right;
            display: flex;
            align-items: flex-end;
            justify-content: flex-end;
        }


        .profile-item .actions .btn {
            min-width: 36px;
        }

        /* Sedikit jarak antar item */
        .list-group-item:last-child {
            border-bottom: none;
        }
    </style>
@endpush
