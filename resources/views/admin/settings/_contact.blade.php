<form action="{{ route('admin.settings.contact.update') }}" method="POST" id="contactForm">
    @csrf

    {{-- Toggle Edit Button --}}
    <div class="d-flex justify-content-end mb-3">
        <button type="button" class="btn btn-outline-primary" id="toggleContactEditBtn">
            <i class="bi bi-pencil me-2"></i>Edit
        </button>
    </div>

    {{-- Office Addresses --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-geo-alt me-2"></i>Office Addresses
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            @if (!empty($addresses))
                <ul class="list-group">
                    @foreach ($addresses as $address)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                @if ($address['is_primary'])
                                    <span class="badge bg-primary me-2">PRIMARY</span>
                                @endif
                                {{ $address['value'] }}
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="form-control-plaintext border rounded p-2 bg-light text-muted">
                    No addresses added yet
                </div>
            @endif
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <div id="addressList">
                @foreach ($addresses as $address)
                    <div class="input-group mb-2 address-item" data-index="{{ $address['index'] }}">
                        <span class="input-group-text">
                            <input type="radio" name="address_primary" value="{{ $address['index'] }}"
                                {{ $address['is_primary'] ? 'checked' : '' }} title="Set as primary">
                        </span>
                        <textarea class="form-control" name="addresses[{{ $address['index'] }}]" rows="2"
                            placeholder="Enter office address">{{ $address['value'] }}</textarea>
                        <button type="button" class="btn btn-outline-danger delete-address"
                            data-index="{{ $address['index'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm" id="addAddress">
                <i class="bi bi-plus-circle me-1"></i>Add Address
            </button>
            <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>Select radio button to set primary address
            </small>
        </div>
    </div>

    {{-- Office Phones --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-telephone me-2"></i>Office Phones
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            @if (!empty($phones))
                <ul class="list-group">
                    @foreach ($phones as $phone)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                @if ($phone['is_primary'])
                                    <span class="badge bg-primary me-2">PRIMARY</span>
                                @endif
                                <a href="tel:+{{ $phone['raw'] }}" class="text-decoration-none">
                                    {{ $phone['formatted'] }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="form-control-plaintext border rounded p-2 bg-light text-muted">
                    No phone numbers added yet
                </div>
            @endif
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <div id="phoneList">
                @foreach ($phones as $phone)
                    <div class="input-group mb-2 phone-item" data-index="{{ $phone['index'] }}">
                        <span class="input-group-text">
                            <input type="radio" name="phone_primary" value="{{ $phone['index'] }}"
                                {{ $phone['is_primary'] ? 'checked' : '' }} title="Set as primary">
                        </span>
                        <input type="text" class="form-control phone-input" name="phones[{{ $phone['index'] }}]"
                            value="{{ $phone['formatted'] }}" placeholder="+62 8xx-xxxx-xxxx" maxlength="22">
                        <button type="button" class="btn btn-outline-danger delete-phone"
                            data-index="{{ $phone['index'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm" id="addPhone">
                <i class="bi bi-plus-circle me-1"></i>Add Phone
            </button>
            <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>Format: +62 XXX-XXXX-XXXX (auto-formatted)
            </small>
        </div>
    </div>

    {{-- Office Emails --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-envelope me-2"></i>Office Emails
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            @if (!empty($emails))
                <ul class="list-group">
                    @foreach ($emails as $email)
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div>
                                @if ($email['is_primary'])
                                    <span class="badge bg-primary me-2">PRIMARY</span>
                                @endif
                                <a href="mailto:{{ $email['value'] }}" class="text-decoration-none">
                                    {{ $email['value'] }}
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="form-control-plaintext border rounded p-2 bg-light text-muted">
                    No emails added yet
                </div>
            @endif
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <div id="emailList">
                @foreach ($emails as $email)
                    <div class="input-group mb-2 email-item" data-index="{{ $email['index'] }}">
                        <span class="input-group-text">
                            <input type="radio" name="email_primary" value="{{ $email['index'] }}"
                                {{ $email['is_primary'] ? 'checked' : '' }} title="Set as primary">
                        </span>
                        <input type="email" class="form-control" name="emails[{{ $email['index'] }}]"
                            value="{{ $email['value'] }}" placeholder="info@example.com">
                        <button type="button" class="btn btn-outline-danger delete-email"
                            data-index="{{ $email['index'] }}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                @endforeach
            </div>
            <button type="button" class="btn btn-outline-success btn-sm" id="addEmail">
                <i class="bi bi-plus-circle me-1"></i>Add Email
            </button>
        </div>
    </div>

    {{-- Display Settings --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-eye me-2"></i>Public Website Display Settings
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-2">
                            <strong>Addresses:</strong>
                            <span class="badge bg-secondary">
                                {{ $displayModes['addresses'] === 'all' ? 'Show All' : 'Primary Only' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Phones:</strong>
                            <span class="badge bg-secondary">
                                {{ $displayModes['phones'] === 'all' ? 'Show All' : 'Primary Only' }}
                            </span>
                        </div>
                        <div class="col-md-4 mb-2">
                            <strong>Emails:</strong>
                            <span class="badge bg-secondary">
                                {{ $displayModes['emails'] === 'all' ? 'Show All' : 'Primary Only' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Addresses</label>
                            <select name="display_addresses" class="form-select form-select-sm">
                                <option value="primary_only"
                                    {{ $displayModes['addresses'] === 'primary_only' ? 'selected' : '' }}>
                                    Primary Only
                                </option>
                                <option value="all" {{ $displayModes['addresses'] === 'all' ? 'selected' : '' }}>
                                    Show All
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Phones</label>
                            <select name="display_phones" class="form-select form-select-sm">
                                <option value="primary_only"
                                    {{ $displayModes['phones'] === 'primary_only' ? 'selected' : '' }}>
                                    Primary Only
                                </option>
                                <option value="all" {{ $displayModes['phones'] === 'all' ? 'selected' : '' }}>
                                    Show All
                                </option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label small">Emails</label>
                            <select name="display_emails" class="form-select form-select-sm">
                                <option value="primary_only"
                                    {{ $displayModes['emails'] === 'primary_only' ? 'selected' : '' }}>
                                    Primary Only
                                </option>
                                <option value="all" {{ $displayModes['emails'] === 'all' ? 'selected' : '' }}>
                                    Show All
                                </option>
                            </select>
                        </div>
                    </div>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>
                        Choose what visitors see on your public website
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Office Hours (Existing) --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-clock-history me-2"></i>Office Hours
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            <x-office-hours-list :office-hours="$officeHours ?? []" />
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <div id="office-hours-list">
                @php
                    $days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
                    $times = array_map(fn($i) => sprintf('%02d:00', $i), range(0, 23));
                    $timezones = ['WIB', 'WITA', 'WIT'];
                @endphp

                @foreach ($officeHours ?? [] as $index => $hour)
                    <div class="row g-2 mb-2 office-hour-item">
                        <div class="col-md-3">
                            <select name="office_hours[{{ $index }}][start_day]"
                                class="form-select form-select-sm">
                                @foreach ($days as $day)
                                    <option value="{{ $day }}" @selected(($hour['start_day'] ?? '') == $day)>
                                        {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="office_hours[{{ $index }}][end_day]"
                                class="form-select form-select-sm">
                                @foreach ($days as $day)
                                    <option value="{{ $day }}" @selected(($hour['end_day'] ?? '') == $day)>
                                        {{ $day }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="office_hours[{{ $index }}][start_time]"
                                class="form-select form-select-sm">
                                @foreach ($times as $time)
                                    <option value="{{ $time }}" @selected(($hour['start_time'] ?? '') == $time)>
                                        {{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select name="office_hours[{{ $index }}][end_time]"
                                class="form-select form-select-sm">
                                @foreach ($times as $time)
                                    <option value="{{ $time }}" @selected(($hour['end_time'] ?? '') == $time)>
                                        {{ $time }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <select name="office_hours[{{ $index }}][timezone]"
                                class="form-select form-select-sm">
                                @foreach ($timezones as $tz)
                                    <option value="{{ $tz }}" @selected(($hour['timezone'] ?? '') == $tz)>
                                        {{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-hour">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
            <button type="button" id="add-hour" class="btn btn-outline-success btn-sm mt-2">
                <i class="bi bi-plus-circle me-1"></i>Add Office Hours
            </button>
        </div>
    </div>

    {{-- Google Maps (Existing) --}}
    <div class="mb-4">
        <label class="form-label fw-bold">
            <i class="bi bi-map me-2"></i>Google Maps Embed Code
        </label>

        {{-- View Mode --}}
        <div class="view-mode">
            @if (!empty($settings['google_maps_embed']))
                <div class="border rounded p-2 bg-light mb-2">
                    <div class="ratio ratio-16x9" style="max-height: 400px;">
                        {!! $settings['google_maps_embed'] !!}
                    </div>
                </div>
            @else
                <div class="border rounded p-3 bg-light text-center">
                    <i class="bi bi-map text-muted" style="font-size: 3rem;"></i>
                    <p class="text-muted mb-0 mt-2">No map configured</p>
                </div>
            @endif
        </div>

        {{-- Edit Mode --}}
        <div class="edit-mode d-none">
            <textarea class="form-control font-monospace" id="google_maps_embed" name="google_maps_embed" rows="4"
                placeholder='<iframe src="https://www.google.com/maps/embed?pb=..." width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>'>{{ old('google_maps_embed', $settings['google_maps_embed'] ?? '') }}</textarea>
            <small class="text-muted d-block mt-1">
                <i class="bi bi-info-circle me-1"></i>
                Paste the complete iframe embed code from Google Maps
            </small>

            @if (!empty($settings['google_maps_embed']))
                <div class="mt-3">
                    <label class="form-label fw-semibold small">Current Preview:</label>
                    <div class="ratio ratio-16x9 border rounded" style="max-height: 300px; pointer-events: none;">
                        {!! $settings['google_maps_embed'] !!}
                    </div>
                </div>
            @endif

            <div class="mt-3 d-none" id="map-preview-container">
                <label class="form-label fw-semibold small">New Preview:</label>
                <div class="ratio ratio-16x9 border rounded" style="max-height: 300px; pointer-events: none;"
                    id="map-preview"></div>
            </div>
        </div>
    </div>

    {{-- Action Buttons --}}
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
            const form = document.getElementById('contactForm');
            const toggleBtn = document.getElementById('toggleContactEditBtn');
            const cancelBtn = document.getElementById('cancelContactBtn');

            let nextAddressIndex = {{ $nextIndexes['address'] ?? 1 }};
            let nextPhoneIndex = {{ $nextIndexes['phone'] ?? 1 }};
            let nextEmailIndex = {{ $nextIndexes['email'] ?? 1 }};

            // Toggle Edit Mode
            toggleBtn?.addEventListener('click', function() {
                document.querySelectorAll('.view-mode').forEach(el => el.classList.add('d-none'));
                document.querySelectorAll('.edit-mode').forEach(el => el.classList.remove('d-none'));
                toggleBtn.classList.add('d-none');
            });

            // Cancel Edit
            cancelBtn?.addEventListener('click', function() {
                if (confirm('Discard all changes?')) {
                    location.reload();
                }
            });

            // Add Address
            document.getElementById('addAddress')?.addEventListener('click', function() {
                const container = document.getElementById('addressList');
                const isFirst = container.children.length === 0;

                const html = `
            <div class="input-group mb-2 address-item" data-index="${nextAddressIndex}">
                <span class="input-group-text">
                    <input type="radio" name="address_primary" value="${nextAddressIndex}" ${isFirst ? 'checked' : ''} title="Set as primary">
                </span>
                <textarea class="form-control" name="addresses[${nextAddressIndex}]" rows="2" placeholder="Enter office address"></textarea>
                <button type="button" class="btn btn-outline-danger delete-address" data-index="${nextAddressIndex}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', html);
                nextAddressIndex++;
            });

            // Delete Address
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-address')) {
                    if (confirm('Delete this address?')) {
                        e.target.closest('.address-item').remove();
                    }
                }
            });

            // Add Phone
            document.getElementById('addPhone')?.addEventListener('click', function() {
                const container = document.getElementById('phoneList');
                const isFirst = container.children.length === 0;

                const html = `
            <div class="input-group mb-2 phone-item" data-index="${nextPhoneIndex}">
                <span class="input-group-text">
                    <input type="radio" name="phone_primary" value="${nextPhoneIndex}" ${isFirst ? 'checked' : ''} title="Set as primary">
                </span>
                <input type="text" class="form-control phone-input" name="phones[${nextPhoneIndex}]" placeholder="+62 8xx-xxxx-xxxx" maxlength="22">
                <button type="button" class="btn btn-outline-danger delete-phone" data-index="${nextPhoneIndex}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', html);

                // Initialize phone formatting for new input
                const newInput = container.querySelector(`[name="phones[${nextPhoneIndex}]"]`);
                initPhoneFormatting(newInput);

                nextPhoneIndex++;
            });

            // Delete Phone
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-phone')) {
                    if (confirm('Delete this phone number?')) {
                        e.target.closest('.phone-item').remove();
                    }
                }
            });

            // Add Email
            document.getElementById('addEmail')?.addEventListener('click', function() {
                const container = document.getElementById('emailList');
                const isFirst = container.children.length === 0;

                const html = `
            <div class="input-group mb-2 email-item" data-index="${nextEmailIndex}">
                <span class="input-group-text">
                    <input type="radio" name="email_primary" value="${nextEmailIndex}" ${isFirst ? 'checked' : ''} title="Set as primary">
                </span>
                <input type="email" class="form-control" name="emails[${nextEmailIndex}]" placeholder="info@example.com">
                <button type="button" class="btn btn-outline-danger delete-email" data-index="${nextEmailIndex}">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
        `;
                container.insertAdjacentHTML('beforeend', html);
                nextEmailIndex++;
            });

            // Delete Email
            document.addEventListener('click', function(e) {
                if (e.target.closest('.delete-email')) {
                    if (confirm('Delete this email?')) {
                        e.target.closest('.email-item').remove();
                    }
                }
            });

            // Phone Formatting
            function initPhoneFormatting(input) {
                if (!input) return;

                input.addEventListener('input', function(e) {
                    let value = e.target.value;
                    let cleaned = value.replace(/[^\d+]/g, '');

                    if (!cleaned.startsWith('+62')) {
                        if (cleaned.startsWith('62')) {
                            cleaned = '+' + cleaned;
                        } else if (cleaned.startsWith('0')) {
                            cleaned = '+62' + cleaned.substring(1);
                        } else if (cleaned.length > 0) {
                            cleaned = '+62' + cleaned.replace(/^\+/, '');
                        }
                    }

                    let numbers = cleaned.substring(3);
                    let formatted = '+62';

                    if (numbers.length > 0) {
                        formatted += ' ' + numbers.substring(0, 3);
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

                    e.target.value = formatted;
                });
            }

            // Initialize phone formatting for existing inputs
            document.querySelectorAll('.phone-input').forEach(initPhoneFormatting);

            // Office Hours (Existing functionality)
            const addHourBtn = document.getElementById('add-hour');
            const hoursContainer = document.getElementById('office-hours-list');

            if (addHourBtn && hoursContainer) {
                let hourIndex = hoursContainer.querySelectorAll('.office-hour-item').length;

                addHourBtn.addEventListener('click', function() {
                    const days = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Ahad'];
                    const times = Array.from({
                        length: 24
                    }, (_, i) => `${String(i).padStart(2, '0')}:00`);
                    const timezones = ['WIB', 'WITA', 'WIT'];

                    const html = `
                <div class="row g-2 mb-2 office-hour-item">
                    <div class="col-md-3">
                        <select name="office_hours[${hourIndex}][start_day]" class="form-select form-select-sm">
                            ${days.map(d => `<option value="${d}">${d}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select name="office_hours[${hourIndex}][end_day]" class="form-select form-select-sm">
                            ${days.map(d => `<option value="${d}">${d}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="office_hours[${hourIndex}][start_time]" class="form-select form-select-sm">
                            ${times.map(t => `<option value="${t}">${t}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-2">
                        <select name="office_hours[${hourIndex}][end_time]" class="form-select form-select-sm">
                            ${times.map(t => `<option value="${t}">${t}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-1">
                        <select name="office_hours[${hourIndex}][timezone]" class="form-select form-select-sm">
                            ${timezones.map(tz => `<option value="${tz}">${tz}</option>`).join('')}
                        </select>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-outline-danger btn-sm remove-hour">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
            `;

                    hoursContainer.insertAdjacentHTML('beforeend', html);
                    hourIndex++;
                });

                hoursContainer.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-hour')) {
                        e.target.closest('.office-hour-item').remove();
                    }
                });
            }

            // Google Maps Preview
            const mapsTextarea = document.getElementById('google_maps_embed');
            if (mapsTextarea) {
                let debounceTimer;
                const previewContainer = document.getElementById('map-preview-container');
                const preview = document.getElementById('map-preview');

                mapsTextarea.addEventListener('input', function() {
                    clearTimeout(debounceTimer);
                    debounceTimer = setTimeout(function() {
                        const embedCode = mapsTextarea.value.trim();
                        if (embedCode && embedCode.includes('<iframe')) {
                            preview.innerHTML = embedCode;
                            previewContainer.classList.remove('d-none');
                        } else {
                            preview.innerHTML = '';
                            previewContainer.classList.add('d-none');
                        }
                    }, 500);
                });
            }
        });
    </script>
@endpush
