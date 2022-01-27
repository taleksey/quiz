<?php

declare(strict_types=1);

namespace App\Presentation\Hydrator\Customer;

use App\Domain\Customer\Entity\Customer;
use App\Presentation\DTO\Customer\CustomerDTO;

class CustomerHydrator
{
    public function hydrate(CustomerDTO $customerDTO, string $password): Customer
    {
        $customerEntity = new Customer();
        $customerEntity->setNickName($customerDTO->getNickname());
        $customerEntity->setFirstName($customerDTO->getFirstName());
        $customerEntity->setLastName($customerDTO->getLastName());
        $customerEntity->setEmail($customerDTO->getEmail());
        $customerEntity->setPassword($password);

        return $customerEntity;
    }
}
