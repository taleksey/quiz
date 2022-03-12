<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Entity;

use App\Domain\Entity\EntityInterface;

class QuizSession implements EntityInterface
{
    protected int $id;

    protected Customer $customer;

    protected Quiz $quiz;

    /**
     * @var array<int|string, int|string>
     */
    protected array $session = [];

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    /**
     * @return Quiz
     */
    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    /**
     * @param Quiz $quiz
     */
    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    /**
     * @return array <int|string, int|string>
     */
    public function getSession(): array
    {
        return $this->session;
    }

    /**
     * @param array<int|string, int|string> $session
     */
    public function setSession(array $session): void
    {
        $this->session = $session;
    }

    public function isEmpty(): bool
    {
        return empty($this->id);
    }
}
