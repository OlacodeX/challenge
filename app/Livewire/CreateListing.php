<?php

namespace App\Livewire;

use App\Actions\Listing\CreateListing as CreateListingAction;
use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\ListingCategory;
use App\Http\Requests\StoreListingRequest;
use Illuminate\Support\Facades\Auth;
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
        $validated = $this->validate((new StoreListingRequest)->rules());

        CreateListingAction::run(Auth::user()->seller, $validated);

        session()->flash('status', 'Listing saved and will be reviewed by our team.');

        $this->redirect(route('dashboard'), navigate: true);
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
}
