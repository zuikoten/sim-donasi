<form action="{{ route('admin.settings.contact.update') }}" method="POST">
    @csrf

    <div class="row">
        <!-- Office Address -->
        <div class="col-12 mb-4">
            <label for="office_address" class="form-label fw-bold">
                <i class="bi bi-geo-alt me-2"></i>Office Address
            </label>
            <textarea class="form-control @error('office_address') is-invalid @enderror" id="office_address" name="office_address"
                rows="3" placeholder="Enter complete office address">{{ old('office_address', $settings['office_address'] ?? '') }}</textarea>
            @error('office_address')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Office Email -->
        <div class="col-md-6 mb-4">
            <label for="office_email" class="form-label fw-bold">
                <i class="bi bi-envelope me-2"></i>Office Email
            </label>
            <input type="email" class="form-control @error('office_email') is-invalid @enderror" id="office_email"
                name="office_email" value="{{ old('office_email', $settings['office_email'] ?? '') }}"
                placeholder="info@example.com">
            @error('office_email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Office Phone -->
        <div class="col-md-6 mb-4">
            <label for="office_phone" class="form-label fw-bold">
                <i class="bi bi-telephone me-2"></i>Office Phone
            </label>
            <input type="text" class="form-control @error('office_phone') is-invalid @enderror" id="office_phone"
                name="office_phone" value="{{ old('office_phone', $settings['office_phone'] ?? '') }}"
                placeholder="+62 21 1234567">
            @error('office_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Save Changes
        </button>
    </div>
</form>
