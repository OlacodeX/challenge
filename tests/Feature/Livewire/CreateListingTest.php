<?php

use App\Enums\ListingStatus;
use App\Livewire\CreateListing;
use App\Models\Listing;
use App\Models\Seller;
use App\Models\User;
use Livewire\Livewire;

it('redirects guests to login', function () {
    $this->get(route('listings.create'))
        ->assertRedirect(route('login'));
});

it('forbids buyers without a seller profile', function () {
    $buyer = User::factory()->create();

    $this->actingAs($buyer)
        ->get(route('listings.create'))
        ->assertForbidden();
});

it('allows sellers to view the create form', function () {
    $seller = Seller::factory()->create();

    $this->actingAs($seller->user)
        ->get(route('listings.create'))
        ->assertOk()
        ->assertSeeLivewire(CreateListing::class)
        ->assertSee($seller->company_name);
});

it('prefills listing defaults on mount', function () {
    $seller = Seller::factory()->create();

    Livewire::actingAs($seller->user)
        ->test(CreateListing::class)
        ->assertSet('currency', 'eur')
        ->assertSet('country', 'be');
});

it('creates a draft listing for an authenticated seller', function () {
    $seller = Seller::factory()->create([
        'company_name' => 'Acme Logistics BV',
    ]);
    $user = $seller->user->fresh();

    Livewire::actingAs($user)
        ->test(CreateListing::class)
        ->set('title', 'Warehouse Forklift Fleet')
        ->set('description', 'Three electric forklifts in excellent condition.')
        ->set('category', 'vehicles_fleet')
        ->set('price', '125000')
        ->set('currency', 'eur')
        ->set('country', 'be')
        ->set('city', 'Antwerp')
        ->set('date_online', now()->toDateString())
        ->set('date_offline', now()->addMonths(3)->toDateString())
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard'));

    $listing = Listing::query()->where('title', 'Warehouse Forklift Fleet')->first();

    expect($listing)->not->toBeNull()
        ->and($listing->status)->toBe(ListingStatus::DRAFT)
        ->and($listing->seller_id)->toBe($seller->id)
        ->and($listing->slug)->toBe('warehouse-forklift-fleet');

    $seller->refresh();

    expect($seller->company_name)->toBe('Acme Logistics BV');
});

it('validates required listing fields', function () {
    $seller = Seller::factory()->create();

    Livewire::actingAs($seller->user)
        ->test(CreateListing::class)
        ->set('title', '')
        ->call('save')
        ->assertHasErrors(['title', 'description', 'category', 'price', 'city']);
});

it('generates a unique slug when the title collides', function () {
    $seller = Seller::factory()->create();
    Listing::factory()->draft()->create([
        'seller_id' => $seller->id,
        'title' => 'Existing Listing',
        'slug' => 'existing-listing',
    ]);

    Livewire::actingAs($seller->user)
        ->test(CreateListing::class)
        ->set('title', 'Existing Listing')
        ->set('description', 'Description text.')
        ->set('category', 'commercial_property')
        ->set('price', '500000')
        ->set('currency', 'eur')
        ->set('country', 'fr')
        ->set('city', 'Paris')
        ->set('date_online', now()->toDateString())
        ->set('date_offline', now()->addMonths(6)->toDateString())
        ->call('save')
        ->assertHasNoErrors();

    expect(Listing::query()->where('title', 'Existing Listing')->count())->toBe(2)
        ->and(Listing::query()->where('title', 'Existing Listing')->pluck('slug')->unique()->count())->toBe(2);
});
