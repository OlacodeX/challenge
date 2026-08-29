<?php

use App\Enums\ListingSort;

it('resolves valid sort values from the request', function () {
    expect(ListingSort::fromRequest('price_asc'))->toBe(ListingSort::PRICE_ASC);
    expect(ListingSort::fromRequest('newest'))->toBe(ListingSort::NEWEST);
});

it('falls back to newest for invalid sort values', function () {
    expect(ListingSort::fromRequest('invalid'))->toBe(ListingSort::NEWEST);
    expect(ListingSort::fromRequest(null))->toBe(ListingSort::NEWEST);
});
