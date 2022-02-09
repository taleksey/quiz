<?php

declare(strict_types=1);

namespace App\Presentation\DTO\Statistic;

use App\Domain\Quiz\ValueObject\QuizResult;
use App\Infrastructure\DB\Statistics\Customer;
use App\Infrastructure\DB\Statistics\Quiz;

class StatisticDTO
{
    private Quiz $quiz;

    private Customer $customer;

    private QuizResult $quizResult;

    /**
     * @param array<string, int|QuizResult> $statistic
     */
    public function __construct(array $statistic)
    {
        $this->quiz = new Quiz();
        $this->quiz->setId($statistic['quizId']);

        $this->customer = new Customer();
        $this->customer->setId($statistic['customerId']);

        $this->quizResult = $statistic['quizResult'];
    }

    public function getStartDate(): \DateTime
    {
        return $this->quizResult->getStartDate();
    }

    public function getQuiz(): Quiz
    {
        return $this->quiz;
    }

    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    public function getQuizResult(): QuizResult
    {
        return $this->quizResult;
    }
}
