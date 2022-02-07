<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Statistic;

use App\Infrastructure\DB\Statistics\Customer;
use App\Infrastructure\DB\Statistics\Quiz;

class StatisticDTO
{
    /**
     * @var array<string, int|string|array<int|string, bool|string>>
     */
    private array $statistic;

    /**
     * @param array<string, int|string|array<int|string, bool|string>> $statistic
     */
    public function __construct(array $statistic)
    {
        $this->statistic = $statistic;
    }

    public function getStartDate(): string
    {
        return $this->statistic['startDate'];
    }

    public function getQuiz(): Quiz
    {
        $quiz = new Quiz();
        $quiz->setId($this->statistic['quizId']);

        return $quiz;
    }

    public function getCustomer(): Customer
    {
        $customer = new Customer();
        $customer->setId($this->statistic['customerId']);

        return $customer;
    }

    /**
     * @return array <int|string, bool|string>
     */
    public function getRawAnswers(): array
    {
        return $this->statistic['answers'];
    }
}
