<?php

namespace App\Enums;

enum AuditAction: string
{
    case ContactRevealed = 'contact_revealed';

    public function label(): string
    {
        return match ($this) {
            self::ContactRevealed => 'Contact Revealed',
        };
    }
}
