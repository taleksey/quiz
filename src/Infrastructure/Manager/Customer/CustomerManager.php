<?php

declare(strict_types=1);

namespace App\Infrastructure\Manager\Customer;

use App\Domain\Customer\Entity\Customer;
use App\Domain\Customer\Manager\CustomerManagerInterface;
use App\Domain\Customer\Repository\Interfaces\CustomerRepositoryInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\User\UserInterface;

class CustomerManager implements CustomerManagerInterface
{
    public function __construct(
        private ObjectManager $objectManager,
        private CustomerRepositoryInterface $customerRepository
    ) {
    }

    public function create(Customer $customer): void
    {
        $this->objectManager->persist($customer);
        $this->objectManager->flush();
    }

    public function getCustomerByEmail(string $email): ?UserInterface
    {
        return $this->customerRepository->getCustomerByEmail($email);
    }
}
