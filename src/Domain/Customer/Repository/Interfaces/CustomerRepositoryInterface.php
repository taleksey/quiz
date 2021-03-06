<?php

declare(strict_types=1);

namespace App\Domain\Customer\Repository\Interfaces;

use App\Domain\Customer\Entity\Customer;
use Symfony\Component\Security\Core\User\UserInterface;

interface CustomerRepositoryInterface
{
    public function getCustomerByEmail(string $email): ?UserInterface;
}
