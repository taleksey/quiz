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
        $statistic->setRawAnswers($statisticDTO->getRawAnswers());

        return $statistic;
    }

    private function getTotalQuestions(StatisticDTO $statisticDTO): int
    {
        $questions = array_filter($statisticDTO->getRawAnswers(), static function ($key) {
            return is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        return count($questions);
    }

    private function getTotalCorrectAnswers(StatisticDTO $statisticDTO): int
    {
        $correctQuestions = array_filter($statisticDTO->getRawAnswers(), static function ($answer, $key) {
            return is_int($key) && $answer;
        }, ARRAY_FILTER_USE_BOTH);

        return count($correctQuestions);
    }
}
