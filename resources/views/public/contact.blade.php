@extends('layouts.guest')

@section('title', 'Kontak Kami')

@section('content')
    <!-- Hero Section -->
    <section class="hero-contact-section text-white py-5">
        <div class="container py-5">
            <div class="row justify-content-center text-center">
                <div class="col-lg-10">
                    <h1 class="display-4 fw-bold mb-3 hero-title">
                        Hubungi Kami
                    </h1>
                    <p class="lead hero-subtitle">
                        Ada pertanyaan, saran, atau ingin berkolaborasi? Kami siap mendengar dari Anda.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info Section -->
    <section class="py-5 contact-section">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm contact-card">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-circle me-3">
                                    <i class="bi bi-envelope-heart"></i>
                                </div>
                                <div>
                                    <h5 class="card-title mb-1">Kirim Pesan</h5>
                                    <p class="text-muted small mb-0">Kami akan merespons dalam 1x24 jam</p>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show modern-alert" role="alert">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <strong>Berhasil!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('contact.submit') }}" id="contactForm">
                                @csrf
                                <div class="row g-3">
                                    <!-- Name Input -->
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            <i class="bi bi-person text-primary me-1"></i>
                                            Nama Lengkap
                                        </label>
                                        <input type="text"
                                            class="form-control modern-input @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}"
                                            placeholder="Masukkan nama Anda" required>
                                        @error('name')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Email Input -->
                                    <div class="col-md-6">
                                        <label for="email" class="form-label fw-semibold">
                                            <i class="bi bi-envelope text-primary me-1"></i>
                                            Email
                                        </label>
                                        <input type="email"
                                            class="form-control modern-input @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}"
                                            placeholder="nama@email.com" required>
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Subject Input -->
                                    <div class="col-12">
                                        <label for="subject" class="form-label fw-semibold">
                                            <i class="bi bi-chat-left-text text-primary me-1"></i>
                                            Subjek
                                        </label>
                                        <input type="text"
                                            class="form-control modern-input @error('subject') is-invalid @enderror"
                                            id="subject" name="subject" value="{{ old('subject') }}"
                                            placeholder="Topik pesan Anda" required>
                                        @error('subject')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Message Textarea -->
                                    <div class="col-12">
                                        <label for="message" class="form-label fw-semibold d-flex justify-content-between">
                                            <span>
                                                <i class="bi bi-pencil-square text-primary me-1"></i>
                                                Pesan
                                            </span>
                                            <span class="text-muted small" id="charCount">0 / 500</span>
                                        </label>
                                        <textarea class="form-control modern-input @error('message') is-invalid @enderror" id="message" name="message"
                                            rows="5" maxlength="500" placeholder="Tulis pesan Anda di sini..." required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">
                                                <i class="bi bi-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12 mt-4">
                                        <button type="submit" class="btn btn-primary premium-btn w-100" id="submitBtn">
                                            <span class="btn-content">
                                                <i class="bi bi-send-fill me-2"></i>
                                                Kirim Pesan
                                            </span>
                                            <span class="btn-loader d-none">
                                                <span class="spinner-border spinner-border-sm me-2"></span>
                                                Mengirim...
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4 contact-card">
                        <div class="card-body">
                            <h5 class="card-title mb-3">Informasi Kontak</h5>

                            {{-- MULTI CONTACT DISPLAY --}}
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-geo-alt text-primary"></i> Alamat
                                </h6>

                                @if (isset($addresses) && !empty($addresses))
                                    @foreach ($addresses as $addr)
                                        <div class="mb-3">
                                            {{-- Main Office Label untuk Primary --}}
                                            @if ($addr['is_primary'])
                                                <div class="d-flex align-items-center mb-1">
                                                    <span class="badge bg-primary me-2">
                                                        <i class="bi bi-star-fill"></i> Main Office
                                                    </span>
                                                </div>
                                                {{-- Primary Address: Dark text, highlighted background --}}
                                                <div class="p-3 bg-primary bg-opacity-10 border border-primary rounded">
                                                    <p class="mb-0 fw-bold text-dark">{{ $addr['value'] }}</p>
                                                </div>
                                            @else
                                                {{-- Non-Primary Address: Muted text, light background --}}
                                                <div class="p-3 bg-light border rounded">
                                                    <p class="mb-0 text-muted">{{ $addr['value'] }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                @elseif(isset($settings['office_address']))
                                    {{-- Legacy Fallback --}}
                                    <div class="p-3 bg-light border rounded">
                                        <p class="mb-0">{{ $settings['office_address'] }}</p>
                                    </div>
                                @else
                                    <div class="p-3 bg-light border rounded">
                                        <p class="mb-0 text-muted fst-italic">Alamat belum diatur</p>
                                    </div>
                                @endif
                            </div>

                            {{-- PHONES --}}
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-telephone text-primary"></i> Telepon
                                </h6>

                                @if (isset($phones) && !empty($phones))
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($phones as $ph)
                                            <div>
                                                <a href="tel:+{{ $ph['raw'] }}"
                                                    class="text-decoration-none d-inline-flex align-items-center gap-2 
                                                      {{ $ph['is_primary'] ? 'fw-bold text-primary' : 'fw-semibold text-secondary' }}">
                                                    @if ($ph['is_primary'])
                                                        <i class="bi bi-bookmark-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-bookmark-dash-fill text-secondary"></i>
                                                    @endif
                                                    <span>{{ $ph['formatted'] }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(isset($settings['office_phone']))
                                    {{-- Legacy Fallback --}}
                                    <div>
                                        @php
                                            $phoneSetting = App\Models\Setting::where('key', 'office_phone')->first();
                                        @endphp
                                        <a href="tel:+{{ $settings['office_phone'] }}" class="text-decoration-none">
                                            {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] }}
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted fst-italic mb-0">Telepon belum diatur</p>
                                @endif
                            </div>

                            {{-- EMAILS --}}
                            <div class="mb-4">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-envelope text-primary"></i> Email
                                </h6>

                                @if (isset($emails) && !empty($emails))
                                    <div class="d-flex flex-column gap-2">
                                        @foreach ($emails as $em)
                                            <div>
                                                <a href="mailto:{{ $em['value'] }}"
                                                    class="text-decoration-none d-inline-flex align-items-center gap-2 
                                                      {{ $em['is_primary'] ? 'fw-bold text-primary' : 'fw-semibold text-secondary' }}">
                                                    @if ($em['is_primary'])
                                                        <i class="bi bi-bookmark-star-fill text-warning"></i>
                                                    @else
                                                        <i class="bi bi-bookmark-dash-fill text-secondary"></i>
                                                    @endif
                                                    <span>{{ $em['value'] }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @elseif(isset($settings['office_email']))
                                    {{-- Legacy Fallback --}}
                                    <div>
                                        <a href="mailto:{{ $settings['office_email'] }}" class="text-decoration-none">
                                            {{ $settings['office_email'] }}
                                        </a>
                                    </div>
                                @else
                                    <p class="text-muted fst-italic mb-0">Email belum diatur</p>
                                @endif
                            </div>

                            {{-- OFFICE HOURS --}}
                            <div class="mb-3">
                                <h6 class="text-muted mb-3">
                                    <i class="bi bi-clock text-primary"></i> Jam Operasional
                                </h6>
                                <x-office-hours-list :office-hours="$officeHours" />
                            </div>
                            {{-- akhir section kontak --}}
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card shadow-sm contact-card">
                        <div class="card-body">
                            <h5 class="card-title">Ikuti Kami</h5>
                            <p>Ikuti media sosial kami untuk mendapatkan update terbaru.</p>

                            @php
                                $socialMedia = \App\Models\SocialMedia::where('is_active', true)
                                    ->orderBy('order', 'asc')
                                    ->get();
                            @endphp

                            @if ($socialMedia->count() > 0)
                                @if ($socialMedia->count() <= 6)
                                    {{-- Jika sedikit (<=6), pakai circular buttons --}}
                                    <div class="d-flex gap-3 flex-wrap justify-content-start">
                                        @foreach ($socialMedia as $social)
                                            <a href="{{ $social->url }}" target="_blank"
                                                class="btn btn-outline-{{ $social->getColorClass() }} rounded-circle p-0 social-btn"
                                                style="width: 48px; height: 48px; display: flex; align-items: center; justify-content: center;"
                                                title="{{ $social->platform_name }}" rel="noopener noreferrer">
                                                <i class="{{ $social->icon_class }} fs-5"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    {{-- Jika banyak (>6), pakai grid layout --}}
                                    <div class="row g-2">
                                        @foreach ($socialMedia as $social)
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <a href="{{ $social->url }}" target="_blank"
                                                    class="btn btn-outline-{{ $social->getColorClass() }} w-100"
                                                    title="{{ $social->platform_name }}" rel="noopener noreferrer">
                                                    <i class="{{ $social->icon_class }} me-1"></i>
                                                    <span
                                                        class="small">{{ Str::limit($social->platform_name, 12) }}</span>
                                                </a>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            @else
                                <p class="text-muted mb-0">
                                    <i class="bi bi-info-circle me-2"></i>
                                    Belum ada social media yang terdaftar.
                                </p>
                            @endif
                        </div>
                    </div>

                    @push('styles')
                        <style>
                            /* Smooth hover transition */
                            .social-btn {
                                transition: all 0.3s ease;
                            }

                            .social-btn:hover {
                                transform: translateY(-3px);
                                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                            }
                        </style>
                    @endpush
                    <!-- END Social Media -->
                </div>
            </div>
        </div>
    </section>

    <!-- Map Section (Optional) -->
    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center mb-4">
                    <h5 class="fw-bold">Temukan Lokasi Kami</h5>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="ratio ratio-16x9">
                        {!! $settings['google_maps_embed'] !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@push('styles')
    <style>
        /* HERO SECTION STYLE */
        .hero-contact-section {
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            position: relative;
            overflow: hidden;
        }

        /* Glow gradient overlay */
        .hero-contact-section::before {
            content: "";
            position: absolute;
            top: -120px;
            right: -120px;
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.18);
            filter: blur(80px);
            border-radius: 50%;
        }

        /* Heading Shine */
        .hero-title {
            text-shadow: 0 5px 18px rgba(0, 0, 0, 0.25);
            letter-spacing: 0.5px;
        }

        .hero-subtitle {
            opacity: 0.92;
            font-weight: 300;
        }
    </style>

    <style>
        /* SECTION BACKGROUND */
        .contact-section {
            background: #f8fafc;
        }

        /* GENERAL CARD UPGRADE */
        .contact-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.06);
            transition: all 0.3s ease;
        }

        .contact-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
        }

        .contact-card .card-body {
            padding: 2rem;
        }

        /* FORM INPUT PREMIUM STYLE */
        .contact-card .form-control {
            border-radius: 12px;
            padding: 12px 14px;
            border: 1px solid #d7dde5;
            transition: all 0.25s ease;
        }

        .contact-card .form-control:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.15);
        }

        /* HEADINGS */
        .contact-title {
            font-weight: 700;
            font-size: 1.4rem;
            letter-spacing: 0.3px;
        }

        .contact-subheading-icon {
            font-size: 1.2rem;
            margin-right: 6px;
        }

        /* CONTACT LIST BOXES */
        .contact-box {
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #e1e7ee;
            background: #ffffff;
        }

        .contact-box-primary {
            border: 1px solid #0d6efd;
            background: rgba(13, 110, 253, 0.06);
        }

        /* SOCIAL ICON UPGRADE */
        .social-btn {
            width: 50px;
            height: 50px;
            border-radius: 50% !important;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            transition: 0.3s ease;
        }

        .social-btn:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.15);
        }
    </style>

    <!-- CONTACT FORM -->
    <style>
        /* Icon Circle */
        .icon-circle {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
        }

        /* Modern Input Styling */
        .modern-input {
            border: 2px solid #e1e7ee;
            border-radius: 10px;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }

        .modern-input:focus {
            border-color: #0d6efd;
            box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
            background: #ffffff;
        }

        .modern-input::placeholder {
            color: #adb5bd;
            font-size: 0.9rem;
        }

        /* Label Enhancement */
        .form-label {
            color: #495057;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .form-label i {
            font-size: 1rem;
        }

        /* Premium Button */
        .premium-btn {
            padding: 14px 28px;
            font-size: 1rem;
            font-weight: 600;
            border-radius: 10px;
            border: none;
            background: linear-gradient(135deg, #0d6efd 0%, #2034c9 100%);
            box-shadow: 0 4px 12px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .premium-btn::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            border-radius: 50%;
            background: rgba(179, 172, 255, 0.459);
            transform: translate(-50%, -50%);
            transition: width 0.6s, height 0.6s;
        }

        .premium-btn:hover::before {
            width: 300px;
            height: 300px;
        }

        .premium-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(13, 110, 253, 0.4);
        }

        .premium-btn:active {
            transform: translateY(0);
        }

        /* Button Loading State */
        .premium-btn.loading .btn-content {
            display: none;
        }

        .premium-btn.loading .btn-loader {
            display: inline-block !important;
        }

        /* Modern Alert */
        .modern-alert {
            border: none;
            border-radius: 10px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-left: 4px solid #047857;
        }

        .modern-alert .btn-close {
            filter: brightness(0) invert(1);
        }

        /* Character Counter */
        #charCount {
            font-size: 0.85rem;
            transition: color 0.3s ease;
        }

        #charCount.warning {
            color: #f59e0b !important;
        }

        #charCount.danger {
            color: #dc3545 !important;
        }

        /* Input Error State */
        .modern-input.is-invalid {
            border-color: #dc3545;
            animation: shake 0.3s;
        }

        @keyframes shake {

            0%,
            100% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-5px);
            }

            75% {
                transform: translateX(5px);
            }
        }

        /* Invalid Feedback Enhancement */
        .invalid-feedback {
            font-size: 0.85rem;
            margin-top: 0.5rem;
            display: block;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .icon-circle {
                width: 45px;
                height: 45px;
                font-size: 1.3rem;
            }

            .premium-btn {
                font-size: 0.95rem;
                padding: 12px 24px;
            }
        }

        /* Focus Visible (Accessibility) */
        .modern-input:focus-visible {
            outline: 2px solid #0d6efd;
            outline-offset: 2px;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Character Counter
        const messageInput = document.getElementById('message');
        const charCount = document.getElementById('charCount');

        if (messageInput && charCount) {
            messageInput.addEventListener('input', function() {
                const length = this.value.length;
                charCount.textContent = `${length} / 500`;

                // Color warning
                if (length > 400) {
                    charCount.classList.add('danger');
                    charCount.classList.remove('warning');
                } else if (length > 300) {
                    charCount.classList.add('warning');
                    charCount.classList.remove('danger');
                } else {
                    charCount.classList.remove('warning', 'danger');
                }
            });
        }

        // Form Submit Loading State
        const contactForm = document.getElementById('contactForm');
        const submitBtn = document.getElementById('submitBtn');

        if (contactForm && submitBtn) {
            contactForm.addEventListener('submit', function() {
                submitBtn.classList.add('loading');
                submitBtn.disabled = true;
            });
        }

        // Auto-hide success alert
        const successAlert = document.querySelector('.modern-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
                setTimeout(() => successAlert.remove(), 300);
            }, 5000);
        }
    </script>
@endpush
