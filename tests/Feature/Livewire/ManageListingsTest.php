<?php

use App\Enums\ListingStatus;
use App\Livewire\ManageListings;
use App\Models\Listing;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Livewire\Livewire;

it('redirects guests to login', function () {
    $this->get(route('listings.manage'))
        ->assertRedirect(route('login'));
});

it('forbids buyers without a seller profile', function () {
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->get(route('listings.manage'))
        ->assertForbidden();
});

it('shows only the authenticated sellers listings', function () {
    $seller = Seller::factory()->verified()->create();
    $otherSeller = Seller::factory()->verified()->create();

    Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
        'title' => 'My Draft Listing',
    ]);

    Listing::factory()->draft()->create([
        'seller_id' => $otherSeller->id,
        'title' => 'Someone Elses Listing',
    ]);

    $this->actingAs($seller->user)
        ->get(route('listings.manage'))
        ->assertOk()
        ->assertSee('My Draft Listing')
        ->assertDontSee('Someone Elses Listing');
});

it('filters listings by status in the url', function () {
    $seller = Seller::factory()->verified()->create();

    Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
        'title' => 'Draft Only Listing',
    ]);

    Listing::factory()->publishedInWindow()->create([
        'seller_id' => $seller->id,
        'title' => 'Published Only Listing',
    ]);

    Livewire::actingAs($seller->user)
        ->withQueryParams(['status' => ListingStatus::DRAFT->value])
        ->test(ManageListings::class)
        ->assertSee('Draft Only Listing')
        ->assertDontSee('Published Only Listing');
});

it('allows a verified seller to publish a draft listing', function () {
    $seller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
    ]);

    Livewire::actingAs($seller->user)
        ->test(ManageListings::class)
        ->call('publish', $listing->id)
        ->assertHasNoErrors();

    expect($listing->fresh()->status)->toBe(ListingStatus::PUBLISHED);
});

it('forbids unverified sellers from publishing', function () {
    $seller = Seller::factory()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
    ]);

    Livewire::actingAs($seller->user)
        ->test(ManageListings::class)
        ->call('publish', $listing->id)
        ->assertForbidden();

    expect($listing->fresh()->status)->toBe(ListingStatus::DRAFT);
});

it('forbids publishing another sellers listing', function () {
    $owner = Seller::factory()->verified()->create();
    $otherSeller = Seller::factory()->verified()->create();
    $listing = Listing::factory()->draft()->create([
        'seller_id' => $owner->id,
    ]);

    expect(fn () => Livewire::actingAs($otherSeller->user)
        ->test(ManageListings::class)
        ->call('publish', $listing->id))
        ->toThrow(ModelNotFoundException::class);
});
