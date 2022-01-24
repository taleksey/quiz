<?php

declare(strict_types=1);

namespace App\Domain\Customer\Entity;

class CustomerRole
{
    public static function getDefaultRole(): string
    {
        return 'ROLE_USER';
    }
}
