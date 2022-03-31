<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\QuizSession;

use App\Domain\QuizSession\Entity\Customer as CustomerDomain;
use App\Domain\QuizSession\Repository\CustomerRepositoryInterface;
use App\Infrastructure\DB\QuizSession\Customer;
use App\Infrastructure\Repository\DbRepository;

/**
 * @extends DbRepository<CustomerDomain>
 */
class CustomerRepository extends DbRepository implements CustomerRepositoryInterface
{
    public function getCustomer(CustomerDomain $customer): Customer
    {
        return $this->manager->find($customer->getId());
    }

    protected function getFullEntityName(): string
    {
        return Customer::class;
    }
}
