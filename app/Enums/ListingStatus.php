<?php

namespace App\Enums;

enum ListingStatus: string
{
    case DRAFT = 'draft';
    case PENDING_REVIEW = 'pending_review';
    case PUBLISHED = 'published';
    case EXPIRED = 'expired';

    public function label(): string
    {
        return __('enums.listing_status.'.$this->value);
    }
}
