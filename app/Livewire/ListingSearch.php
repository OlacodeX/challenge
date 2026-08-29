<?php

namespace App\Livewire;

use App\Actions\Listing\SearchListings;
use App\Enums\Country;
use App\Enums\ListingCategory;
use App\Enums\ListingSort;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.marketplace')]
class ListingSearch extends Component
{
    #[Url(as: 'q', history: true, except: '')]
    public string $title = '';

    #[Url(history: true, except: '')]
    public string $category = '';

    #[Url(history: true, except: '')]
    public string $country = '';

    #[Url(history: true, except: '')]
    public string $minPrice = '';

    #[Url(history: true, except: '')]
    public string $maxPrice = '';

    #[Url(history: true, except: 'newest')]
    public string $sort = 'newest';

    #[Url(as: 'page', history: true, except: 1)]
    public int $page = 1;

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->page = 1;
        }
    }

    public function clearFilters(): void
    {
        $this->title = '';
        $this->category = '';
        $this->country = '';
        $this->minPrice = '';
        $this->maxPrice = '';
        $this->sort = 'newest';
        $this->page = 1;
    }

    public function render()
    {
        return view('livewire.listing-search', [
            'listings' => SearchListings::run(
                title: $this->title,
                category: $this->category,
                country: $this->country,
                minPrice: $this->minPrice !== '' ? (float) $this->minPrice : null,
                maxPrice: $this->maxPrice !== '' ? (float) $this->maxPrice : null,
                sort: $this->sort,
                page: $this->page,
            ),
            'filterOptionCounts' => SearchListings::make()->filterOptionCounts(),
            'categories' => ListingCategory::cases(),
            'countries' => Country::cases(),
            'sortOptions' => ListingSort::cases(),
        ]);
    }
}
