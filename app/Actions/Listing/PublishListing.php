<?php

namespace App\Actions\Listing;

use App\Enums\ListingStatus;
use App\Models\Listing;
use Lorisleiva\Actions\Concerns\AsAction;

class PublishListing
{
    use AsAction;

    public function handle(Listing $listing): Listing
    {
        $listing->update([
            'status' => ListingStatus::PUBLISHED,
        ]);

        return $listing->refresh();
    }
}
