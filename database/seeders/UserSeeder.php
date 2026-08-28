<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class UserSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        User::truncate();

        Schema::enableForeignKeyConstraints();

        User::factory()->create([
            'name' => 'Verified Seller',
            'email' => 'verified@kinkoza.test',
        ]);

        User::factory()->create([
            'name' => 'Unverified Seller',
            'email' => 'unverified@kinkoza.test',
        ]);

        User::factory()->create([
            'name' => 'Buyer User',
            'email' => 'buyer@kinkoza.test',
        ]);
    }
}
