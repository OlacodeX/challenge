<?php

namespace App\Enums;

enum AuditAction: string
{
    case CONTACT_REVEALED = 'contact_revealed';

    public function label(): string
    {
        return match ($this) {
            self::CONTACT_REVEALED => 'Contact Revealed',
        };
    }
}
