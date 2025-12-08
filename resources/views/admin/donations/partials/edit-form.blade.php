{{-- File: resources/views/admin/donations/partials/edit-form.blade.php --}}

<form id="editDonationForm" action="{{ route('admin.donations.update', $donation->id) }}" method="POST"
    enctype="multipart/form-data">
    @csrf
    @method('PUT')

    {{-- Hidden inputs untuk menyimpan data original --}}
    <input type="hidden" id="original_user_id" value="{{ $donation->user_id }}">
    <input type="hidden" id="original_user_name" value="{{ $donation->user->name }}">
    <input type="hidden" id="original_program_id" value="{{ $donation->program_id }}">
    <input type="hidden" id="original_program_name" value="{{ $donation->program->nama_program }}">
    <input type="hidden" id="original_nominal" value="{{ $donation->nominal }}">
    <input type="hidden" id="original_metode_pembayaran" value="{{ $donation->metode_pembayaran }}">
    <input type="hidden" id="original_bank_account_id" value="{{ $donation->bank_account_id }}">
    <input type="hidden" id="original_bank_account_name"
        value="{{ $donation->bankAccount->bank_name }} - {{ $donation->bankAccount->account_number }}">
    <input type="hidden" id="original_status" value="{{ $donation->status }}">
    <input type="hidden" id="original_bukti_transfer" value="{{ $donation->bukti_transfer ? 'Ada' : 'Tidak Ada' }}">

    <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title fw-bold" id="editDonationModalLabel">
            <i class="bi bi-pencil-square me-2"></i>Edit Donasi #{{ $donation->id }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body p-4">
        <div class="alert alert-warning border-0 shadow-sm">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <strong>Perhatian!</strong> Pengubahan data donasi akan mempengaruhi total dana terkumpul program secara
            otomatis.
        </div>

        <!-- PILIH DONATUR -->
        <div class="mb-4">
            <label for="edit_user_id" class="form-label fw-semibold">Pilih Donatur</label>
            <select class="form-select select2-ajax-edit @error('user_id') is-invalid @enderror" id="edit_user_id"
                name="user_id" required>
                <option value="{{ $donation->user_id }}" selected>
                    {{ $donation->user->name }}
                </option>
            </select>
            @error('user_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- PILIH PROGRAM -->
        <div class="mb-4">
            <label for="edit_program_id" class="form-label fw-semibold">Program Donasi</label>
            <select class="form-select select2-edit @error('program_id') is-invalid @enderror" id="edit_program_id"
                name="program_id" required>
                <option value="" disabled>-- Pilih Program Donasi --</option>
                @foreach ($programs as $program)
                    <option value="{{ $program->id }}" data-program-name="{{ $program->nama_program }}"
                        {{ old('program_id', $donation->program_id) == $program->id ? 'selected' : '' }}>
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
            <label for="edit_nominal" class="form-label fw-semibold">Nominal (Rp)</label>
            <input type="number" class="form-control @error('nominal') is-invalid @enderror" id="edit_nominal"
                name="nominal" value="{{ old('nominal', $donation->nominal) }}" min="1000" step="1000"
                placeholder="Masukkan nominal donasi..." required>
            @error('nominal')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- METODE PEMBAYARAN -->
        <div class="mb-4">
            <label for="edit_metode_pembayaran" class="form-label fw-semibold">Metode Pembayaran</label>
            <select class="form-select select2-edit @error('metode_pembayaran') is-invalid @enderror"
                id="edit_metode_pembayaran" name="metode_pembayaran" required>
                <option value="" disabled>-- Pilih Metode Pembayaran --</option>
                <option value="Uang Tunai"
                    {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'Uang Tunai' ? 'selected' : '' }}>
                    Uang Tunai
                </option>
                <option value="Transfer Bank"
                    {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>
                    Transfer Bank
                </option>
                <option value="QRIS"
                    {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>
                    QRIS
                </option>
                <option value="E-Wallet"
                    {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'E-Wallet' ? 'selected' : '' }}>
                    E-Wallet
                </option>
            </select>
            @error('metode_pembayaran')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- REKENING BANK TUJUAN -->
        <div class="mb-4">
            <label for="edit_bank_account_id" class="form-label fw-semibold">Rekening Bank Tujuan</label>
            <select class="form-select select2-edit @error('bank_account_id') is-invalid @enderror"
                id="edit_bank_account_id" name="bank_account_id" required>
                <option value="" disabled>-- Pilih Rekening Bank --</option>

                @if ($kasTunai)
                    <option value="{{ $kasTunai->id }}" data-type="cash" data-bank-name="{{ $kasTunai->bank_name }}"
                        {{ old('bank_account_id', $donation->bank_account_id) == $kasTunai->id ? 'selected' : '' }}>
                        💵 {{ $kasTunai->bank_name }}
                    </option>
                @endif

                @foreach ($bankAccounts as $bank)
                    <option value="{{ $bank->id }}" data-type="{{ $bank->account_type ?? 'bank' }}"
                        data-bank-name="{{ $bank->bank_name }} - {{ $bank->account_number }}"
                        {{ old('bank_account_id', $donation->bank_account_id) == $bank->id ? 'selected' : '' }}>
                        🏦 {{ $bank->bank_name }} - {{ $bank->account_number }} ({{ $bank->account_holder }})
                    </option>
                @endforeach
            </select>
            @error('bank_account_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="form-text text-muted" id="edit_rekening_hint"></small>
        </div>

        <!-- STATUS -->
        <div class="mb-4">
            <label for="edit_status" class="form-label fw-semibold">Status Donasi</label>
            <select class="form-select select2-edit @error('status') is-invalid @enderror" id="edit_status"
                name="status" required>
                <option value="menunggu" {{ old('status', $donation->status) == 'menunggu' ? 'selected' : '' }}>
                    Menunggu
                </option>
                <option value="terverifikasi"
                    {{ old('status', $donation->status) == 'terverifikasi' ? 'selected' : '' }}>
                    Terverifikasi
                </option>
                <option value="ditolak" {{ old('status', $donation->status) == 'ditolak' ? 'selected' : '' }}>
                    Ditolak
                </option>
            </select>
            @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- KETERANGAN TAMBAHAN -->
        <div class="mb-4">
            <label for="edit_keterangan_tambahan" class="form-label fw-semibold">
                Keterangan <em>(Opsional)</em>
            </label>
            <textarea class="form-control @error('keterangan_tambahan') is-invalid @enderror" id="edit_keterangan_tambahan"
                name="keterangan_tambahan" rows="2" placeholder="Tambahkan catatan atau keterangan tambahan...">{{ old('keterangan_tambahan', $donation->keterangan_tambahan) }}</textarea>
            @error('keterangan_tambahan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- BUKTI TRANSFER -->
        <div class="mb-4">
            <label for="edit_bukti_transfer" class="form-label fw-semibold">
                Upload Bukti Penerimaan <em>&#40;berupa bukti transfer/foto nota/foto penyerahan&#41;</em>
            </label>
            <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror"
                id="edit_bukti_transfer" name="bukti_transfer" accept="image/*">
            <div class="form-text text-muted">
                Maksimal 2MB (jpeg, png, jpg, gif). Kosongkan jika tidak ingin mengubah.
            </div>
            @error('bukti_transfer')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            @if ($donation->bukti_transfer)
                <div class="mt-3 p-3 bg-light rounded-3">
                    <small class="text-muted fw-semibold">Bukti saat ini:</small><br>
                    <img src="{{ asset('storage/' . $donation->bukti_transfer) }}" alt="Bukti Transfer"
                        class="img-thumbnail mt-2 shadow-sm" style="max-height: 120px; cursor: pointer;"
                        onclick="showImagePreview('{{ asset('storage/' . $donation->bukti_transfer) }}')">
                    <div class="form-text text-muted mt-1">
                        <i class="bi bi-info-circle"></i> Klik gambar untuk memperbesar
                    </div>
                </div>
            @endif
        </div>
    </div>

    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-outline-secondary rounded-3 px-4" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-1"></i> Batal
        </button>
        <button type="button" class="btn btn-warning text-dark rounded-3 px-4" id="btnSubmitEdit">
            <i class="bi bi-save-fill me-1"></i> Update Donasi
        </button>
    </div>
</form>

{{-- Script untuk Auto-Select Kas Tunai --}}
<script>
    $(document).ready(function() {
        const kasTunaiId = {{ $kasTunai ? $kasTunai->id : 'null' }};
        const $editMetodePembayaran = $('#edit_metode_pembayaran');
        const $editBankAccountSelect = $('#edit_bank_account_id');
        const $editRekeningHint = $('#edit_rekening_hint');

        console.log('Edit Form - Kas Tunai ID:', kasTunaiId);
        console.log('Edit Form - Current Metode:', $editMetodePembayaran.val());
        console.log('Edit Form - Current Bank:', $editBankAccountSelect.val());

        // Function untuk update rekening dropdown di modal edit
        function updateEditRekeningDropdown() {
            const metode = $editMetodePembayaran.val();
            const currentBankId = $editBankAccountSelect.val(); // Simpan value saat ini

            console.log('Updating edit rekening dropdown, metode:', metode);
            console.log('Current bank ID before destroy:', currentBankId);

            // Destroy Select2 dulu
            if ($editBankAccountSelect.hasClass('select2-hidden-accessible')) {
                $editBankAccountSelect.select2('destroy');
            }

            if (!metode) {
                $editRekeningHint.text('Pilih metode pembayaran terlebih dahulu')
                    .removeClass('text-success').addClass('text-muted');

                $editBankAccountSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#editDonationModal')
                });
                return;
            }

            // Handle berdasarkan metode
            if (metode === 'Uang Tunai') {
                if (kasTunaiId) {
                    // Init Select2 dengan filter - hanya tampilkan Kas Tunai
                    $editBankAccountSelect.select2({
                        theme: 'bootstrap-5',
                        width: '100%',
                        dropdownParent: $('#editDonationModal'),
                        templateResult: function(option) {
                            if (!option.id) return option.text;
                            const $option = $(option.element);
                            const dataType = $option.data('type');
                            if (dataType !== 'cash') return null;
                            return option.text;
                        }
                    });

                    // Auto-select Kas Tunai (atau preserve value jika sudah Kas Tunai)
                    if (currentBankId == kasTunaiId) {
                        $editBankAccountSelect.val(currentBankId).trigger('change');
                    } else {
                        $editBankAccountSelect.val(kasTunaiId).trigger('change');
                    }

                    $editRekeningHint.text('✓ Otomatis dipilih: Kas Tunai')
                        .removeClass('text-muted').addClass('text-success');
                } else {
                    alert('⚠️ Error: Data Kas Tunai tidak ditemukan.');
                }
            } else {
                // Metode lain - Init Select2 dengan filter - hide Kas Tunai
                $editBankAccountSelect.select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    dropdownParent: $('#editDonationModal'),
                    templateResult: function(option) {
                        if (!option.id) return option.text;
                        const $option = $(option.element);
                        const dataType = $option.data('type');
                        if (dataType === 'cash') return null;
                        return option.text;
                    }
                });

                // Preserve value jika bukan Kas Tunai
                if (currentBankId && currentBankId != kasTunaiId) {
                    $editBankAccountSelect.val(currentBankId).trigger('change');
                    console.log('Preserved bank ID:', currentBankId);
                } else {
                    $editBankAccountSelect.val('').trigger('change');
                    console.log('Reset bank value (was cash or empty)');
                }

                $editRekeningHint.text('Pilih rekening bank tujuan')
                    .removeClass('text-success').addClass('text-muted');
            }
        }

        // Event listener untuk perubahan metode pembayaran
        $editMetodePembayaran.on('change', function() {
            console.log('Metode pembayaran changed to:', $(this).val());
            updateEditRekeningDropdown();
        });

        // Prevent user dari mengubah rekening jika metode = Uang Tunai
        $editBankAccountSelect.on('change', function() {
            const metode = $editMetodePembayaran.val();
            const selectedValue = $(this).val();

            if (metode === 'Uang Tunai' && selectedValue != kasTunaiId) {
                alert('⚠️ Untuk metode Uang Tunai, rekening harus "Kas Tunai"');
                $(this).val(kasTunaiId).trigger('change');
            }
        });

        // Trigger saat form pertama kali loaded
        setTimeout(function() {
            console.log('Initializing dropdown with current values...');
            console.log('Current metode:', $editMetodePembayaran.val());
            console.log('Current bank:', $editBankAccountSelect.val());

            // Trigger update untuk sync dengan data saat ini
            updateEditRekeningDropdown();
        }, 500);
    });
</script>
