<?php

namespace App\Domain\Customer\Repository\Interfaces;

use App\Domain\Customer\Entity\Customer;

interface CustomerRepositoryInterface
{
    public function create(Customer $customer): void;
}
