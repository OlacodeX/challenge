<?php

namespace App\Enums;

enum Country: string
{
    case FR = 'fr';
    case BE = 'be';
    case LU = 'lu';

    public function label(): string
    {
        return match ($this) {
            self::FR => 'France',
            self::BE => 'Belgium',
            self::LU => 'Luxembourg',
        };
    }

    public function code(): string
    {
        return strtoupper($this->value);
    }
}
