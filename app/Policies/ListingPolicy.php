<?php

namespace App\Policies;

use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\Seller;
use App\Models\User;

class ListingPolicy
{
    public function view(?User $user, Listing $listing): bool
    {
        return $listing->status === ListingStatus::PUBLISHED && $listing->isWithinPublicationWindow();
    }

    public function create(User $user): bool
    {
        return $user->isSeller();
    }

    public function update(User $user, Listing $listing): bool
    {
        return $this->ownsListing($user, $listing);
    }

    public function delete(User $user, Listing $listing): bool
    {
        return $this->ownsListing($user, $listing);
    }

    public function publish(User $user, Listing $listing): bool
    {
        $seller = $user->seller;

        return $this->ownsListing($user, $listing) && $seller instanceof Seller && $seller->isVerified();
    }

    public function revealContact(User $user, Listing $listing): bool
    {
        $listing->loadMissing('seller');

        return $this->view($user, $listing) && $listing->seller->user_id !== $user->id;
    }

    private function ownsListing(User $user, Listing $listing): bool
    {
        $listing->loadMissing('seller');

        return $listing->seller->user_id === $user->id;
    }
}
