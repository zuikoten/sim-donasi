@extends('layouts.app')
@section('title', 'Tambah Donasi pada Donatur')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-primary"><i class="bi bi-wallet2 me-2"></i>Tambah Donasi pada Donatur</h2>
        </div>

        <div class="card shadow-sm border-0 rounded-4">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('admin.donations.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- PILIH DONATUR (DYNAMIC AJAX) -->
                    <div class="mb-4">
                        <label for="user_id" class="form-label fw-semibold">Pilih Donatur</label>
                        <select class="form-select select2-ajax @error('user_id') is-invalid @enderror" id="user_id"
                            name="user_id">
                            <option value="" selected disabled>-- Cari atau pilih Donatur --</option>
                        </select>
                        @error('user_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <!-- PILIH PROGRAM -->
                    <div class="mb-4">
                        <label for="program_id" class="form-label fw-semibold">Program Donasi</label>
                        <select class="form-select select2 @error('program_id') is-invalid @enderror" id="program_id"
                            name="program_id">
                            <option value="" selected disabled>-- Pilih Program Donasi --</option>
                            @foreach ($programs as $program)
                                <option value="{{ $program->id }}">
                                    {{ $program->nama_program }} ({{ $program->kategori }})
                                </option>
                            @endforeach
                        </select>
                        @error('program_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- NOMINAL -->
                    <div class="mb-4">
                        <label for="nominal" class="form-label fw-semibold">Nominal (Rp)</label>
                        <input type="number" class="form-control @error('nominal') is-invalid @enderror" name="nominal"
                            id="nominal" value="{{ old('nominal') }}" placeholder="Masukkan nominal donasi...">
                        @error('nominal')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- METODE PEMBAYARAN -->
                    <div class="mb-4">
                        <label for="metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
                        <select class="form-select select2 @error('metode_pembayaran') is-invalid @enderror"
                            name="metode_pembayaran" id="metode_pembayaran">
                            <option value="" disabled selected>-- Pilih Metode Pembayaran --</option>
                            <option value="Uang Tunai">Uang Tunai</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                            <option value="QRIS">QRIS</option>
                            <option value="E-Wallet">E-Wallet</option>
                        </select>
                        @error('metode_pembayaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- PILIH REKENING BANK -->
                    <div class="mb-4">
                        <label for="bank_account_id" class="form-label fw-semibold">Rekening Bank Tujuan</label>
                        <select class="form-select select2 @error('bank_account_id') is-invalid @enderror"
                            id="bank_account_id" name="bank_account_id">
                            <option value="" selected disabled>-- Pilih Rekening Bank --</option>
                            @foreach ($bankAccounts as $bank)
                                <option value="{{ $bank->id }}"
                                    {{ old('bank_account_id') == $bank->id ? 'selected' : '' }}>
                                    {{ $bank->bank_name }} - {{ $bank->account_number }} ({{ $bank->account_holder }})
                                </option>
                            @endforeach
                        </select>
                        @error('bank_account_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- STATUS -->
                    <div class="mb-4">
                        <label for="status" class="form-label fw-semibold">Status Donasi</label>
                        <select class="form-select select2 @error('status') is-invalid @enderror" name="status"
                            id="status">
                            <option value="menunggu">Menunggu</option>
                            <option value="terverifikasi">Terverifikasi</option>
                            <option value="ditolak">Ditolak</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- KETERANGAN -->
                    <div class="mb-4">
                        <label for="keterangan_tambahan" class="form-label fw-semibold">Keterangan
                            <em>(Opsional)</em></label>
                        <textarea class="form-control" name="keterangan_tambahan" rows="2"
                            placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('keterangan_tambahan') }}</textarea>
                    </div>

                    <!-- BUKTI PEMBAYARAN -->
                    <div class="mb-4">
                        <label for="bukti_transfer" class="form-label fw-semibold">Upload Bukti Penerimaan <em>&#40;berupa
                                bukti
                                transfer/foto nota/foto penyerahan&#41;</em> </label>
                        <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror"
                            id="bukti_transfer" name="bukti_transfer" accept="image/*">
                        <div class="form-text text-muted">Maksimal 2MB (jpeg, png, jpg, gif).</div>
                        @error('bukti_transfer')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-end mt-4">
                        <a href="{{ route('donations.index') }}" class="btn btn-outline-secondary me-2">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-save-fill"></i> Simpan Donasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('styles')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <!-- STYLE TAMBAHAN -->
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

        .card {
            border-radius: 1rem;
        }
    </style>
@endpush

@push('scripts')
    <!-- jQuery (wajib untuk Select2) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            $('#user_id').select2({
                theme: 'bootstrap-5',
                width: '100%',
                placeholder: '-- Cari atau pilih Donatur --',
                allowClear: true,
                minimumInputLength: 0,
                ajax: {
                    url: '{{ route('donatur.search') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        console.log('Searching for:', params.term); // DEBUG
                        return {
                            term: params.term || '',
                        };
                    },
                    processResults: function(data) {
                        console.log('Results:', data); // DEBUG
                        return {
                            results: data
                        };
                    },
                    error: function(xhr, status, error) {
                        console.error('Ajax error:', xhr.responseText); // DEBUG
                    },
                    cache: true
                }
            });
        });
    </script>
@endpush
