<form action="{{ route('admin.settings.contact.update') }}" method="POST" id="contactForm">
    @csrf

    <!-- Toggle Edit Button -->
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-outline-primary" id="toggleContactEditBtn">
            <i class="bi bi-pencil me-2"></i>Edit
        </button>
    </div>

    <div class="row">
        <!-- Office Address -->
        <div class="col-12 mb-4">
            <label for="office_address" class="form-label fw-bold">
                <i class="bi bi-geo-alt me-2"></i>Office Address
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="form-control-plaintext border rounded p-2 bg-light"
                    style="min-height: 80px; white-space: pre-wrap;">{{ $settings['office_address'] ?? 'Not set' }}
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <textarea class="form-control @error('office_address') is-invalid @enderror" id="office_address" name="office_address"
                    rows="3" placeholder="Enter complete office address">{{ old('office_address', $settings['office_address'] ?? '') }}</textarea>
                @error('office_address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Office Email -->
        <div class="col-md-6 mb-4">
            <label for="office_email" class="form-label fw-bold">
                <i class="bi bi-envelope me-2"></i>Office Email
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="form-control-plaintext border rounded p-2 bg-light">
                    @if (!empty($settings['office_email']))
                        <a href="mailto:{{ $settings['office_email'] }}" class="text-decoration-none">
                            {{ $settings['office_email'] ?? 'not set' }}
                        </a>
                    @else
                        <span class="text-muted">Not set</span>
                    @endif
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <input type="email" class="form-control @error('office_email') is-invalid @enderror" id="office_email"
                    name="office_email" value="{{ old('office_email', $settings['office_email'] ?? '') }}"
                    placeholder="info@example.com">
                @error('office_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Office Phone -->
        <div class="col-md-6 mb-4">
            <label for="office_phone" class="form-label fw-bold">
                <i class="bi bi-telephone me-2"></i>Office Phone
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                <div class="form-control-plaintext border rounded p-2 bg-light">
                    @if (!empty($settings['office_phone']))
                        @php
                            $phoneSetting = App\Models\Setting::where('key', 'office_phone')->first();
                        @endphp
                        <a href="tel:+{{ $settings['office_phone'] }}" class="text-decoration-none">
                            {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] }}
                        </a>
                    @else
                        <span class="text-muted">Not set</span>
                    @endif
                </div>
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <input type="text" class="form-control @error('office_phone') is-invalid @enderror" id="office_phone"
                    name="office_phone" value="{{ old('office_phone', $settings['office_phone'] ?? '') }}"
                    placeholder="+62 8xx-xxxx-xxxx" maxlength="22">
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle me-1"></i>Format: +62 XXX-XXXX-XXXX-X (auto-formatted)
                </small>
                @error('office_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Office Operational Hours -->
        <!-- Office Hours -->
        <div class="col-12 mb-4">
            <label class="form-label fw-bold">
                <i class="bi bi-clock-history me-2"></i>Jam Operasional
            </label>

            <!-- View Mode -->
            <x-office-hours-list :office-hours="$officeHours" />


            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <div id="office-hours-list">
                    @php
                        $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
                        $times = [];
                        for ($i = 0; $i < 24; $i++) {
                            $times[] = sprintf('%02d:00', $i);
                        }
                        $timezones = ['WIB', 'WITA', 'WIT'];
                    @endphp

                    @if (!empty($officeHours))
                        @foreach ($officeHours as $index => $hour)
                            <div class="row g-2 mb-2 office-hour-item">
                                <div class="col-md-3">
                                    <select name="office_hours[{{ $index }}][start_day]" class="form-select">
                                        @foreach ($days as $day)
                                            <option value="{{ $day }}" @selected($hour['start_day'] == $day)>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <select name="office_hours[{{ $index }}][end_day]" class="form-select">
                                        @foreach ($days as $day)
                                            <option value="{{ $day }}" @selected($hour['end_day'] == $day)>
                                                {{ $day }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="office_hours[{{ $index }}][start_time]" class="form-select">
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}" @selected($hour['start_time'] == $time)>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <select name="office_hours[{{ $index }}][end_time]" class="form-select">
                                        @foreach ($times as $time)
                                            <option value="{{ $time }}" @selected($hour['end_time'] == $time)>
                                                {{ $time }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <select name="office_hours[{{ $index }}][timezone]" class="form-select">
                                        @foreach ($timezones as $tz)
                                            <option value="{{ $tz }}" @selected($hour['timezone'] == $tz)>
                                                {{ $tz }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-1 d-flex align-items-center">
                                    <button type="button" class="btn btn-outline-danger btn-sm remove-hour">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Tombol Tambah -->
                <button type="button" id="add-hour" class="btn btn-outline-primary btn-sm mt-2">
                    <i class="bi bi-plus-lg me-1"></i>Tambah Jam Operasional
                </button>
            </div>
        </div>




        <!-- Google Maps Embed -->
        <div class="col-12 mb-4">
            <label for="google_maps_embed" class="form-label fw-bold">
                <i class="bi bi-map me-2"></i>Google Maps Embed Code
            </label>

            <!-- View Mode -->
            <div class="view-mode">
                @if (!empty($settings['google_maps_embed']))
                    <div class="border rounded p-2 bg-light mb-2">
                        <div class="ratio ratio-16x9" style="max-height: 400px;">
                            {!! $settings['google_maps_embed'] !!}
                        </div>
                    </div>
                    <small class="text-muted d-block">
                        <i class="bi bi-info-circle me-1"></i>Google Maps iframe is set
                    </small>
                @else
                    <div class="border rounded p-3 bg-light text-center">
                        <i class="bi bi-map text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mb-0 mt-2">No map configured</p>
                    </div>
                @endif
            </div>

            <!-- Edit Mode -->
            <div class="edit-mode d-none">
                <textarea class="form-control @error('google_maps_embed') is-invalid @enderror font-monospace" id="google_maps_embed"
                    name="google_maps_embed" rows="4"
                    placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
                <small class="text-muted d-block mt-1">
                    <i class="bi bi-info-circle me-1"></i>
                    Paste the complete iframe embed code from Google Maps.
                    <a href="https://www.google.com/maps" target="_blank" class="text-decoration-none">Get embed code
                        →</a>
                </small>
                @error('google_maps_embed')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror

                <!-- Live Preview -->
                @if (!empty($settings['google_maps_embed']))
                    <div class="mt-3">
                        <label class="form-label fw-semibold small">Current Map:</label>
                        <div class="ratio ratio-16x9 border rounded" style="max-height: 300px; pointer-events: none;">
                            {!! $settings['google_maps_embed'] !!}
                        </div>
                        <small class="text-muted d-block mt-1">
                            <i class="bi bi-eye me-1"></i>Preview only - interactions disabled
                        </small>
                    </div>
                @endif

                <div class="mt-3 d-none" id="map-preview-container">
                    <label class="form-label fw-semibold small">New Preview:</label>
                    <div class="ratio ratio-16x9 border rounded" style="max-height: 300px; pointer-events: none;"
                        id="map-preview"></div>
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-eye me-1"></i>Preview only - interactions disabled
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons (Only visible in Edit Mode) -->
    <div class="d-flex justify-content-end gap-2 edit-mode d-none">
        <button type="button" class="btn btn-secondary" id="cancelContactBtn">
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
            const toggleBtn = document.getElementById('toggleContactEditBtn');
            const cancelBtn = document.getElementById('cancelContactBtn');
            const contactForm = document.getElementById('contactForm');
            const addBtn = document.getElementById('add-hour');
            const container = document.getElementById('office-hours-list');

            if (!toggleBtn) {
                console.error('Toggle button not found!');
                return;
            }

            // Toggle to Edit Mode
            toggleBtn.addEventListener('click', function() {
                console.log('Edit button clicked');

                // Find all view-mode and edit-mode within contact form
                const viewModes = contactForm.querySelectorAll('.view-mode');
                const editModes = contactForm.querySelectorAll('.edit-mode');

                console.log('View modes:', viewModes.length);
                console.log('Edit modes:', editModes.length);

                viewModes.forEach(el => el.classList.add('d-none'));
                editModes.forEach(el => el.classList.remove('d-none'));
                toggleBtn.classList.add('d-none');
            });

            // Cancel and return to View Mode
            if (cancelBtn) {
                cancelBtn.addEventListener('click', function() {
                    console.log('Cancel button clicked');

                    const viewModes = contactForm.querySelectorAll('.view-mode');
                    const editModes = contactForm.querySelectorAll('.edit-mode');

                    viewModes.forEach(el => el.classList.remove('d-none'));
                    editModes.forEach(el => el.classList.add('d-none'));
                    toggleBtn.classList.remove('d-none');

                    // Reset form to original values
                    contactForm.reset();

                    // Hide map preview
                    const mapPreviewContainer = document.getElementById('map-preview-container');
                    const mapPreview = document.getElementById('map-preview');

                    if (mapPreviewContainer) {
                        mapPreviewContainer.classList.add('d-none');
                        mapPreview.innerHTML = '';
                    }
                });
            }

            // Live preview for Google Maps embed
            const googleMapsEmbed = document.getElementById('google_maps_embed');

            if (googleMapsEmbed) {
                let debounceTimer;
                const mapPreviewContainer = document.getElementById('map-preview-container');
                const mapPreview = document.getElementById('map-preview');

                googleMapsEmbed.addEventListener('input', function() {
                    clearTimeout(debounceTimer);

                    debounceTimer = setTimeout(function() {
                        const embedCode = googleMapsEmbed.value.trim();

                        if (embedCode && embedCode.includes('<iframe')) {
                            mapPreview.innerHTML = embedCode;
                            mapPreviewContainer.classList.remove('d-none');
                        } else {
                            mapPreview.innerHTML = '';
                            mapPreviewContainer.classList.add('d-none');
                        }
                    }, 500);
                });
            }

            // Phone number formatting
            const phoneInput = document.getElementById('office_phone');

            if (phoneInput) {
                phoneInput.addEventListener('input', function(e) {
                    let value = e.target.value;

                    // Hapus semua karakter selain angka dan +
                    let cleaned = value.replace(/[^\d+]/g, '');

                    // Pastikan selalu diawali dengan +62
                    if (!cleaned.startsWith('+62')) {
                        if (cleaned.startsWith('62')) {
                            cleaned = '+' + cleaned;
                        } else if (cleaned.startsWith('0')) {
                            cleaned = '+62' + cleaned.substring(1);
                        } else if (cleaned.startsWith('+')) {
                            cleaned = '+62';
                        } else if (cleaned.length > 0) {
                            cleaned = '+62' + cleaned;
                        }
                    }

                    // Ambil angka setelah +62
                    let numbers = cleaned.substring(3);

                    let formatted = '+62';

                    if (numbers.length > 0) {
                        formatted += ' ';
                        // Ambil 3 digit pertama
                        formatted += numbers.substring(0, 3);
                    }
                    if (numbers.length > 3) {
                        formatted += '-' + numbers.substring(3, 7);
                    }
                    if (numbers.length > 7) {
                        formatted += '-' + numbers.substring(7, 11);
                    }
                    if (numbers.length > 11) {
                        formatted += '-' + numbers.substring(11, 15);
                    }

                    // Update input
                    e.target.value = formatted;
                });

                // Format saat halaman dimuat
                if (phoneInput.value) {
                    phoneInput.dispatchEvent(new Event('input'));
                }
            }

            // Menambah/menghapus baris jam operasional
            if (addBtn && container) {
                let index = container.querySelectorAll('.office-hour-item').length;

                addBtn.addEventListener('click', () => {
                    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
                    const times = Array.from({
                        length: 24
                    }, (_, i) => `${String(i).padStart(2, '0')}:00`);
                    const timezones = ['WIB', 'WITA', 'WIT'];

                    const row = document.createElement('div');
                    row.classList.add('row', 'g-2', 'mb-2', 'office-hour-item');

                    row.innerHTML = `
                <div class="col-md-3">
                    <select name="office_hours[${index}][start_day]" class="form-select">
                        ${days.map(d => `<option value="${d}">${d}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-3">
                    <select name="office_hours[${index}][end_day]" class="form-select">
                        ${days.map(d => `<option value="${d}">${d}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="office_hours[${index}][start_time]" class="form-select">
                        ${times.map(t => `<option value="${t}">${t}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="office_hours[${index}][end_time]" class="form-select">
                        ${times.map(t => `<option value="${t}">${t}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-1">
                    <select name="office_hours[${index}][timezone]" class="form-select">
                        ${timezones.map(tz => `<option value="${tz}">${tz}</option>`).join('')}
                    </select>
                </div>
                <div class="col-md-1 d-flex align-items-center">
                    <button type="button" class="btn btn-outline-danger btn-sm remove-hour">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
            `;

                    container.appendChild(row);
                    index++;
                });

                // Hapus baris jam operasional
                container.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-hour')) {
                        e.target.closest('.office-hour-item').remove();
                    }
                });
            }

        });
    </script>
@endpush
