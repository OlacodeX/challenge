<?php

use App\Actions\Listing\PublishListing;
use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\Seller;
use Illuminate\Support\Facades\Gate;

it('allows a verified seller to publish a draft listing', function () {
    $seller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
    ]);

    $this->actingAs($seller->user);

    Gate::authorize('publish', $listing);

    PublishListing::run($listing);

    expect($listing->fresh()->status)->toBe(ListingStatus::PUBLISHED);
});

it('forbids unverified sellers from publishing', function () {
    $seller = Seller::factory()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
    ]);

    expect($seller->user->can('publish', $listing))->toBeFalse();
});

it('forbids publishing a listing that is not a draft', function () {
    $seller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->publishedInWindow()->create([
        'seller_id' => $seller->id,
    ]);

    expect($seller->user->can('publish', $listing))->toBeFalse();
});

it('forbids publishing another sellers listing', function () {
    $owner = Seller::factory()->verified()->create();
    $otherSeller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $owner->id,
    ]);

    expect($otherSeller->user->can('publish', $listing))->toBeFalse();
});

it('includes a published listing in search results', function () {
    $seller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
        'title' => 'Unique Publish Search Title',
        'date_online' => now()->toDateString(),
        'date_offline' => now()->addMonths(3)->toDateString(),
    ]);

    $this->actingAs($seller->user);

    Gate::authorize('publish', $listing);

    PublishListing::run($listing);

    $this->get(route('home', ['q' => 'Unique Publish Search Title']))
        ->assertOk()
        ->assertSee('Unique Publish Search Title');
});
