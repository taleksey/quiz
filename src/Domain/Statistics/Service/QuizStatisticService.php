<?php

declare(strict_types=1);

namespace App\Domain\Statistics\Service;

use App\Domain\Statistics\Entity\Customer;
use App\Domain\Statistics\Entity\QuizStatistic;
use App\Domain\Statistics\Hydrator\StatisticHydratorInterface;
use App\Domain\Statistics\Repository\StatisticRepositoryInterface;
use App\Presentation\DTO\Statistic\StatisticDTO;

class QuizStatisticService
{
    private StatisticRepositoryInterface $statisticRepository;

    private StatisticHydratorInterface $statisticHydrator;

    public function __construct(
        StatisticRepositoryInterface $statisticRepository,
        StatisticHydratorInterface $statisticHydrator
    ) {
        $this->statisticRepository = $statisticRepository;
        $this->statisticHydrator = $statisticHydrator;
    }

    public function saveResultQuiz(StatisticDTO $statisticDTO): void
    {
        $dateTime = $statisticDTO->getDateTimeWhenStartQuiz();
        $currentDateTime = new \DateTime('NOW');
        $dateTimeResult = $currentDateTime->diff($dateTime);
        $spendSecondsOnQuiz = $this->convertToSeconds($dateTimeResult);

        $statistic = $this->statisticHydrator->hydrate($statisticDTO, $spendSecondsOnQuiz);
        $this->statisticRepository->saveOrUpdate($statistic);
    }

    /**
     * @param int $quizId
     * @return array<int, QuizStatistic>
     */
    public function getQuizStatistics(int $quizId): array
    {
        return $this->statisticRepository->getStatistics($quizId);
    }

    public function getPositionCurrentCustomer(Customer $customer, int $quizId): int
    {
        return $this->statisticRepository->getPositionCurrentCustomer($customer, $quizId);
    }

    public function getTotalCorrectAnswersByCustomer(Customer $customer, int $quizId): int
    {
        return $this->statisticRepository->getTotalCorrectAnswersByCustomer($customer, $quizId);
    }

    private function convertToSeconds(\DateInterval $dateInterval): int
    {
        return (int)(($dateInterval->days * 24 * 60 * 60) +
            ($dateInterval->h * 60 * 60) +
            ($dateInterval->i * 60) +
            $dateInterval->s);
    }
}
