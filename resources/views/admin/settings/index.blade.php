@extends('layouts.app')

@section('title', 'Settings - Sistem Informasi Donatur')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0"><i class="bi bi-gear me-2"></i>Settings</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <!-- Nav Tabs -->
            <ul class="nav nav-tabs nav-fill mb-4" id="settingsTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                        type="button" role="tab">
                        <i class="bi bi-gear me-2"></i>General
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact" type="button"
                        role="tab">
                        <i class="bi bi-envelope me-2"></i>Contact Info
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="team-tab" data-bs-toggle="tab" data-bs-target="#team" type="button"
                        role="tab">
                        <i class="bi bi-people me-2"></i>Team
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="testimonial-tab" data-bs-toggle="tab" data-bs-target="#testimonial"
                        type="button" role="tab">
                        <i class="bi bi-chat-quote me-2"></i>Testimonials
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="bank-tab" data-bs-toggle="tab" data-bs-target="#bank" type="button"
                        role="tab">
                        <i class="bi bi-bank me-2"></i>Bank Accounts
                    </button>
                </li>
            </ul>

            <!-- Tab Content -->
            <div class="tab-content" id="settingsTabContent">
                <!-- Tab 1: General Settings -->
                <div class="tab-pane fade show active" id="general" role="tabpanel">
                    @include('admin.settings._general')
                </div>

                <!-- Tab 2: Contact Information -->
                <div class="tab-pane fade" id="contact" role="tabpanel">
                    @include('admin.settings._contact')
                </div>

                <!-- Tab 3: Team Management -->
                <div class="tab-pane fade" id="team" role="tabpanel">
                    @include('admin.settings._team')
                </div>

                <!-- Tab 4: Testimonials -->
                <div class="tab-pane fade" id="testimonial" role="tabpanel">
                    @include('admin.settings._testimonial')
                </div>

                <!-- Tab 5: Bank Accounts -->
                <div class="tab-pane fade" id="bank" role="tabpanel">
                    @include('admin.settings._bank')
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .nav-tabs .nav-link {
            color: #6c757d;
            border: none;
            border-bottom: 3px solid transparent;
        }

        .nav-tabs .nav-link:hover {
            border-color: #dee2e6;
            color: #495057;
        }

        .nav-tabs .nav-link.active {
            color: #0d6efd;
            border-color: #0d6efd;
            background-color: transparent;
        }

        .image-preview {
            max-width: 200px;
            max-height: 200px;
            object-fit: contain;
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.5rem;
        }
    </style>
@endpush
