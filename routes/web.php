<?php

use App\Livewire\CreateListing;
use App\Livewire\ListingSearch;
use App\Livewire\ShowListing;
use App\Models\Listing;
use Illuminate\Support\Facades\Route;

Route::livewire('/', ListingSearch::class)->name('home');

Route::livewire('/listings/create', CreateListing::class)
    ->middleware(['auth', 'can:create,'.Listing::class])
    ->name('listings.create');

Route::livewire('/listings/{listing}', ShowListing::class)
    ->middleware('can:view,listing')
    ->name('listings.show');

Route::view('dashboard', 'dashboard')->middleware(['auth', 'verified'])->name('dashboard');

Route::view('profile', 'profile')->middleware(['auth'])->name('profile');

require __DIR__.'/auth.php';
