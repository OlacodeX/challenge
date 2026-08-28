<?php

namespace App\Models;

use App\Actions\Listing\SearchListings;
use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\ListingCategory;
use App\Enums\ListingStatus;
use Cviebrock\EloquentSluggable\Sluggable;
use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @property Carbon $date_online
 * @property Carbon $date_offline
 */
class Listing extends Model
{
    /** @use HasFactory<ListingFactory> */
    use HasFactory, Sluggable;

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget(SearchListings::FILTER_OPTION_COUNTS_CACHE_KEY));
        static::deleted(fn () => Cache::forget(SearchListings::FILTER_OPTION_COUNTS_CACHE_KEY));
    }

    protected $fillable = [
        'seller_id',
        'title',
        'slug',
        'description',
        'category',
        'price',
        'currency',
        'country',
        'city',
        'date_online',
        'date_offline',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'category' => ListingCategory::class,
            'currency' => Currency::class,
            'country' => Country::class,
            'status' => ListingStatus::class,
            'date_online' => 'date',
            'date_offline' => 'date',
        ];
    }

    protected function price(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => $value / 100,
            set: fn ($value) => $value * 100,
        );
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title',
                'unique' => true,
                'onUpdate' => false,
            ],
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function audits(): MorphMany
    {
        return $this->morphMany(Audit::class, 'auditable');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }

    public function isWithinPublicationWindow(): bool
    {
        $today = now()->toDateString();

        return $this->date_online->toDateString() <= $today
            && $this->date_offline->toDateString() >= $today;
    }

    #[Scope]
    protected function published(Builder $query): void
    {
        $query->where('status', ListingStatus::PUBLISHED);
    }

    #[Scope]
    protected function withinPublicationWindow(Builder $query): void
    {
        $today = now()->toDateString();

        $query->whereDate('date_online', '<=', $today)
            ->whereDate('date_offline', '>=', $today);
    }

    #[Scope]
    protected function ownedBy(Builder $query, Seller $seller): void
    {
        $query->where('seller_id', $seller->id);
    }
}
