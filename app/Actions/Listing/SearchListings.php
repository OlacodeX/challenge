<?php

namespace App\Actions\Listing;

use App\Enums\Country;
use App\Enums\ListingCategory;
use App\Enums\ListingSort;
use App\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;
use Lorisleiva\Actions\Concerns\AsAction;

class SearchListings
{
    use AsAction;

    public const FILTER_OPTION_COUNTS_CACHE_KEY = 'listing-search-filter-option-counts';

    public function handle(
        string $title = '',
        string $category = '',
        string $country = '',
        ?float $minPrice = null,
        ?float $maxPrice = null,
        ?string $sort = null,
        int $page = 1,
    ): LengthAwarePaginator {

        $this->enforceSearchRateLimit();

        $query = Listing::query()
            ->published()
            ->withinPublicationWindow()
            ->with('seller:id,company_name')
            ->select([
                'id',
                'seller_id',
                'title',
                'slug',
                'category',
                'price',
                'currency',
                'country',
                'city',
                'created_at',
            ])
            ->when($title !== '', fn (Builder $query) => $query->whereIn('id', Listing::search($title)->keys()))
            ->when(ListingCategory::tryFrom($category), fn (Builder $query, ListingCategory $category) => $query->where('category', $category))
            ->when(Country::tryFrom($country), fn (Builder $query, Country $country) => $query->where('country', $country))
            ->when($minPrice, fn (Builder $query) => $query->where('price', '>=', $minPrice * 100))
            ->when($maxPrice, fn (Builder $query) => $query->where('price', '<=', $maxPrice * 100));

        ListingSort::fromRequest($sort)->applyToQuery($query);

        return $query->paginate(12, page: $page)->withQueryString();
    }

    public function filterOptionCounts(): array
    {
        return Cache::flexible(self::FILTER_OPTION_COUNTS_CACHE_KEY, [300, 600], function (): array {
            $base = Listing::query()->published()->withinPublicationWindow();

            $categories = (clone $base)
                ->selectRaw('category, count(*) as aggregate')
                ->groupBy('category')
                ->pluck('aggregate', 'category')
                ->all();

            $countries = (clone $base)
                ->selectRaw('country, count(*) as aggregate')
                ->groupBy('country')
                ->pluck('aggregate', 'country')
                ->all();

            return [
                'categories' => $categories,
                'countries' => $countries,
            ];
        });
    }

    private function enforceSearchRateLimit(): void
    {
        $limit = RateLimiter::limiter('search')(request());

        if (RateLimiter::tooManyAttempts($limit->key, $limit->maxAttempts)) {
            abort(429);
        }

        RateLimiter::hit($limit->key, $limit->decaySeconds);
    }
}
