<?php

namespace App\Domain\Customer\Entity;

class CustomerRole
{
    public static function getDefaultRole(): string
    {
        return 'ROLE_USER';
    }
}
