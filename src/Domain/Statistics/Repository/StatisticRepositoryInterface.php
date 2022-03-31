<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Repository;

use App\Domain\Statistics\Entity\Customer;
use App\Domain\Statistics\Entity\Customer as CustomerDomain;
use App\Infrastructure\DB\Statistics\QuizStatistic as DBQuizStatistic;
use App\Domain\Statistics\Entity\QuizStatistic;

interface StatisticRepositoryInterface
{
    /**
     * @param int $quizId
     * @return array<int, QuizStatistic>
     */
    public function getStatistics(int $quizId): array;

    public function getPositionCurrentCustomer(CustomerDomain $customer, int $quizId): int;

    public function getTotalCorrectAnswersByCustomer(CustomerDomain $customer, int $quizId): int;

    public function getQuizStatisticByCustomer(int $quizId, CustomerDomain $customer): DBQuizStatistic;
}
