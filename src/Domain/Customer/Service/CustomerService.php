<?php

declare(strict_types=1);

namespace App\Domain\Customer\Service;

use App\Domain\Customer\Manager\CustomerManagerInterface;
use App\Presentation\DTO\Customer\CustomerDTO;
use App\Presentation\Hydrator\Customer\CustomerHydrator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerService
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHash,
        private CustomerHydrator $hydrator,
        private CustomerManagerInterface $customerManager
    ) {
    }

    public function registrationCustomer(CustomerDTO $customer, string $password): void
    {
        $hashPassword = $this->passwordHash->hashPassword($customer, $password);
        $customerEntity = $this->hydrator->hydrate($customer, $hashPassword);
        $this->customerManager->create($customerEntity);
    }
}
