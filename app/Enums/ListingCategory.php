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
        return __('enums.listing_category.'.$this->value);
    }

    public function accentGradient(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'from-slate-500 to-slate-700',
            self::VEHICLES_FLEET => 'from-blue-500 to-blue-700',
            self::COMMERCIAL_PROPERTY => 'from-emerald-500 to-emerald-700',
            self::INTANGIBLE_ASSETS => 'from-violet-500 to-violet-700',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'bg-slate-50 text-slate-700 ring-slate-600/10',
            self::VEHICLES_FLEET => 'bg-blue-50 text-blue-700 ring-blue-600/10',
            self::COMMERCIAL_PROPERTY => 'bg-emerald-50 text-emerald-700 ring-emerald-600/10',
            self::INTANGIBLE_ASSETS => 'bg-violet-50 text-violet-700 ring-violet-600/10',
        };
    }

    public function linkClasses(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'text-slate-600 hover:text-slate-800',
            self::VEHICLES_FLEET => 'text-blue-600 hover:text-blue-800',
            self::COMMERCIAL_PROPERTY => 'text-emerald-600 hover:text-emerald-800',
            self::INTANGIBLE_ASSETS => 'text-violet-600 hover:text-violet-800',
        };
    }

    public function primaryButtonClasses(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'bg-slate-600 hover:bg-slate-500',
            self::VEHICLES_FLEET => 'bg-blue-600 hover:bg-blue-500',
            self::COMMERCIAL_PROPERTY => 'bg-emerald-600 hover:bg-emerald-500',
            self::INTANGIBLE_ASSETS => 'bg-violet-600 hover:bg-violet-500',
        };
    }

    public function cardBorderHoverClass(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'hover:border-slate-200',
            self::VEHICLES_FLEET => 'hover:border-blue-200',
            self::COMMERCIAL_PROPERTY => 'hover:border-emerald-200',
            self::INTANGIBLE_ASSETS => 'hover:border-violet-200',
        };
    }

    public function cardTitleHoverClass(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'group-hover:text-slate-600',
            self::VEHICLES_FLEET => 'group-hover:text-blue-600',
            self::COMMERCIAL_PROPERTY => 'group-hover:text-emerald-600',
            self::INTANGIBLE_ASSETS => 'group-hover:text-violet-600',
        };
    }

    public function cardActionButtonHoverClass(): string
    {
        return match ($this) {
            self::MACHINERY_EQUIPMENT => 'group-hover:bg-slate-50 group-hover:text-slate-700 group-hover:ring-slate-200',
            self::VEHICLES_FLEET => 'group-hover:bg-blue-50 group-hover:text-blue-700 group-hover:ring-blue-200',
            self::COMMERCIAL_PROPERTY => 'group-hover:bg-emerald-50 group-hover:text-emerald-700 group-hover:ring-emerald-200',
            self::INTANGIBLE_ASSETS => 'group-hover:bg-violet-50 group-hover:text-violet-700 group-hover:ring-violet-200',
        };
    }
}
