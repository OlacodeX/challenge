<?php

namespace Database\Seeders;

use App\Enums\KybStatus;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class SellerSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        Seller::truncate();

        Schema::enableForeignKeyConstraints();

        $verifiedUser = User::query()->where('email', 'verified@kinkoza.test')->firstOrFail();

        Seller::factory()->verified()->create([
            'user_id' => $verifiedUser->id,
            'company_name' => 'Kinkoza Verified BV',
            'vat_number' => 'BE0123456789',
            'registration_number' => 'BE-REG-001234',
            'contact_email' => 'sales@kinkoza-verified.test',
            'contact_phone' => '+32470123456',
        ]);

        $unverifiedUser = User::query()->where('email', 'unverified@kinkoza.test')->firstOrFail();

        Seller::factory()->create([
            'user_id' => $unverifiedUser->id,
            'company_name' => 'Pending Traders SA',
            'vat_number' => 'FR98765432109',
            'registration_number' => null,
            'contact_email' => 'info@pending-traders.test',
            'contact_phone' => '+33123456789',
            'kyb_status' => KybStatus::PENDING,
        ]);

        Seller::factory()->count(3)->verified()->create();
    }
}
