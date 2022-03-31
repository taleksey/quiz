<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Repository;

use App\Infrastructure\DB\QuizSession\Customer;
use App\Domain\QuizSession\Entity\Customer as CustomerDomain;

interface CustomerRepositoryInterface
{
    public function getCustomer(CustomerDomain $customer): Customer;
}
