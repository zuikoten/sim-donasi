@extends('layouts.app')

@section('title', 'Buat Donasi Baru')

@section('content')
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Buat Donasi Baru</h1>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('donations.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- PILIH PROGRAM -->
                        <div class="mb-3">
                            <label for="program_id" class="form-label">Pilih Program</label>
                            <select class="form-select @error('program_id') is-invalid @enderror" id="program_id"
                                name="program_id" required>
                                <option value="" selected disabled>-- Pilih Program Donasi --</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->id }}"
                                        {{ old('program_id') == $program->id ? 'selected' : '' }}>
                                        {{ $program->nama_program }} - {{ $program->kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('program_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- NOMINAL DONASI -->
                        <div class="mb-3">
                            <label for="nominal" class="form-label">Nominal Donasi (Rp)</label>
                            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="nominal"
                                name="nominal" value="{{ old('nominal') }}" placeholder="Masukkan nominal donasi..."
                                required>
                            @error('nominal')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- METODE PEMBAYARAN -->
                        <div class="mb-3">
                            <label for="metode_pembayaran" class="form-label">Metode Pembayaran</label>
                            <select class="form-select @error('metode_pembayaran') is-invalid @enderror"
                                id="metode_pembayaran" name="metode_pembayaran" required>
                                <option value="" selected disabled>-- Pilih Metode Pembayaran --</option>
                                <option value="Uang Tunai" {{ old('metode_pembayaran') == 'Uang Tunai' ? 'selected' : '' }}>
                                    Uang Tunai
                                </option>
                                <option value="Transfer Bank"
                                    {{ old('metode_pembayaran') == 'Transfer Bank' ? 'selected' : '' }}>
                                    Transfer Bank
                                </option>
                                <option value="QRIS" {{ old('metode_pembayaran') == 'QRIS' ? 'selected' : '' }}>
                                    QRIS
                                </option>
                                <option value="E-Wallet" {{ old('metode_pembayaran') == 'E-Wallet' ? 'selected' : '' }}>
                                    E-Wallet
                                </option>
                            </select>
                            @error('metode_pembayaran')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- PILIH REKENING BANK -->
                        <div class="mb-3">
                            <label for="bank_account_id" class="form-label">Pilih Rekening Tujuan</label>
                            <select class="form-select @error('bank_account_id') is-invalid @enderror" id="bank_account_id"
                                name="bank_account_id" required disabled>
                                <option value="" selected disabled>-- Pilih Metode Pembayaran Dulu --</option>

                                @if ($kasTunai)
                                    <option value="{{ $kasTunai->id }}" data-type="cash"
                                        {{ old('bank_account_id') == $kasTunai->id ? 'selected' : '' }}>
                                        💵 {{ $kasTunai->bank_name }}
                                    </option>
                                @endif

                                @foreach ($bankAccounts as $bank)
                                    <option value="{{ $bank->id }}" data-type="{{ $bank->account_type ?? 'bank' }}"
                                        {{ old('bank_account_id') == $bank->id ? 'selected' : '' }}>
                                        🏦 {{ $bank->bank_name }} - {{ $bank->account_number }}
                                        ({{ $bank->account_holder }})
                                    </option>
                                @endforeach
                            </select>
                            @error('bank_account_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted" id="rekening-hint">
                                Pilih metode pembayaran terlebih dahulu
                            </small>
                        </div>

                        <!-- KETERANGAN TAMBAHAN -->
                        <div class="mb-3">
                            <label for="keterangan_tambahan" class="form-label">Keterangan Tambahan (Opsional)</label>
                            <textarea class="form-control @error('keterangan_tambahan') is-invalid @enderror" id="keterangan_tambahan"
                                name="keterangan_tambahan" rows="2" placeholder="Contoh: Ditransfer via Bank BCA an. Muhammad Rizki">{{ old('keterangan_tambahan') }}</textarea>
                            @error('keterangan_tambahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- BUKTI TRANSFER -->
                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label">Upload Bukti Penerimaan</label>
                            <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror"
                                id="bukti_transfer" name="bukti_transfer" accept="image/*" required>
                            <div class="form-text">Format: JPEG, PNG, JPG, GIF. Maksimal: 2MB.</div>
                            @error('bukti_transfer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('donations.index') }}" class="btn btn-secondary me-md-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Kirim Donasi</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- SIDEBAR INFO REKENING -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Informasi Rekening</h5>
                    <p>Silakan transfer ke salah satu rekening berikut:</p>
                    <x-bank-list :bankAccounts="$bankAccounts" />
                    <div class="modern-alert mt-3">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Setelah transfer, jangan lupa upload bukti transfer pada form donasi.</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kasTunaiId = {{ $kasTunai ? $kasTunai->id : 'null' }};
            const metodePembayaran = document.getElementById('metode_pembayaran');
            const bankAccountSelect = document.getElementById('bank_account_id');
            const rekeningHint = document.getElementById('rekening-hint');

            // Function untuk update rekening dropdown
            function updateRekeningDropdown() {
                const metode = metodePembayaran.value;

                // Reset
                if (!metode) {
                    bankAccountSelect.disabled = true;
                    bankAccountSelect.value = '';
                    rekeningHint.textContent = 'Pilih metode pembayaran terlebih dahulu';
                    rekeningHint.className = 'form-text text-muted';

                    // Hide semua option kecuali placeholder
                    Array.from(bankAccountSelect.options).forEach(option => {
                        if (option.value !== '') {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });
                    return;
                }

                // Enable dropdown
                bankAccountSelect.disabled = false;

                // Handle berdasarkan metode
                if (metode === 'Uang Tunai') {
                    if (kasTunaiId) {
                        // Show only Kas Tunai
                        Array.from(bankAccountSelect.options).forEach(option => {
                            const dataType = option.dataset.type;
                            if (dataType === 'cash' || option.value === '') {
                                option.style.display = 'block';
                            } else {
                                option.style.display = 'none';
                            }
                        });

                        // Auto-select Kas Tunai
                        bankAccountSelect.value = kasTunaiId;
                        bankAccountSelect.style.backgroundColor = '#e9ecef';
                        rekeningHint.textContent = '✓ Otomatis dipilih: Kas Tunai';
                        rekeningHint.className = 'form-text text-success';
                    } else {
                        alert('⚠️ Error: Data Kas Tunai tidak ditemukan.\nHubungi administrator.');
                        bankAccountSelect.disabled = true;
                    }
                } else {
                    // Metode lain (Transfer Bank, QRIS, E-Wallet)
                    // Hide Kas Tunai, show only bank accounts
                    Array.from(bankAccountSelect.options).forEach(option => {
                        const dataType = option.dataset.type;
                        if (dataType === 'cash') {
                            option.style.display = 'none';
                        } else {
                            option.style.display = 'block';
                        }
                    });

                    // Reset selection
                    bankAccountSelect.value = '';
                    bankAccountSelect.style.backgroundColor = '';
                    rekeningHint.textContent = 'Pilih rekening bank tujuan';
                    rekeningHint.className = 'form-text text-muted';
                }
            }

            // Event listener untuk perubahan metode pembayaran
            metodePembayaran.addEventListener('change', updateRekeningDropdown);

            // Prevent user dari mengubah rekening jika metode = Uang Tunai
            bankAccountSelect.addEventListener('change', function() {
                const metode = metodePembayaran.value;

                if (metode === 'Uang Tunai' && this.value != kasTunaiId) {
                    alert('⚠️ Untuk metode Uang Tunai, rekening harus "Kas Tunai"');
                    this.value = kasTunaiId;
                }
            });

            // Trigger pada page load (untuk old input saat validation error)
            if (metodePembayaran.value) {
                updateRekeningDropdown();
            }
        });
    </script>
@endpush
