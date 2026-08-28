<?php

use App\Livewire\ListingSearch;
use App\Livewire\ShowListing;
use Illuminate\Support\Facades\Route;

Route::livewire('/', ListingSearch::class)->name('home');

Route::livewire('/listings/{listing}', ShowListing::class)->name('listings.show');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
