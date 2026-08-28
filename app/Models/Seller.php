<?php

namespace App\Models;

use App\Enums\KybStatus;
use Database\Factories\SellerFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seller extends Model
{
    /** @use HasFactory<SellerFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_name',
        'vat_number',
        'registration_number',
        'contact_email',
        'contact_phone',
        'kyb_status',
    ];

    protected function casts(): array
    {
        return [
            'kyb_status' => KybStatus::class,
        ];
    }

    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isVerified(): bool
    {
        return $this->kyb_status === KybStatus::VERIFIED;
    }
}
