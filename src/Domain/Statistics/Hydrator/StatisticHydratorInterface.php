<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Hydrator;

use App\Domain\Statistics\Entity\QuizStatistic;
use App\Presentation\DTO\Statistic\StatisticDTO;

interface StatisticHydratorInterface
{
    public function hydrate(StatisticDTO $statisticDTO, int $spendSecondsOnQuiz): QuizStatistic;
}
