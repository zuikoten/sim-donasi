<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\DonaturSearchController;

Route::get('/search-donatur', [DonaturSearchController::class, 'index'])->name('search.donatur');

