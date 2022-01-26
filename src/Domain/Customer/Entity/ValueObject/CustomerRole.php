<?php

declare(strict_types=1);

namespace App\Domain\Customer\Entity\ValueObject;

class CustomerRole
{
    /**
     * @var array<int, string>
     */
    private array $roles;

    /**
     * @param array<int, string> $roles
     */
    public function __construct(array $roles)
    {
        $this->roles = $roles;
    }

    /**
     * @return array<int, string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = $this->getDefaultRole();

        return array_unique($roles);
    }

    public function getDefaultRole(): string
    {
        return 'ROLE_USER';
    }
}
