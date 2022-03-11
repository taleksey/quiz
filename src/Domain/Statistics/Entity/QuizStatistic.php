<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Entity;

class QuizStatistic
{
    protected int $id;

    protected Customer $customer;

    protected Quiz $quiz;

    private int $totalCorrectAnswers;

    private int $totalQuestions;

    private int $spendSecondsQuiz;

    /**
     * @var array<int, string>
     */
    private array $rawAnswers = [];

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function setCustomer(Customer $customer): void
    {
        $this->customer = $customer;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(Quiz $quiz): void
    {
        $this->quiz = $quiz;
    }

    public function getTotalCorrectAnswers(): int
    {
        return $this->totalCorrectAnswers;
    }

    public function setTotalCorrectAnswers(int $totalCorrectAnswers): void
    {
        $this->totalCorrectAnswers = $totalCorrectAnswers;
    }

    public function getTotalQuestions(): int
    {
        return $this->totalQuestions;
    }

    public function setTotalQuestions(int $totalQuestions): void
    {
        $this->totalQuestions = $totalQuestions;
    }

    /**
     * @return array<int, string>
     */
    public function getRawAnswers(): array
    {
        return $this->rawAnswers;
    }

    /**
     * @param array<int, string> $rawAnswers
     * @return void
     */
    public function setRawAnswers(array $rawAnswers): void
    {
        $this->rawAnswers = $rawAnswers;
    }

    /**
     * @return int
     */
    public function getSpendSecondsQuiz(): int
    {
        return $this->spendSecondsQuiz;
    }

    /**
     * @param int $spendSecondsQuiz
     */
    public function setSpendSecondsQuiz(int $spendSecondsQuiz): void
    {
        $this->spendSecondsQuiz = $spendSecondsQuiz;
    }

    public function isEmpty(): bool
    {
        return empty($this->id);
    }
}
