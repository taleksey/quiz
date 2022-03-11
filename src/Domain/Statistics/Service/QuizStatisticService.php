<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Service;

use App\Domain\Statistics\Entity\Customer;
use App\Domain\Statistics\Entity\QuizStatistic;
use App\Domain\Statistics\Hydrator\StatisticHydratorInterface;
use App\Domain\Statistics\Manager\StatisticManagerInterface;
use App\Presentation\DTO\Statistic\StatisticDTO;

class QuizStatisticService
{
    private StatisticHydratorInterface $statisticHydrator;

    private StatisticManagerInterface $manager;

    public function __construct(
        StatisticHydratorInterface $statisticHydrator,
        StatisticManagerInterface $manager
    ) {
        $this->statisticHydrator = $statisticHydrator;
        $this->manager = $manager;
    }

    public function saveResultQuiz(StatisticDTO $statisticDTO): void
    {
        $dateTime = $statisticDTO->getStartDate();

        $currentDateTime = new \DateTime('NOW');
        $dateTimeResult = $currentDateTime->diff($dateTime);
        $spendSecondsOnQuiz = $this->convertToSeconds($dateTimeResult);

        $statistic = $this->statisticHydrator->hydrate($statisticDTO, $spendSecondsOnQuiz);
        $quizStatistic = $this->manager->getQuizStatisticByCustomer($statistic->getQuiz()->getId(), $statistic->getCustomer());
        if ($quizStatistic->isEmpty()) {
            $this->manager->save($statistic);
        } else {
            $this->manager->update($statistic, $quizStatistic);
        }
    }

    /**
     * @param int $quizId
     * @return array<int, QuizStatistic>
     */
    public function getQuizStatistics(int $quizId): array
    {
        return $this->manager->getStatistics($quizId);
    }

    public function getPositionCurrentCustomer(Customer $customer, int $quizId): int
    {
        return $this->manager->getPositionCurrentCustomer($customer, $quizId);
    }

    public function getTotalCorrectAnswersByCustomer(Customer $customer, int $quizId): int
    {
        return $this->manager->getTotalCorrectAnswersByCustomer($customer, $quizId);
    }

    private function convertToSeconds(\DateInterval $dateInterval): int
    {
        return (int)(($dateInterval->days * 24 * 60 * 60) +
            ($dateInterval->h * 60 * 60) +
            ($dateInterval->i * 60) +
            $dateInterval->s);
    }
}
