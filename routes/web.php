<?php

use App\Livewire\ListingSearch;
use Illuminate\Support\Facades\Route;

Route::livewire('/', ListingSearch::class)->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
