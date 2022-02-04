<?php

declare(strict_types=1);

namespace App\Presentation\Hydrator\Statistic;

use App\Domain\Statistics\Entity\QuizStatistic as QuizStatisticDomain;
use App\Domain\Statistics\Hydrator\StatisticHydratorInterface;
use App\Infrastructure\DB\Statistics\QuizStatistic;
use App\Presentation\DTO\Statistic\StatisticDTO;

class StatisticHydrator implements StatisticHydratorInterface
{
    public function hydrate(StatisticDTO $statisticDTO, int $spendSecondsOnQuiz): QuizStatisticDomain
    {
        $statistic = new QuizStatistic();
        $statistic->setSpendSecondsQuiz($spendSecondsOnQuiz);
        $statistic->setQuiz($statisticDTO->getQuiz());
        $statistic->setCustomer($statisticDTO->getCustomer());
        $statistic->setTotalCorrectAnswers($statisticDTO->getTotalCorrectAnswers());
        $statistic->setTotalQuestions($statisticDTO->getTotalQuestions());
        $statistic->setRawAnswers($statisticDTO->getRawAnswers());

        return $statistic;
    }
}
