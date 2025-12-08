@section ('content')

. . . tombol edit

. . . tampilan data donasi (dari @show donationController)

<!-- Modal Edit Donasi -->
    <div class="modal fade" id="editDonationModal" tabindex="-1" aria-labelledby="editDonationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.donations.update', $donation->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-header bg-warning text-dark">
                        <h5 class="modal-title" id="editDonationModalLabel">
                            <i class="fas fa-edit"></i> Edit Donasi #{{ $donation->id }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle"></i>
                            <strong>Perhatian!</strong> Perubahan data donasi akan mempengaruhi total dana terkumpul program
                            secara otomatis.
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Donatur <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('user_id') is-invalid @enderror" id="user_id"
                                        name="user_id" required>
                                        <option value="">-- Pilih Donatur --</option>
                                        @foreach ($donaturs as $donatur)
                                            <option value="{{ $donatur->id }}"
                                                {{ old('user_id', $donation->user_id) == $donatur->id ? 'selected' : '' }}>
                                                {{ $donatur->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="program_id" class="form-label">Program Donasi <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('program_id') is-invalid @enderror" id="program_id"
                                        name="program_id" required>
                                        <option value="">-- Pilih Program --</option>
                                        @foreach ($programs as $program)
                                            <option value="{{ $program->id }}"
                                                {{ old('program_id', $donation->program_id) == $program->id ? 'selected' : '' }}>
                                                {{ $program->nama_program }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('program_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="nominal" class="form-label">Nominal Donasi <span
                                            class="text-danger">*</span></label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" class="form-control @error('nominal') is-invalid @enderror"
                                            id="nominal" name="nominal"
                                            value="{{ old('nominal', $donation->nominal) }}" min="1000"
                                            step="1000" required>
                                    </div>
                                    @error('nominal')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="metode_pembayaran" class="form-label">Metode Pembayaran <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('metode_pembayaran') is-invalid @enderror"
                                        id="metode_pembayaran" name="metode_pembayaran" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="Uang Tunai"
                                            {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'Uang Tunai' ? 'selected' : '' }}>
                                            Uang Tunai</option>
                                        <option value="Transfer Bank"
                                            {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'Transfer Bank' ? 'selected' : '' }}>
                                            Transfer Bank</option>
                                        <option value="QRIS"
                                            {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'QRIS' ? 'selected' : '' }}>
                                            QRIS</option>
                                        <option value="E-Wallet"
                                            {{ old('metode_pembayaran', $donation->metode_pembayaran) == 'E-Wallet' ? 'selected' : '' }}>
                                            E-Wallet</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="bank_account_id" class="form-label">Rekening Tujuan <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('bank_account_id') is-invalid @enderror"
                                        id="bank_account_id" name="bank_account_id" required>
                                        <option value="">-- Pilih Rekening --</option>
                                        @foreach ($bankAccounts as $bank)
                                            <option value="{{ $bank->id }}"
                                                {{ old('bank_account_id', $donation->bank_account_id) == $bank->id ? 'selected' : '' }}>
                                                {{ $bank->bank_name }} - {{ $bank->account_number }}
                                                ({{ $bank->account_holder }})
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('bank_account_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select @error('status') is-invalid @enderror" id="status"
                                        name="status" required>
                                        <option value="menunggu"
                                            {{ old('status', $donation->status) == 'menunggu' ? 'selected' : '' }}>Menunggu
                                        </option>
                                        <option value="terverifikasi"
                                            {{ old('status', $donation->status) == 'terverifikasi' ? 'selected' : '' }}>
                                            Terverifikasi</option>
                                        <option value="ditolak"
                                            {{ old('status', $donation->status) == 'ditolak' ? 'selected' : '' }}>Ditolak
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan_tambahan" class="form-label">Keterangan Tambahan</label>
                            <textarea class="form-control @error('keterangan_tambahan') is-invalid @enderror" id="keterangan_tambahan"
                                name="keterangan_tambahan" rows="3">{{ old('keterangan_tambahan', $donation->keterangan_tambahan) }}</textarea>
                            @error('keterangan_tambahan')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="bukti_transfer" class="form-label">
                                Bukti Transfer
                                <small class="text-muted">(Kosongkan jika tidak ingin mengubah)</small>
                            </label>
                            <input type="file" class="form-control @error('bukti_transfer') is-invalid @enderror"
                                id="bukti_transfer" name="bukti_transfer" accept="image/*">
                            @error('bukti_transfer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            @if ($donation->bukti_transfer)
                                <div class="mt-2">
                                    <small class="text-muted">Bukti saat ini:</small><br>
                                    <img src="{{ asset('storage/' . $donation->bukti_transfer) }}" alt="Bukti Transfer"
                                        class="img-thumbnail mt-1" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times"></i> Batal
                        </button>
                        <button type="submit" class="btn btn-warning">
                            <i class="fas fa-save"></i> Update Donasi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Preview Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Bukti Transfer</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    @if ($donation->bukti_transfer)
                        <img src="{{ asset('storage/' . $donation->bukti_transfer) }}" alt="Bukti Transfer"
                            class="img-fluid">
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Auto-open edit modal if there are validation errors
        @if ($errors->any())
            var editModal = new bootstrap.Modal(document.getElementById('editDonationModal'));
            editModal.show();
        @endif
    </script>
@endpush