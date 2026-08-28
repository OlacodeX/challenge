<?php

namespace App\Actions\Listing;

use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\Seller;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateListing
{
    use AsAction;

    /**
     * @param  array{
     *     title: string,
     *     description: string,
     *     category: string,
     *     price: float|int|string,
     *     currency: string,
     *     country: string,
     *     city: string,
     *     date_online: string,
     *     date_offline: string,
     * }  $validated
     */
    public function handle(Seller $seller, array $validated): Listing
    {
        return Listing::query()->create([
            'seller_id' => $seller->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category' => $validated['category'],
            'price' => $validated['price'],
            'currency' => $validated['currency'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'date_online' => $validated['date_online'],
            'date_offline' => $validated['date_offline'],
            'status' => ListingStatus::DRAFT,
        ]);
    }
}
