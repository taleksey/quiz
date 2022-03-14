<?php

declare(strict_types=1);

namespace App\Domain\Customer\Manager;

use App\Domain\Customer\Entity\Customer;
use Symfony\Component\Security\Core\User\UserInterface;

interface CustomerManagerInterface
{
    public function create(Customer $customer): void;

    public function getCustomerByEmail(string $email): ?UserInterface;
}
