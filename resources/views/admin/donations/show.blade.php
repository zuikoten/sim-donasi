{{-- File: resources/views/admin/donations/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detail Donasi')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Detail Donasi</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <a href="{{ route('donations.index') }}" class="btn btn-sm btn-secondary mt-2 me-2">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>

            @if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <button type="button" class="btn btn-sm btn-warning mt-2 me-2" data-bs-toggle="modal"
                    data-bs-target="#editDonationModal">
                    <i class="bi bi-pencil-square"></i> Edit Donasi
                </button>
                <button type="button" class="btn btn-sm btn-danger mt-2" data-bs-toggle="modal"
                    data-bs-target="#deleteDonationModal">
                    <i class="bi bi-trash"></i> Hapus Donasi
                </button>
            @endif

        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Informasi Donasi</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <td width="30%"><strong>ID Donasi</strong></td>
                            <td>{{ $donation->id }}</td>
                        </tr>
                        <tr>
                            <td><strong>Tanggal</strong></td>
                            <td>{{ $donation->created_at->format('d F Y, H:i') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Donatur</strong></td>
                            <td>{{ $donation->user->name }} ({{ $donation->user->email }})</td>
                        </tr>
                        <tr>
                            <td><strong>Program</strong></td>
                            <td>{{ $donation->program->nama_program }}</td>
                        </tr>
                        <tr>
                            <td><strong>Nominal</strong></td>
                            <td>Rp {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode Pembayaran</strong></td>
                            <td>{{ $donation->metode_pembayaran }}</td>
                        </tr>
                        <tr>
                            <td><strong>Metode Pembayaran</strong></td>
                            <td>{{ $donation->bankAccount->account_holder }}
                                <small class="text-muted">({{ $donation->bankAccount->account_number }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td><strong>Status</strong></td>
                            <td>
                                @if ($donation->status === 'terverifikasi')
                                    <span class="badge bg-success">Terverifikasi</span>
                                @elseif($donation->status === 'menunggu')
                                    <span class="badge bg-warning">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-danger">Ditolak</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bukti Penerimaan</h5>
                </div>
                <div class="card-body text-center">
                    @if ($donation->bukti_transfer)
                        <a href="{{ Storage::url($donation->bukti_transfer) }}" target="_blank">
                            <img src="{{ Storage::url($donation->bukti_transfer) }}" alt="Bukti Transfer"
                                class="img-fluid rounded" style="max-height: 300px;">
                        </a>
                        <p class="mt-2">
                            <a href="{{ Storage::url($donation->bukti_transfer) }}" target="_blank"
                                class="btn btn-sm btn-outline-primary">Lihat Gambar Asli</a>
                        </p>
                    @else
                        <p class="text-muted">Belum ada bukti transfer.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Edit Donasi (AJAX Loaded) -->
    <div class="modal fade" id="editDonationModal" tabindex="-1" aria-labelledby="editDonationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow">
                <div id="editModalContent">
                    <!-- Konten akan dimuat via AJAX -->
                    <div class="text-center p-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat form edit...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-labelledby="imagePreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="imagePreviewModalLabel">
                        <i class="bi bi-image me-2"></i>Bukti Transfer
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <img id="previewImage" src="" alt="Preview" class="img-fluid rounded shadow">
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Delete --}}
    <div class="modal fade" id="deleteDonationModal" tabindex="-1" aria-labelledby="deleteDonationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold" id="deleteDonationModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus Donasi
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan!
                    </div>

                    <p class="mb-3">Anda akan menghapus donasi dengan detail berikut:</p>

                    <div class="card bg-light border-0">
                        <div class="card-body">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <td class="fw-semibold" style="width: 40%;">ID Donasi:</td>
                                    <td>#{{ $donation->id }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Donatur:</td>
                                    <td>{{ $donation->user->name }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Program:</td>
                                    <td>{{ $donation->program->nama_program }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Nominal:</td>
                                    <td class="text-danger fw-bold">Rp
                                        {{ number_format($donation->nominal, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td class="fw-semibold">Status:</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $donation->status == 'terverifikasi' ? 'success' : ($donation->status == 'ditolak' ? 'danger' : 'warning') }}">
                                            {{ ucfirst($donation->status) }}
                                        </span>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <p class="text-danger fw-semibold mt-3 mb-0">
                        <i class="bi bi-info-circle-fill me-1"></i>
                        Data donasi, bukti transfer, dan history akan dihapus permanen.
                    </p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-danger rounded-3 px-4" id="confirmDeleteBtn">
                        <i class="bi bi-trash-fill me-1"></i> Ya, Hapus Donasi
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Konfirmasi Perubahan --}}
    <div class="modal fade" id="confirmChangeModal" tabindex="-1" aria-labelledby="confirmChangeModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-warning text-dark border-0">
                    <h5 class="modal-title fw-bold" id="confirmChangeModalLabel">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Perubahan Data
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="alert alert-warning border-0 shadow-sm mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Perhatian!</strong> Anda akan melakukan pengubahan data donasi sebagai berikut:
                    </div>

                    <div id="changesSummary" class="card bg-light border-0 mb-3">
                        <div class="card-body">
                            <!-- Changes will be inserted here by JavaScript -->
                        </div>
                    </div>

                    <div class="alert alert-danger border-0 shadow-sm mb-0">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Segala pengubahan yang Anda lakukan dapat memengaruhi data donasi, terutama apabila Anda
                            mengubah Nominal Donasi.</strong>
                        <br>Yakin ingin tetap mengubah?
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary rounded-3 px-4" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i> Batal
                    </button>
                    <button type="button" class="btn btn-warning text-dark rounded-3 px-4" id="btnConfirmUpdate">
                        <i class="bi bi-check-circle-fill me-1"></i> Ya, Saya Yakin
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />

    <style>
        .select2-container--bootstrap-5 .select2-selection {
            border-radius: 10px;
            padding: 6px 12px;
        }

        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
            padding-left: 0.25rem;
        }

        .select2-results__options {
            max-height: 200px;
        }

        .modal-content {
            border-radius: 1rem;
        }

        .modal-dialog-scrollable .modal-body {
            max-height: calc(100vh - 200px);
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery (wajib untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <!-- Handler Modal Edit Donation -->
    <script>
        $(document).ready(function() {
            let modalLoaded = false; // Flag untuk cek apakah sudah pernah load

            // Load modal content via AJAX saat modal dibuka
            $('#editDonationModal').on('show.bs.modal', function(e) {
                // Jika sudah pernah load dan tidak ada error validasi, skip
                if (modalLoaded && !$('#editModalContent').find('.alert-danger').length) {
                    return;
                }

                const donationId = {{ $donation->id }};

                // Show loading
                $('#editModalContent').html(`
                    <div class="text-center p-5">
                        <div class="spinner-border text-warning" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat form edit...</p>
                    </div>
                `);

                // Load via AJAX
                $.ajax({
                    url: '{{ route('admin.donations.edit-form', ':id') }}'.replace(':id',
                        donationId),
                    type: 'GET',
                    success: function(response) {
                        $('#editModalContent').html(response);
                        modalLoaded = true;

                        // Initialize Select2 setelah content loaded
                        initializeSelect2();
                    },
                    error: function(xhr) {
                        let errorMsg = 'Gagal memuat form. Silakan refresh halaman.';
                        if (xhr.status === 403) {
                            errorMsg = 'Anda tidak memiliki akses untuk mengedit donasi ini.';
                        } else if (xhr.status === 404) {
                            errorMsg = 'Data donasi tidak ditemukan.';
                        }

                        $('#editModalContent').html(`
                            <div class="p-4">
                                <div class="alert alert-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                    ${errorMsg}
                                </div>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        `);
                    }
                });
            });

            // Function untuk initialize Select2
            function initializeSelect2() {
                // Select2 untuk dropdown biasa
                $('.select2-edit').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#editDonationModal')
                });

                // Select2 dengan AJAX untuk Donatur
                $('#edit_user_id').select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    placeholder: '-- Cari atau pilih Donatur --',
                    allowClear: true,
                    minimumInputLength: 0,
                    dropdownParent: $('#editDonationModal'),
                    ajax: {
                        url: '{{ route('donatur.search') }}',
                        dataType: 'json',
                        delay: 250,
                        data: function(params) {
                            return {
                                term: params.term || '',
                            };
                        },
                        processResults: function(data) {
                            return {
                                results: data
                            };
                        },
                        error: function(xhr, status, error) {
                            console.error('Ajax error:', xhr.responseText);
                        },
                        cache: true
                    }
                });
            }

            // Reset modal saat ditutup
            $('#editDonationModal').on('hidden.bs.modal', function() {
                // Destroy Select2 untuk mencegah memory leak
                if ($('.select2-edit').length) {
                    $('.select2-edit').select2('destroy');
                }
                if ($('#edit_user_id').length) {
                    $('#edit_user_id').select2('destroy');
                }
            });

            // Auto-open modal jika ada validation errors
            @if ($errors->any())
                $('#editDonationModal').modal('show');
                modalLoaded = false; // Force reload untuk tampilkan error
            @endif
        });

        // Function untuk preview gambar
        function showImagePreview(imageUrl) {
            $('#previewImage').attr('src', imageUrl);
            $('#imagePreviewModal').modal('show');
        }
    </script>

    <!-- Handler Modal Delete Donation -->
    <script>
        $(document).ready(function() {
            // Handle delete confirmation
            $('#confirmDeleteBtn').on('click', function() {
                const btn = $(this);
                const originalText = btn.html();

                // Disable button dan tampilkan loading
                btn.prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm me-2"></span>Menghapus...');

                // AJAX Delete Request
                $.ajax({
                    url: '{{ route('admin.donations.destroy', $donation->id) }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE'
                    },
                    success: function(response) {
                        // Tutup modal
                        $('#deleteDonationModal').modal('hide');

                        // Tampilkan overlay success
                        $('body').append(`
                    <div id="successOverlay" style="
                        position: fixed;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        background: rgba(0,0,0,0.8);
                        z-index: 9999;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    ">
                        <div style="
                            background: white;
                            padding: 2rem;
                            border-radius: 1rem;
                            text-align: center;
                            max-width: 400px;
                        ">
                            <div style="
                                width: 80px;
                                height: 80px;
                                background: #28a745;
                                border-radius: 50%;
                                margin: 0 auto 1rem;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                            ">
                                <i class="bi bi-check-lg" style="font-size: 3rem; color: white;"></i>
                            </div>
                            <h4 class="text-success mb-2">Berhasil!</h4>
                            <p class="mb-0">Donasi berhasil dihapus.</p>
                            <p class="text-muted small">Mengarahkan ke halaman daftar donasi...</p>
                        </div>
                    </div>
                `);

                        // Redirect setelah 1.5 detik
                        setTimeout(function() {
                            window.location.href = '{{ route('donations.index') }}';
                        }, 1500);
                    },
                    error: function(xhr, status, error) {
                        // Enable button kembali
                        btn.prop('disabled', false).html(originalText);

                        // Tampilkan error message
                        let errorMsg = 'Gagal menghapus donasi. Silakan coba lagi.';

                        if (xhr.status === 403) {
                            errorMsg = 'Anda tidak memiliki akses untuk menghapus donasi ini.';
                        } else if (xhr.status === 404) {
                            errorMsg = 'Data donasi tidak ditemukan.';
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMsg = xhr.responseJSON.message;
                        }

                        // Tampilkan alert error
                        alert('Error: ' + errorMsg);

                        console.error('Delete error:', xhr.responseText);
                    }
                });
            });

            // Reset button saat modal ditutup
            $('#deleteDonationModal').on('hidden.bs.modal', function() {
                $('#confirmDeleteBtn').prop('disabled', false)
                    .html('<i class="bi bi-trash-fill me-1"></i> Ya, Hapus Donasi');
            });
        });
    </script>

    <!-- Handler Modal Konfirmasi Edit Donation -->
    <script>
        // Script untuk handle konfirmasi update (global, bisa diakses dari modal yang di-load AJAX)
        $(document).on('click', '#btnSubmitEdit', function(e) {
            e.preventDefault();

            // Collect current values
            const currentUserId = $('#edit_user_id').val();
            const currentUserName = $('#edit_user_id option:selected').text().trim();
            const currentProgramId = $('#edit_program_id').val();
            const currentProgramName = $('#edit_program_id option:selected').data('program-name');
            const currentNominal = $('#edit_nominal').val();
            const currentMetode = $('#edit_metode_pembayaran').val();
            const currentBankId = $('#edit_bank_account_id').val();
            const currentBankName = $('#edit_bank_account_id option:selected').data('bank-name');
            const currentStatus = $('#edit_status').val();
            const currentBuktiTransfer = $('#edit_bukti_transfer')[0].files.length > 0 ? 'Diubah' : 'Tidak diubah';

            // Get original values
            const originalUserId = $('#original_user_id').val();
            const originalUserName = $('#original_user_name').val();
            const originalProgramId = $('#original_program_id').val();
            const originalProgramName = $('#original_program_name').val();
            const originalNominal = $('#original_nominal').val();
            const originalMetode = $('#original_metode_pembayaran').val();
            const originalBankId = $('#original_bank_account_id').val();
            const originalBankName = $('#original_bank_account_name').val();
            const originalStatus = $('#original_status').val();

            // Build changes summary
            let changesHTML = '<table class="table table-sm mb-0">';

            // Donatur
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top" style="width: 30%;">Donatur:</td>';
            if (currentUserId != originalUserId) {
                changesHTML +=
                    `<td><span class="text-danger">${originalUserName}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${currentUserName}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Program Donasi
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Program Donasi:</td>';
            if (currentProgramId != originalProgramId) {
                changesHTML +=
                    `<td><span class="text-danger">${originalProgramName}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${currentProgramName}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Nominal
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Nominal:</td>';
            if (currentNominal != originalNominal) {
                changesHTML +=
                    `<td><span class="text-danger">Rp ${Number(originalNominal).toLocaleString('id-ID')}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">Rp ${Number(currentNominal).toLocaleString('id-ID')}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Metode Pembayaran
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Metode Pembayaran:</td>';
            if (currentMetode != originalMetode) {
                changesHTML +=
                    `<td><span class="text-danger">${originalMetode}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${currentMetode}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Rekening Bank Tujuan
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Rekening Bank Tujuan:</td>';
            if (currentBankId != originalBankId) {
                changesHTML +=
                    `<td><span class="text-danger">${originalBankName}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${currentBankName}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Status Donasi
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Status Donasi:</td>';
            if (currentStatus != originalStatus) {
                changesHTML +=
                    `<td><span class="text-danger">${originalStatus.charAt(0).toUpperCase() + originalStatus.slice(1)}</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${currentStatus.charAt(0).toUpperCase() + currentStatus.slice(1)}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            // Bukti Penerimaan
            changesHTML += '<tr>';
            changesHTML += '<td class="fw-semibold align-top">Bukti Penerimaan:</td>';
            if (currentBuktiTransfer == 'Diubah') {
                const fileName = $('#edit_bukti_transfer')[0].files[0].name;
                changesHTML +=
                    `<td><span class="text-danger">File Lama</span> <i class="bi bi-arrow-right text-muted"></i> <span class="text-success fw-bold">${fileName}</span></td>`;
            } else {
                changesHTML += '<td><span class="badge bg-secondary">No Update</span></td>';
            }
            changesHTML += '</tr>';

            changesHTML += '</table>';

            // Insert summary into modal
            $('#changesSummary .card-body').html(changesHTML);

            // Sembunyikan modal edit, tampilkan modal konfirmasi
            $('#editDonationModal').modal('hide');

            // Tunggu animasi hide selesai, baru tampilkan modal konfirmasi
            setTimeout(function() {
                $('#confirmChangeModal').modal('show');
            }, 300);
        });

        // Handle confirm button
        $(document).on('click', '#btnConfirmUpdate', function() {
            const btn = $(this);
            const originalText = btn.html();

            // Disable button dan tampilkan loading
            btn.prop('disabled', true)
                .html('<span class="spinner-border spinner-border-sm me-2"></span>Menyimpan...');

            // Submit form
            $('#editDonationForm').off('submit').submit();
        });

        // Jika user cancel konfirmasi, kembali ke modal edit
        $('#confirmChangeModal').on('hidden.bs.modal', function() {
            if (!$('#editDonationForm').data('submitted')) {
                $('#editDonationModal').modal('show');
            }
        });

        // Mark form as submitted
        $('#editDonationForm').on('submit', function() {
            $(this).data('submitted', true);
        });
    </script>
@endpush
