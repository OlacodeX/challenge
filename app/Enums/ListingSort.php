<?php

namespace App\Enums;

use Illuminate\Database\Eloquent\Builder;

enum ListingSort: string
{
    case PRICE_ASC = 'price_asc';
    case PRICE_DESC = 'price_desc';
    case NEWEST = 'newest';
    case OLDEST = 'oldest';

    public function label(): string
    {
        return __('enums.listing_sort.'.$this->value);
    }

    public static function fromRequest(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::NEWEST;
    }

    public function applyToQuery(Builder $query): Builder
    {
        return match ($this) {
            self::PRICE_ASC => $query->orderBy('price'),
            self::PRICE_DESC => $query->orderByDesc('price'),
            self::NEWEST => $query->orderByDesc('created_at'),
            self::OLDEST => $query->orderBy('created_at'),
        };
    }
}
