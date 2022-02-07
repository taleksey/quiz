<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Statistic;

use App\Infrastructure\DB\Statistics\Customer;
use App\Infrastructure\DB\Statistics\Quiz;

class StatisticDTO
{
    private string $startDate;

    private Quiz $quiz;

    private Customer $customer;

    /**
     * @var array<int|string, bool|string>
     */
    private array $answers;

    /**
     * @param array<string, int|string|array<int|string, bool|string>> $statistic
     */
    public function __construct(array $statistic)
    {
        $this->startDate = $statistic['startDate'];
        $this->quiz = new Quiz();
        $this->quiz->setId($statistic['quizId']);

        $this->customer = new Customer();
        $this->customer->setId($statistic['customerId']);

        $this->answers = $statistic['answers'];
    }

    public function getStartDate(): string
    {
        return $this->startDate;
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * @return array <int|string, bool|string>
     */
    public function getRawAnswers(): array
    {
        return $this->answers;
    }
}
