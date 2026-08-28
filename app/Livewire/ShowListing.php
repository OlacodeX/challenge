<?php

namespace App\Livewire;

use App\Actions\Listing\RevealContact;
use App\Models\Listing;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.marketplace')]
class ShowListing extends Component
{
    public Listing $listing;

    public bool $contactRevealed = false;

    public ?string $revealedEmail = null;

    public ?string $revealedPhone = null;

    public function mount(Listing $listing): void
    {
        $this->listing = $listing->loadMissing('seller:id,company_name,user_id');
    }

    public function revealContact(): void
    {
        $this->authorize('revealContact', $this->listing);

        $contact = RevealContact::run($this->listing, Auth::user());

        $this->revealedEmail = $contact['email'];
        $this->revealedPhone = $contact['phone'];
        $this->contactRevealed = true;
    }

    public function render()
    {
        return view('livewire.show-listing');
    }
}
