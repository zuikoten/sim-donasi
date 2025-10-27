<form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="row">
        <!-- Site Title -->
        <div class="col-12 mb-4">
            <label for="site_title" class="form-label fw-bold">
                <i class="bi bi-globe me-2"></i>Site Title
            </label>
            <input type="text" class="form-control @error('site_title') is-invalid @enderror" id="site_title"
                name="site_title" value="{{ old('site_title', $settings['site_title'] ?? '') }}"
                placeholder="Enter website title">
            @error('site_title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Favicon -->
        <div class="col-md-6 mb-4">
            <label for="favicon" class="form-label fw-bold">
                <i class="bi bi-star me-2"></i>Favicon
            </label>
            <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon"
                name="favicon" accept="image/png,image/x-icon,image/jpg">
            <small class="text-muted">Recommended: 64x64 px, PNG/ICO, Max 512KB</small>

            @if (isset($settings['favicon']) && $settings['favicon'])
                <div class="mt-2">
                    <img src="{{ asset_url($settings['favicon']) }}" alt="Current Favicon" class="image-preview"
                        id="favicon-preview">
                </div>
            @else
                <div class="mt-2">
                    <img src="#" alt="Favicon Preview" class="image-preview d-none" id="favicon-preview">
                </div>
            @endif

            @error('favicon')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Logo -->
        <div class="col-md-6 mb-4">
            <label for="logo" class="form-label fw-bold">
                <i class="bi bi-image me-2"></i>Website Logo
            </label>
            <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo"
                accept="image/png,image/jpeg,image/jpg,image/svg+xml">
            <small class="text-muted">Recommended: 300px width, PNG/JPG/SVG, Max 2MB</small>

            @if (isset($settings['logo']) && $settings['logo'])
                <div class="mt-2">
                    <img src="{{ asset_url($settings['logo']) }}" alt="Current Logo" class="image-preview"
                        id="logo-preview">
                </div>
            @else
                <div class="mt-2">
                    <img src="#" alt="Logo Preview" class="image-preview d-none" id="logo-preview">
                </div>
            @endif

            @error('logo')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Save Changes
        </button>
    </div>
</form>

@push('scripts')
    <script>
        // Image preview for favicon
        document.getElementById('favicon').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('favicon-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });

        // Image preview for logo
        document.getElementById('logo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('logo-preview');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.classList.remove('d-none');
                }
                reader.readAsDataURL(file);
            }
        });
    </script>
@endpush
