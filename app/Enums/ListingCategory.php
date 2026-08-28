<?php

namespace App\Enums;

enum ListingCategory: string
{
    case MACHINERY_EQUIPMENT = 'machinery_equipment';
    case VEHICLES_FLEET = 'vehicles_fleet';
    case COMMERCIAL_PROPERTY = 'commercial_property';
    case INTANGIBLE_ASSETS = 'intangible_assets';

    public function label(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'Machinery & Equipment',
            self::VEHICLES_FLEET => 'Vehicles & Fleet',
            self::COMMERCIAL_PROPERTY => 'Commercial Property',
            self::INTANGIBLE_ASSETS => 'Intangible Assets',
        };
    }
}
