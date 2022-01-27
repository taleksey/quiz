<?php

declare(strict_types=1);

namespace App\Infractructure\Repository\Registration;

use App\Domain\Customer\Entity\Customer;
use App\Domain\Customer\Repository\Interfaces\CustomerRepositoryInterface;
use App\Infractructure\Repository\DbRepository;

/**
 * @extends DbRepository<Customer>
 */
class CustomerRepository extends DbRepository implements CustomerRepositoryInterface
{
    public function create(Customer $customer): void
    {
        $this->entityManager->persist($customer);
        $this->entityManager->flush();
    }

    public function getCustomerByEmail(string $email): ?Customer
    {
        return $this->manager->findOneBy([
            'email' => $email
        ]);
    }

    /**
     * @return class-string<Customer>
     */
    protected function getFullEntityName(): string
    {
        return Customer::class;
    }
}
