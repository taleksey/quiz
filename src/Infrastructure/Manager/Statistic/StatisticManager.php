<?php

declare(strict_types=1);

namespace App\Infrastructure\Manager\Statistic;

use App\Domain\Statistics\Entity\Customer;
use App\Domain\Statistics\Entity\Customer as CustomerDomain;
use App\Domain\Statistics\Entity\QuizStatistic;
use App\Domain\Statistics\Manager\StatisticManagerInterface;
use App\Domain\Statistics\Repository\StatisticRepositoryInterface;
use App\Infrastructure\DB\Statistics\Customer as DBCustomer;
use App\Infrastructure\DB\Statistics\Quiz;
use App\Infrastructure\DB\Statistics\QuizStatistic as DBQuizStatistic;
use Doctrine\Persistence\ObjectManager;

class StatisticManager implements StatisticManagerInterface
{
    public function __construct(
        private ObjectManager $objectManager,
        private StatisticRepositoryInterface $statisticRepository
    ) {}

    public function save(QuizStatistic $entity): void
    {
        $quiz = $this->objectManager->find(Quiz::class, $entity->getQuiz()->getId());
        $entity->setQuiz($quiz);
        $customer = $this->objectManager->find(DBCustomer::class, $entity->getCustomer()->getId());
        $entity->setCustomer($customer);
        $this->saveEntity($entity);
    }

    public function update(QuizStatistic $entity, DBQuizStatistic $updateEntity): void
    {
        $updateEntity->setTotalQuestions($entity->getTotalQuestions());
        $updateEntity->setTotalCorrectAnswers($entity->getTotalCorrectAnswers());
        $updateEntity->setRawAnswers($entity->getRawAnswers());
        $updateEntity->setSpendSecondsQuiz($entity->getSpendSecondsQuiz());
        $this->saveEntity($updateEntity);
    }

    /**
     * @param int $quizId
     * @return array<int, QuizStatistic>
     */
    public function getStatistics(int $quizId): array
    {
        return $this->statisticRepository->getStatistics($quizId);
    }

    public function getPositionCurrentCustomer(CustomerDomain $customer, int $quizId): int
    {
        return $this->statisticRepository->getPositionCurrentCustomer($customer, $quizId);
    }

    public function getTotalCorrectAnswersByCustomer(CustomerDomain $customer, int $quizId): int
    {
        return $this->statisticRepository->getTotalCorrectAnswersByCustomer($customer, $quizId);
    }

    public function getQuizStatisticByCustomer(int $quizId, CustomerDomain $customer): DBQuizStatistic
    {
        return $this->statisticRepository->getQuizStatisticByCustomer($quizId, $customer);
    }

    private function saveEntity(mixed $entity): void
    {
        $this->objectManager->persist($entity);
        $this->objectManager->flush();
    }
}
