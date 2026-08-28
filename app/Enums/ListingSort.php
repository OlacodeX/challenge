<?php

namespace App\Enums;

enum ListingSort: string
{
    case PRICE_ASC = 'price_asc';
    case PRICE_DESC = 'price_desc';
    case NEWEST = 'newest';
    case OLDEST = 'oldest';

    public function label(): string
    {
        return match ($this) {
            self::PRICE_ASC => 'Price: Low to High',
            self::PRICE_DESC => 'Price: High to Low',
            self::NEWEST => 'Newest',
            self::OLDEST => 'Oldest',
        };
    }

    public static function fromRequest(?string $value): self
    {
        return self::tryFrom((string) $value) ?? self::NEWEST;
    }
}
