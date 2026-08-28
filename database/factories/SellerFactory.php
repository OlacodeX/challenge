<?php

namespace Database\Factories;

use App\Enums\KybStatus;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Seller>
 */
class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'company_name' => fake()->company(),
            'vat_number' => strtoupper(fake()->bothify('??########')),
            'registration_number' => strtoupper(fake()->bothify('REG-######')),
            'contact_email' => fake()->companyEmail(),
            'contact_phone' => fake()->e164PhoneNumber(),
            'kyb_status' => KybStatus::PENDING,
        ];
    }

    public function verified(): static
    {
        return $this->state(fn (array $attributes) => [
            'kyb_status' => KybStatus::VERIFIED,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'kyb_status' => KybStatus::REJECTED,
        ]);
    }
}
