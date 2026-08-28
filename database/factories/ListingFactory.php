<?php

namespace Database\Factories;

use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\ListingCategory;
use App\Enums\ListingStatus;
use App\Models\Listing;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    public function definition(): array
    {
        $title = $this->randomTitle();
        $dateOnline = fake()->dateTimeBetween('-3 months', '-1 week');
        $dateOffline = fake()->dateTimeBetween('+1 month', '+12 months');

        return [
            'seller_id' => Seller::factory(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numerify('####'),
            'description' => fake()->paragraphs(3, true),
            'category' => fake()->randomElement(ListingCategory::cases()),
            'price' => fake()->randomFloat(2, 5000, 2500000),
            'currency' => fake()->randomElement(Currency::cases()),
            'country' => fake()->randomElement(Country::cases()),
            'city' => fake()->city(),
            'date_online' => $dateOnline,
            'date_offline' => $dateOffline,
            'status' => ListingStatus::DRAFT,
        ];
    }

    public function publishedInWindow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::PUBLISHED,
            'date_online' => now()->subDays(fake()->numberBetween(7, 60)),
            'date_offline' => now()->addDays(fake()->numberBetween(30, 180)),
        ]);
    }

    public function publishedOutOfWindow(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::PUBLISHED,
            'date_online' => now()->subDays(fake()->numberBetween(120, 365)),
            'date_offline' => now()->subDays(fake()->numberBetween(1, 30)),
        ]);
    }

    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::DRAFT,
        ]);
    }

    public function pendingReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::PENDING_REVIEW,
        ]);
    }

    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::EXPIRED,
            'date_online' => now()->subDays(fake()->numberBetween(180, 365)),
            'date_offline' => now()->subDays(fake()->numberBetween(30, 90)),
        ]);
    }

    private function randomTitle(): string
    {
        $titles = [
            'CNC Milling Machine Model X400',
            'Industrial Forklift 5-Ton Capacity',
            'Commercial Warehouse Unit 2,400 sqm',
            'Fleet of 12 Delivery Vans',
            'Patent Portfolio — Logistics Software',
            'Heavy Duty Hydraulic Press',
            'Retail Property with Loading Bay',
            'Brand Trademark Package — EU Registered',
            'Excavator CAT 320D Low Hours',
            'Manufacturing Line — Packaging Equipment',
            'Office Building Class A — City Centre',
            'Customer Database & CRM License',
            'Automated Pallet Wrapping System',
            'Cold Storage Facility 1,800 sqm',
            'Trademark & Domain Bundle',
        ];

        return fake()->randomElement($titles);
    }
}
