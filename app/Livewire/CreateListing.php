<?php

namespace App\Livewire;

use App\Actions\Listing\CreateListing as CreateListingAction;
use App\Actions\Listing\PublishListing;
use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\ListingCategory;
use App\Http\Requests\StoreListingRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.marketplace')]
class CreateListing extends Component
{
    public string $title = '';

    public string $description = '';

    public string $category = '';

    public string $price = '';

    public string $currency = '';

    public string $country = '';

    public string $city = '';

    public string $date_online = '';

    public string $date_offline = '';

    public function mount(): void
    {
        $this->currency = Currency::EUR->value;
        $this->country = Country::BE->value;
        $this->date_online = now()->toDateString();
        $this->date_offline = now()->addMonths(6)->toDateString();
    }

    public function save(): void
    {
        CreateListingAction::run(Auth::user()->seller, $this->validatedListingData());

        session()->flash('status', 'Listing saved as draft.');

        $this->redirect(route('listings.manage'), navigate: true);
    }

    public function saveAndPublish(): void
    {
        $validated = $this->validatedListingData();

        DB::transaction(function () use ($validated): void {
            $listing = CreateListingAction::run(Auth::user()->seller, $validated);

            $this->authorize('publish', $listing);

            PublishListing::run($listing);
        });

        session()->flash('status', 'Listing published.');

        $this->redirect(route('listings.manage'), navigate: true);
    }

    public function render()
    {
        return view('livewire.create-listing', [
            'categories' => ListingCategory::cases(),
            'countries' => Country::cases(),
            'currencies' => Currency::cases(),
            'seller' => Auth::user()->seller,
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validatedListingData(): array
    {
        return $this->validate((new StoreListingRequest)->rules());
    }
}
