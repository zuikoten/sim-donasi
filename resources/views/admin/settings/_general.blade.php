<form action="{{ route('admin.settings.general.update') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
    @csrf

    <!-- Toggle Edit Button -->
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-outline-primary" id="toggleEditBtn">
            <i class="bi bi-pencil me-2"></i>Edit
        </button>
    </div>

    <div class="row">
        <!-- Site Title -->
        <div class="col-12 mb-4">
            <label for="site_title" class="form-label fw-bold">
                <i class="bi bi-globe me-2"></i>Site Title
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="form-control-plaintext border rounded p-2 bg-light">
                    {{ $settings['site_title'] ?? 'Not set' }}
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <input type="text" class="form-control @error('site_title') is-invalid @enderror" id="site_title"
                    name="site_title" value="{{ old('site_title', $settings['site_title'] ?? '') }}"
                    placeholder="Masukan Judul Website">
                @error('site_title')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Site Description -->
        <div class="col-12 mb-4">
            <label for="site_description" class="form-label fw-bold">
                <i class="bi bi-card-text me-2"></i>Site Description
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="form-control-plaintext border rounded p-2 bg-light" style="min-height: 60px;">
                    {{ $settings['site_description'] ?? 'Not set' }}
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <textarea class="form-control @error('site_description') is-invalid @enderror" id="site_description"
                    name="site_description" rows="3" maxlength="200"
                    placeholder="Masukan deskripsi singkat tentang websitemu (maks 80 characters)">{{ old('site_description', $settings['site_description'] ?? '') }}</textarea>
                <div class="d-flex justify-content-between align-items-center mt-1">
                    <small class="text-muted">Deskripsi singkat untuk SEO dan sosial media.</small>
                    <small class="text-muted">
                        <span id="charCount">0</span>/200 characters
                    </small>
                </div>
                @error('site_description')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Favicon -->
        <div class="col-md-6 mb-4">
            <label for="favicon" class="form-label fw-bold">
                <i class="bi bi-star me-2"></i>Favicon
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="border rounded p-3 bg-light">
                    @if (!empty($settings['favicon']))
                        <img src="{{ asset('public/storage/' . $settings['favicon']) }}" alt="Current Favicon"
                            class="img-thumbnail" style="max-width: 64px; max-height: 64px;">
                        <div class="mt-2">
                            <small class="text-muted">{{ basename($settings['favicon']) }}</small>
                        </div>
                    @else
                        <span class="text-muted">
                            <i class="bi bi-image me-1"></i>No favicon uploaded
                        </span>
                    @endif
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <input type="file" class="form-control @error('favicon') is-invalid @enderror" id="favicon"
                    name="favicon" accept="image/png,image/x-icon,image/jpg">
                <small class="text-muted d-block mt-1">Recommended: 64x64 px, PNG/ICO, Max 512KB</small>

                @if (!empty($settings['favicon']))
                    <div class="mt-2 position-relative">
                        <label class="form-label fw-semibold small">Current Favicon:</label>
                        <img src="{{ asset('public/storage/' . $settings['favicon']) }}" alt="Current Favicon"
                            class="img-thumbnail d-block" style="max-width: 64px; max-height: 64px;"
                            id="favicon-current">
                    </div>
                    <div class="mt-2 position-relative d-none" id="favicon-preview-container">
                        <label class="form-label fw-semibold small">New Preview:</label>
                        <img src="#" alt="Favicon Preview" class="img-thumbnail d-block"
                            style="max-width: 64px; max-height: 64px;" id="favicon-preview">
                    </div>
                @else
                    <div class="mt-2">
                        <img src="#" alt="Favicon Preview" class="img-thumbnail d-none"
                            style="max-width: 64px; max-height: 64px;" id="favicon-preview">
                    </div>
                @endif

                @error('favicon')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Logo -->
        <div class="col-md-6 mb-4">
            <label for="logo" class="form-label fw-bold">
                <i class="bi bi-image me-2"></i>Website Logo
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="border rounded p-3 bg-light">
                    @if (!empty($settings['logo']))
                        <img src="{{ asset('public/storage/' . $settings['logo']) }}" alt="Current Logo" class="img-thumbnail"
                            style="max-width: 200px; max-height: 100px;">
                        <div class="mt-2">
                            <small class="text-muted">{{ basename($settings['logo']) }}</small>
                        </div>
                    @else
                        <span class="text-muted">
                            <i class="bi bi-image me-1"></i>No logo uploaded
                        </span>
                    @endif
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo"
                    name="logo" accept="image/png,image/jpeg,image/jpg,image/svg+xml">
                <small class="text-muted d-block mt-1">Recommended: 300px width, PNG/JPG/SVG, Max 2MB</small>

                @if (!empty($settings['logo']))
                    <div class="mt-2 position-relative">
                        <label class="form-label fw-semibold small">Current Logo:</label>
                        <img src="{{ asset('public/storage/' . $settings['logo']) }}" alt="Current Logo"
                            class="img-thumbnail d-block" style="max-width: 200px; max-height: 100px;"
                            id="logo-current">
                    </div>
                    <div class="mt-2 position-relative d-none" id="logo-preview-container">
                        <label class="form-label fw-semibold small">New Preview:</label>
                        <img src="#" alt="Logo Preview" class="img-thumbnail d-block"
                            style="max-width: 200px; max-height: 100px;" id="logo-preview">
                    </div>
                @else
                    <div class="mt-2">
                        <img src="#" alt="Logo Preview" class="img-thumbnail d-none"
                            style="max-width: 200px; max-height: 100px;" id="logo-preview">
                    </div>
                @endif

                @error('logo')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Action Buttons (Only visible in Edit Mode) -->
    <div class="d-flex justify-content-end gap-2 edit-mode d-none">
        <button type="button" class="btn btn-secondary" id="cancelBtn">
            <i class="bi bi-x-circle me-2"></i>Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save me-2"></i>Save Changes
        </button>
    </div>
