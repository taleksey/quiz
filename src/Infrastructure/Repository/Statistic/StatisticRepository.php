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
        $statistic = $this->getQuizStatisticByCustomer($quizId, $customer);
        if ($statistic->isEmpty()) {
            return  0;
        }
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
        $statistic = $this->getQuizStatisticByCustomer($quizId, $customer);
        if (! $statistic->isEmpty()) {
            return $statistic->getTotalCorrectAnswers();
        }

        return 0;
    }

    public function getQuizStatisticByCustomer(int $quizId, CustomerDomain $customer): QuizStatistic
    {
        $quizStatistic = $this->manager->findOneBy([
            'customer' => $customer->getId(),
            'quiz' => $quizId
        ]);

        if (! empty($quizStatistic)) {
            return $quizStatistic;
        }

        return new QuizStatistic();
    }

    protected function getFullEntityName(): string
    {
        return QuizStatistic::class;
    }
}
