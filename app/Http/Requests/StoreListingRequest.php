<?php

namespace App\Http\Requests;

use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\ListingCategory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreListingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'category' => ['required', new Enum(ListingCategory::class)],
            'price' => ['required', 'numeric', 'min:0.01'],
            'currency' => ['required', new Enum(Currency::class)],
            'country' => ['required', new Enum(Country::class)],
            'city' => ['required', 'string', 'max:255'],
            'date_online' => ['required', 'date'],
            'date_offline' => ['required', 'date', 'after_or_equal:date_online'],
        ];
    }
}
