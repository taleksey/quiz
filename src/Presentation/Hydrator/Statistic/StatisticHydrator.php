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
        $statistic->setTotalCorrectAnswers($this->getTotalCorrectAnswers($statisticDTO));
        $statistic->setTotalQuestions($this->getTotalQuestions($statisticDTO));
        $statistic->setRawAnswers($statisticDTO->getQuizResult()->toArray());

        return $statistic;
    }

    private function getTotalQuestions(StatisticDTO $statisticDTO): int
    {
        $quizResult = $statisticDTO->getQuizResult();

        return count($quizResult->getAnswers());
    }

    private function getTotalCorrectAnswers(StatisticDTO $statisticDTO): int
    {
        $quizResult = $statisticDTO->getQuizResult();

        return $quizResult->getTotalCorrectAnswers();
    }
}
