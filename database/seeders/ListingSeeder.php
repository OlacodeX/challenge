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

        Listing::factory()->count(15)->publishedInWindow()->create(['seller_id' => $unverifiedSeller->id]);
        Listing::factory()->count(8)->draft()->create(['seller_id' => $unverifiedSeller->id]);
        Listing::factory()->count(5)->pendingReview()->create(['seller_id' => $unverifiedSeller->id]);
    }

    private function sellerForUser(string $email): Seller
    {
        $user = User::query()->where('email', $email)->firstOrFail();

        return Seller::query()->where('user_id', $user->id)->firstOrFail();
    }
}
