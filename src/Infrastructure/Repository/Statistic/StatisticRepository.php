<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\Statistic;

use App\Domain\Statistics\Entity\QuizStatistic as QuizStatisticDomain;
use App\Domain\Statistics\Repository\StatisticRepositoryInterface;
use App\Infrastructure\DB\Statistics\Customer;
use App\Infrastructure\DB\Statistics\Quiz;
use App\Infrastructure\DB\Statistics\QuizStatistic;
use App\Infrastructure\Repository\DbRepository;
use App\Domain\Statistics\Entity\Customer as CustomerDomain;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;

/**
 * @extends DbRepository<QuizStatisticDomain>
 */
class StatisticRepository extends DbRepository implements StatisticRepositoryInterface
{
    public function saveOrUpdate(QuizStatisticDomain $statistic): void
    {
        /** @var QuizStatistic|null $statisticEntity */
        $statisticEntity = $this->manager->findOneBy([
            'customer' => $statistic->getCustomer()->getId(),
            'quiz' => $statistic->getQuiz()->getId()
        ]);

        if (null === $statisticEntity) {
            $quiz = $this->entityManager->find(Quiz::class, $statistic->getQuiz()->getId());
            $statistic->setQuiz($quiz);
            $customer = $this->entityManager->find(Customer::class, $statistic->getCustomer()->getId());
            $statistic->setCustomer($customer);
        }

        if (null !== $statisticEntity) {
            $statisticEntity->setTotalQuestions($statistic->getTotalQuestions());
            $statisticEntity->setTotalCorrectAnswers($statistic->getTotalCorrectAnswers());
            $statisticEntity->setRawAnswers($statistic->getRawAnswers());
            $statisticEntity->setSpendSecondsQuiz($statistic->getSpendSecondsQuiz());
            $statistic = $statisticEntity;
        }

        $this->entityManager->persist($statistic);
        $this->entityManager->flush();
    }

    public function getStatistics(int $quizId): array
    {
        return $this->manager->findBy([
            "quiz" => $quizId
        ], [
            'totalCorrectAnswers' => 'DESC',
            'spendSecondsQuiz' => 'ASC'
        ], 3);
    }

    public function getPositionCurrentCustomer(CustomerDomain $customer, int $quizId): int
    {
        /** @var QuizStatistic $statistic */
        $statistic =  $this->manager->findOneBy([
            'quiz' => $quizId,
            'customer' => $customer->getId()
        ]);

        try {
            $beforePositions =  (int) $this->manager->createQueryBuilder('st')
                ->select('count(st.id)')
                ->where('st.quiz = :quiz')
                ->andWhere('st.totalCorrectAnswers >= :totalCorrectAnswers')
                ->andWhere('st.spendSecondsQuiz < :spendSecondsQuiz')
                ->setParameters([
                    'quiz' => $quizId,
                    'totalCorrectAnswers' => $statistic->getTotalCorrectAnswers(),
                    'spendSecondsQuiz' => $statistic->getSpendSecondsQuiz()
                ])
                ->getQuery()
                ->getSingleScalarResult()
            ;
            return $beforePositions + 1;
        } catch (NoResultException|NonUniqueResultException) {
        }

        return 0;
    }

    public function getTotalCorrectAnswersByCustomer(CustomerDomain $customer, int $quizId): int
    {
        /** @var QuizStatistic|null $statistic */
        $statistic =  $this->manager->findOneBy([
            'quiz' => $quizId,
            'customer' => $customer->getId()
        ]);

        if (null !== $statistic) {
            return $statistic->getTotalCorrectAnswers();
        }

        return 0;
    }

    protected function getFullEntityName(): string
    {
        return QuizStatistic::class;
    }
}
