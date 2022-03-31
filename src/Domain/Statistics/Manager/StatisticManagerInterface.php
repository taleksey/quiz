<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Manager;

use App\Domain\Statistics\Entity\Customer;
use App\Domain\Statistics\Entity\QuizStatistic;
use App\Infrastructure\DB\Statistics\QuizStatistic as DBQuizStatistic;

interface StatisticManagerInterface
{
    public function save(QuizStatistic $entity): void;

    public function update(QuizStatistic $entity, DBQuizStatistic $updateEntity): void;

    /**
     * @param int $quizId
     * @return array<int, QuizStatistic>
     */
    public function getStatistics(int $quizId): array;

    public function getPositionCurrentCustomer(Customer $customer, int $quizId): int;

    public function getTotalCorrectAnswersByCustomer(Customer $customer, int $quizId): int;

    public function getQuizStatisticByCustomer(int $quizId, Customer $customer): DBQuizStatistic;
}
