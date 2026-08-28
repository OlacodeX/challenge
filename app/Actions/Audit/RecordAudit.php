<?php

namespace App\Actions\Audit;

use App\Enums\AuditAction;
use App\Models\Audit;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Lorisleiva\Actions\Concerns\AsAction;

class RecordAudit implements ShouldQueue
{
    use AsAction;

    public function handle(
        AuditAction $action,
        ?User $user,
        Model $auditable,
        array $metadata = [],
    ): Audit {
        return Audit::query()->create([
            'user_id' => $user?->id,
            'action' => $action,
            'auditable_type' => $auditable->getMorphClass(),
            'auditable_id' => $auditable->getKey(),
            'metadata' => $metadata,
            'created_at' => now(),
        ]);
    }
}
