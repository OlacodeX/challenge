<?php

namespace Database\Seeders;

use App\Models\Listing;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class ListingSeeder extends Seeder
{
    use WithoutModelEvents;

    private const DEMO_SELLER_EMAILS = [
        'verified@kinkoza.test',
        'unverified@kinkoza.test',
    ];

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Listing::truncate();

        Schema::enableForeignKeyConstraints();

        $verifiedSeller = $this->sellerForUser('verified@kinkoza.test');
        $unverifiedSeller = $this->sellerForUser('unverified@kinkoza.test');

        Listing::factory()->count(45)->publishedInWindow()->create(['seller_id' => $verifiedSeller->id]);
        Listing::factory()->count(10)->publishedOutOfWindow()->create(['seller_id' => $verifiedSeller->id]);
        Listing::factory()->count(8)->draft()->create(['seller_id' => $verifiedSeller->id]);
        Listing::factory()->count(5)->expired()->create(['seller_id' => $verifiedSeller->id]);
        Listing::factory()->count(4)->pendingReview()->create(['seller_id' => $verifiedSeller->id]);

        Listing::factory()->count(8)->draft()->create(['seller_id' => $unverifiedSeller->id]);
        Listing::factory()->count(5)->pendingReview()->create(['seller_id' => $unverifiedSeller->id]);

        Seller::query()
            ->whereHas('user', fn ($query) => $query->whereNotIn('email', [
                ...self::DEMO_SELLER_EMAILS,
                'buyer@kinkoza.test',
            ]))
            ->each(fn (Seller $seller) => Listing::factory()
                ->count(8)
                ->publishedInWindow()
                ->create(['seller_id' => $seller->id]));
    }

    private function sellerForUser(string $email): Seller
    {
        $user = User::query()->where('email', $email)->firstOrFail();

        return Seller::query()->where('user_id', $user->id)->firstOrFail();
    }
}