</form>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleEditBtn');
            const cancelBtn = document.getElementById('cancelBtn');
            const viewModes = document.querySelectorAll('.view-mode');
            const editModes = document.querySelectorAll('.edit-mode');
            const siteDescription = document.getElementById('site_description');
            const charCount = document.getElementById('charCount');

            // Character counter for site description
            function updateCharCount() {
                const length = siteDescription.value.length;
                charCount.textContent = length;

                // Change color based on character count
                if (length > 100) {
                    charCount.classList.add('text-danger');
                    charCount.classList.remove('text-muted');
                } else if (length > 150) {
                    charCount.classList.add('text-warning');
                    charCount.classList.remove('text-muted', 'text-danger');
                } else {
                    charCount.classList.add('text-muted');
                    charCount.classList.remove('text-warning', 'text-danger');
                }
            }

            // Initialize character count
            if (siteDescription) {
                updateCharCount();
                siteDescription.addEventListener('input', updateCharCount);
            }

            // Toggle to Edit Mode
            toggleBtn.addEventListener('click', function() {
                viewModes.forEach(el => el.classList.add('d-none'));
                editModes.forEach(el => el.classList.remove('d-none'));
                toggleBtn.classList.add('d-none');

                // Update character count when entering edit mode
                if (siteDescription) {
                    updateCharCount();
                }
            });

            // Cancel and return to View Mode
            cancelBtn.addEventListener('click', function() {
                viewModes.forEach(el => el.classList.remove('d-none'));
                editModes.forEach(el => el.classList.add('d-none'));
                toggleBtn.classList.remove('d-none');

                // Reset form to original values
                document.getElementById('settingsForm').reset();

                // Reset character count
                if (siteDescription) {
                    updateCharCount();
                }

                // Hide preview images
                const faviconPreviewContainer = document.getElementById('favicon-preview-container');
                const logoPreviewContainer = document.getElementById('logo-preview-container');
                const faviconPreview = document.getElementById('favicon-preview');
                const logoPreview = document.getElementById('logo-preview');

                if (faviconPreviewContainer) {
                    faviconPreviewContainer.classList.add('d-none');
                }
                if (logoPreviewContainer) {
                    logoPreviewContainer.classList.add('d-none');
                }
                if (faviconPreview) {
                    faviconPreview.classList.add('d-none');
                }
                if (logoPreview) {
                    logoPreview.classList.add('d-none');
                }
            });

            // Image preview for favicon
            document.getElementById('favicon').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('favicon-preview');
                const container = document.getElementById('favicon-preview-container');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        if (container) {
                            container.classList.remove('d-none');
                        }
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('d-none');
                    if (container) {
                        container.classList.add('d-none');
                    }
                }
            });

            // Image preview for logo
            document.getElementById('logo').addEventListener('change', function(e) {
                const file = e.target.files[0];
                const preview = document.getElementById('logo-preview');
                const container = document.getElementById('logo-preview-container');

                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        preview.src = e.target.result;
                        preview.classList.remove('d-none');
                        if (container) {
                            container.classList.remove('d-none');
                        }
                    }
                    reader.readAsDataURL(file);
                } else {
                    preview.classList.add('d-none');
                    if (container) {
                        container.classList.add('d-none');
                    }
                }
            });
        });
    </script>
@endpush
