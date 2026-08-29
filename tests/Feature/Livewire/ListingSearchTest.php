<?php

use App\Enums\ListingCategory;
use App\Enums\ListingSort;
use App\Livewire\ListingSearch;
use App\Models\Listing;
use Livewire\Livewire;

it('shows only published listings within the publication window', function () {
    Listing::factory()->publishedInWindow()->create(['title' => 'Visible Listing']);
    Listing::factory()->draft()->create(['title' => 'Draft Listing']);
    Listing::factory()->publishedOutOfWindow()->create(['title' => 'Expired Listing']);

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('Visible Listing')
        ->assertDontSee('Draft Listing')
        ->assertDontSee('Expired Listing');
});

it('filters listings by title from the url', function () {
    Listing::factory()->publishedInWindow()->create(['title' => 'CNC Milling Machine']);
    Listing::factory()->publishedInWindow()->create(['title' => 'Office Building']);

    Livewire::withQueryParams(['q' => 'CNC'])
        ->test(ListingSearch::class)
        ->assertSee('CNC Milling Machine')
        ->assertDontSee('Office Building');
});

it('filters listings by category from the url', function () {
    Listing::factory()->publishedInWindow()->create([
        'title' => 'Machinery Listing',
        'category' => ListingCategory::MACHINERY_EQUIPMENT,
    ]);

    Listing::factory()->publishedInWindow()->create([
        'title' => 'Vehicle Listing',
        'category' => ListingCategory::VEHICLES_FLEET,
    ]);

    Livewire::withQueryParams(['category' => ListingCategory::MACHINERY_EQUIPMENT->value])
        ->test(ListingSearch::class)
        ->assertSee('Machinery Listing')
        ->assertDontSee('Vehicle Listing');
});

it('resets pagination when a filter changes', function () {
    Livewire::test(ListingSearch::class)
        ->set('page', 2)
        ->set('title', 'test')
        ->assertSet('page', 1);
});

it('clears all filters back to defaults', function () {
    Livewire::test(ListingSearch::class)
        ->set('title', 'CNC')
        ->set('category', ListingCategory::MACHINERY_EQUIPMENT->value)
        ->set('sort', ListingSort::PRICE_ASC->value)
        ->call('clearFilters')
        ->assertSet('title', '')
        ->assertSet('category', '')
        ->assertSet('sort', 'newest');
});

it('ignores invalid sort values and still returns results', function () {
    Listing::factory()->publishedInWindow()->create(['title' => 'Still Searchable Listing']);

    Livewire::withQueryParams(['sort' => 'DROP TABLE'])
        ->test(ListingSearch::class)
        ->assertOk()
        ->assertSee('Still Searchable Listing');
});
