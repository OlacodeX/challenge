<?php

use App\Actions\Listing\RevealContact;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\HttpException;

beforeEach(function () {
    Queue::fake();
});

it('returns 429 when the contact reveal rate limit is exceeded', function () {
    $listing = Listing::factory()->publishedInWindow()->create();
    $buyer = User::factory()->create();

    $this->actingAs($buyer);

    $limit = RateLimiter::limiter('contact-reveal')(request());
    RateLimiter::clear($limit->key);

    for ($attempt = 0; $attempt < 5; $attempt++) {
        RevealContact::run($listing, $buyer);
    }

    expect(fn () => RevealContact::run($listing, $buyer))
        ->toThrow(function (HttpException $exception) {
            expect($exception->getStatusCode())->toBe(429);
        });
});
