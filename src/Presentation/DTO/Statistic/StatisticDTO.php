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

    public function getDateTimeWhenStartQuiz(): \DateTime
    {
        try {
            return new \DateTime($this->statistic['startDate']);
        } catch (\Exception) {
        }

        return new \DateTime('NOW');
    }

    public function getTotalQuestions(): int
    {
        $questions = array_filter($this->getRawAnswers(), static function ($key) {
            return is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        return count($questions);
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

    public function getTotalCorrectAnswers(): int
    {
        $correctQuestions = array_filter($this->getRawAnswers(), static function ($answer, $key) {
            return is_int($key) && $answer;
        }, ARRAY_FILTER_USE_BOTH);

        return count($correctQuestions);
    }

    /**
     * @return array <int|string, bool|string>
     */
    public function getRawAnswers(): array
    {
        return $this->statistic['answers'];
    }
}
