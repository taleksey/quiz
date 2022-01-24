<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Entity;

class Customer
{
    /**
     * @var array<string, string>
     */
    private array $customer;

    /**
     * @param array<string, string> $customer
     */
    public function __construct(array $customer)
    {
        $this->customer = $customer;
    }

    public function getEmail(): string
    {
        return $this->customer['email'] ?? '';
    }
}
