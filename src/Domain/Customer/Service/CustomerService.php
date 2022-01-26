<?php

declare(strict_types=1);

namespace App\Domain\Customer\Service;

use App\Domain\Customer\Repository\Interfaces\CustomerRepositoryInterface;
use App\Presentation\DTO\Customer\CustomerDTO;
use App\Presentation\Hydrator\Customer\CustomerHydrator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class CustomerService
{
    private UserPasswordHasherInterface $passwordHash;
    private CustomerRepositoryInterface $customerRepository;
    private CustomerHydrator $hydrator;

    public function __construct(UserPasswordHasherInterface $passwordHash, CustomerRepositoryInterface $customerRepository, CustomerHydrator $hydrator)
    {
        $this->passwordHash = $passwordHash;
        $this->customerRepository = $customerRepository;
        $this->hydrator = $hydrator;
    }

    public function registrationCustomer(CustomerDTO $customer, string $password): void
    {
        $hashPassword = $this->passwordHash->hashPassword($customer, $password);
        $customerEntity = $this->hydrator->hydrate($customer, $hashPassword);
        $this->customerRepository->create($customerEntity);
    }
}
