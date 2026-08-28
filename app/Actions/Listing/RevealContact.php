<?php

namespace App\Actions\Listing;

use App\Actions\Audit\RecordAudit;
use App\Enums\AuditAction;
use App\Models\Listing;
use App\Models\User;
use Illuminate\Support\Facades\RateLimiter;
use Lorisleiva\Actions\Concerns\AsAction;

class RevealContact
{
    use AsAction;

    /**
     * @return array{email: string, phone: string}
     */
    public function handle(Listing $listing, User $user): array
    {
        $this->enforceContactRevealRateLimit();

        $listing->loadMissing('seller');

        $ipAddress = request()->ip();
        $userAgent = request()->userAgent();

        RecordAudit::dispatch(
            AuditAction::CONTACT_REVEALED,
            $user,
            $listing,
            [
                'ip_address' => $ipAddress,
                'user_agent' => $userAgent,
            ],
        );

        return [
            'email' => $listing->seller()->value('contact_email'),
            'phone' => $listing->seller()->value('contact_phone'),
        ];
    }

    private function enforceContactRevealRateLimit(): void
    {
        $limit = RateLimiter::limiter('contact-reveal')(request());

        if (RateLimiter::tooManyAttempts($limit->key, $limit->maxAttempts)) {
            abort(429);
        }

        RateLimiter::hit($limit->key, $limit->decaySeconds);
    }
}
