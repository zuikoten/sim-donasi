@extends('layouts.guest')

@section('title', 'Kontak Kami')

@section('content')
    <!-- Hero Section -->
    <section class="bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-12 text-center">
                    <h1 class="display-4 fw-bold">Hubungi Kami</h1>
                    <p class="lead">Ada pertanyaan, saran, atau ingin berkolaborasi? Kami siap mendengar dari Anda.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact Form & Info Section -->
    <section class="py-5">
        <div class="container">
            <div class="row">
                <!-- Contact Form -->
                <div class="col-lg-8 mb-4">
                    <div class="card shadow-sm">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-4">Kirim Pesan</h5>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('contact.submit') }}">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label">Nama Lengkap</label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror"
                                            id="email" name="email" value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="subject" class="form-label">Subjek</label>
                                        <input type="text" class="form-control @error('subject') is-invalid @enderror"
                                            id="subject" name="subject" value="{{ old('subject') }}" required>
                                        @error('subject')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12">
                                        <label for="message" class="form-label">Pesan</label>
                                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                                            required>{{ old('message') }}</textarea>
                                        @error('message')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-send"></i> Kirim Pesan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="col-lg-4">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body">
                            <h5 class="card-title">Informasi Kontak</h5>
                            <div class="mb-3">
                                <h6><i class="bi bi-geo-alt text-primary"></i> Alamat</h6>
                                <p>{{ $settings['office_address'] ?? 'Alamat belum diatur' }}
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="bi bi-telephone text-primary"></i> Telepon</h6>
                                <p>@php
                                    $phoneSetting = App\Models\Setting::where('key', 'office_phone')->first();
                                @endphp
                                    <a href="tel:+{{ $settings['office_phone'] }}" class="text-decoration-none">
                                        {{ $phoneSetting ? $phoneSetting->formatted_office_phone : $settings['office_phone'] ?? 'Telepon belum diatur' }}
                                    </a>
                                </p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="bi bi-envelope text-primary"></i> Email</h6>
                                <p><a href="mailto:{{ $settings['office_email'] }}" class="text-decoration-none">
                                        {{ $settings['office_email'] ?? 'Email belum diatur' }}
                                    </a></p>
                            </div>
                            <div class="mb-3">
                                <h6><i class="bi bi-clock text-primary"></i> Jam Operasional</h6>
                                <x-office-hours-list :office-hours="$officeHours" />

                            </div>
                        </div>
                    </div>

                    <!-- Social Media -->
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title">Ikuti Kami</h5>
                            <p>Ikuti media sosial kami untuk mendapatkan update terbaru.</p>
                            <div class="d-flex gap-2">
                                <a href="#" class="btn btn-outline-primary"><i class="bi bi-facebook"></i></a>
                                <a href="#" class="btn btn-outline-info"><i class="bi bi-twitter"></i></a>
                                <a href="#" class="btn btn-outline-danger"><i class="bi bi-instagram"></i></a>
                                <a href="#" class="btn btn-outline-success"><i class="bi bi-whatsapp"></i></a>
                            </div>
                        </div>
                    </div>
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
