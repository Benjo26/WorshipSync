<?php

use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LiveSetController;
use App\Http\Controllers\SongController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'welcome'])->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/auth/google/redirect', [GoogleController::class, 'redirect'])->name('google.redirect');
    Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('songs', SongController::class)->except('show');
    Route::get('/live-sets/{liveSet}/pdf', [LiveSetController::class, 'pdf'])->name('live-sets.pdf');
    Route::resource('live-sets', LiveSetController::class);
    Route::get('/songs/{song}/player', [SongController::class, 'player'])->name('songs.player');
    Route::post('/logout', [GoogleController::class, 'logout'])->name('logout');
});
