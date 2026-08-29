<?php

namespace App\Livewire;

use App\Actions\Listing\PublishListing;
use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\Seller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.marketplace')]
class ManageListings extends Component
{
    #[Url(history: true)]
    public string $status = '';

    #[Url(as: 'page', history: true, except: 1)]
    public int $page = 1;

    public function updated(string $property): void
    {
        if ($property !== 'page') {
            $this->page = 1;
        }
    }

    public function publish(int $listingId): void
    {
        $listing = Listing::query()
            ->ownedBy($this->seller())
            ->findOrFail($listingId);

        $this->authorize('publish', $listing);

        PublishListing::run($listing);

        session()->flash('status', __('marketplace.listing_published'));
    }

    public function render()
    {
        $seller = $this->seller();

        return view('livewire.manage-listings', [
            'listings' => $this->listingsFor($seller),
            'seller' => $seller,
            'statuses' => ListingStatus::cases(),
        ]);
    }

    private function seller(): ?Seller
    {
        return Auth::user()?->seller;
    }

    private function listingsFor(Seller $seller): LengthAwarePaginator
    {
        return Listing::query()
            ->ownedBy($seller)
            ->when(ListingStatus::tryFrom($this->status), fn ($query, ListingStatus $status) => $query->where('status', $status))
            ->orderByDesc('created_at')
            ->paginate(12, page: $this->page);
    }
}
