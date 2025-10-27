<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\BeneficiaryController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonaturController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\AdminDonationController;

// ========== Settings Controllers ==========
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\BankAccountController;


// Public routes
Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/program/{id}', [PublicController::class, 'programDetail'])->name('program.detail');
Route::get('/laporan', [PublicController::class, 'reports'])->name('public.reports');
Route::get('/tentang', [PublicController::class, 'about'])->name('about');
Route::get('/kontak', [PublicController::class, 'contact'])->name('contact');
Route::post('/kontak', [PublicController::class, 'submitContact'])->name('contact.submit');

// Authentication routes (from Laravel Breeze)
require __DIR__ . '/auth.php';

// Protected routes
Route::middleware(['auth'])->group(function () {

    // Breeze login
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    //User Profile
    Route::get('/profile/details', [UserProfileController::class, 'edit'])->name('profile.details');
    Route::put('/profile/details', [UserProfileController::class, 'update'])->name('profile.details.update');

    // Sub-group: wajib profil lengkap
    Route::middleware(['profile.completed'])->group(function () {
        // Dashboard Admin
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('role:superadmin,admin');

        // Dashboard Donatur
        Route::get('/donatur/dashboard', [DonaturController::class, 'dashboard'])->name('donatur.dashboard')->middleware('role:donatur');

        // Donations
        Route::resource('donations', DonationController::class);
        Route::post('/donations/{donation}/verify', [DonationController::class, 'verify'])->name('donations.verify');
    });

    // Notifications
    Route::resource('notifications', NotificationController::class)->only(['index', 'destroy']);
    Route::post('/notifications/{notification}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notifications/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.markAllAsRead');
    Route::post('/notifications/delete-all-read', [NotificationController::class, 'deleteAllRead'])->name('notifications.deleteAllRead');


    // Programs
    Route::resource('programs', ProgramController::class);

    // Beneficiaries
    Route::resource('beneficiaries', BeneficiaryController::class);

    // Distributions
    Route::resource('distributions', DistributionController::class);

    // Reports
    Route::prefix('laporan')->name('reports.')->group(function () {
        Route::get('/donasi', [ReportController::class, 'donations'])->name('donations');
        Route::get('/penyaluran', [ReportController::class, 'distributions'])->name('distributions');
        Route::get('/program', [ReportController::class, 'programs'])->name('programs');

        Route::get('/donasi/pdf', [ReportController::class, 'exportDonationsPDF'])->name('donations.pdf');
        Route::get('/donasi/excel', [ReportController::class, 'exportDonationsExcel'])->name('donations.excel');
        Route::get('/penyaluran/pdf', [ReportController::class, 'exportDistributionsPDF'])->name('distributions.pdf');
        Route::get('/penyaluran/excel', [ReportController::class, 'exportDistributionsExcel'])->name('distributions.excel');
        Route::get('/program/pdf', [ReportController::class, 'exportProgramsPDF'])->name('programs.pdf');
        Route::get('/program/excel', [ReportController::class, 'exportProgramsExcel'])->name('programs.excel');
    });

    // Users (Superadmin or admin only)
    Route::middleware(['role:superadmin,admin'])->group(function () {
        Route::resource('users', UserController::class);
        Route::put('users/{user}/profile', [UserController::class, 'updateProfile'])->name('users.profile.update');
        Route::put('/admin/users/{user}/profile', [UserProfileController::class, 'updateByAdmin'])->name('users.profile.update');
    });
    // Roles (Superadmin only)
    Route::middleware(['role:superadmin'])->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Donation Create By Admin (Superadmin or Admin only)
    Route::middleware('role:superadmin,admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/donations/create', [AdminDonationController::class, 'create'])->name('donations.create');
        Route::post('/donations/store', [AdminDonationController::class, 'store'])->name('donations.store');
    });

    // AJAX search Donatur
    Route::get('/search-donatur', [DonaturController::class, 'search'])->name('donatur.search');

    // ========== NEW: Settings Routes (Superadmin or Admin only) ==========
    Route::middleware(['role:superadmin,admin'])->prefix('admin')->name('admin.')->group(function () {

        // Settings Main Page & Updates
        Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('/settings/general', [SettingController::class, 'updateGeneral'])->name('settings.general.update');
        Route::post('/settings/contact', [SettingController::class, 'updateContact'])->name('settings.contact.update');

        // Team Management
        Route::prefix('settings/teams')->name('settings.teams.')->group(function () {
            Route::get('/', [TeamController::class, 'index'])->name('index');
            Route::post('/', [TeamController::class, 'store'])->name('store');
            Route::get('/{team}', [TeamController::class, 'show'])->name('show');
            Route::post('/{team}', [TeamController::class, 'update'])->name('update'); // Using POST for FormData
            Route::delete('/{team}', [TeamController::class, 'destroy'])->name('destroy');
            Route::post('/{team}/move', [TeamController::class, 'move'])->name('move');
        });

        // Testimonials
        Route::prefix('settings/testimonials')->name('settings.testimonials.')->group(function () {
            Route::get('/', [TestimonialController::class, 'index'])->name('index');
            Route::post('/', [TestimonialController::class, 'store'])->name('store');
            Route::get('/{testimonial}', [TestimonialController::class, 'show'])->name('show');
            Route::post('/{testimonial}', [TestimonialController::class, 'update'])->name('update'); // Using POST for FormData
            Route::delete('/{testimonial}', [TestimonialController::class, 'destroy'])->name('destroy');
            Route::post('/{testimonial}/move', [TestimonialController::class, 'move'])->name('move');
        });

        // Bank Accounts
        Route::prefix('settings/bank-accounts')->name('settings.bank-accounts.')->group(function () {
            Route::get('/', [BankAccountController::class, 'index'])->name('index');
            Route::post('/', [BankAccountController::class, 'store'])->name('store');
            Route::get('/{bankAccount}', [BankAccountController::class, 'show'])->name('show');
            Route::post('/{bankAccount}', [BankAccountController::class, 'update'])->name('update'); // Using POST for FormData
            Route::delete('/{bankAccount}', [BankAccountController::class, 'destroy'])->name('destroy');
        });
    });
    // =====================================================================
});
