<?php

namespace App\Enums;

enum Country: string
{
    case FR = 'fr';
    case BE = 'be';
    case LU = 'lu';

    public function label(): string
    {
        return __('enums.country.'.$this->value);
    }

    public function code(): string
    {
        return strtoupper($this->value);
    }
}
