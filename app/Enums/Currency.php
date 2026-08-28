<?php

namespace App\Enums;

enum Currency: string
{
    case EUR = 'eur';
    case USD = 'usd';

    public function label(): string
    {
        return match ($this) {
            self::EUR => 'EUR',
            self::USD => 'USD',
        };
    }
}
