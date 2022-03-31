<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\QuizSession;

use App\Domain\QuizSession\Repository\QuizSessionRepositoryInterface;
use App\Infrastructure\DB\QuizSession\QuizSession;
use App\Infrastructure\Repository\DbRepository;
use App\Domain\QuizSession\Entity\QuizSession as QuizSessionDomain;

/**
 * @extends DbRepository<QuizSessionDomain>
 */
class QuizSessionRepository extends DbRepository implements QuizSessionRepositoryInterface
{
    public function removeQuizSessionsByCustomerId(int $customerId): void
    {
        $this->manager->createQueryBuilder('qs')
            ->delete()
            ->where('qs.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->getQuery()
            ->execute();
    }

    public function getQuizSessions(int $customerId): array
    {
        return $this->manager->createQueryBuilder('qs')
            ->where('qs.customer = :customerId')
            ->setParameter('customerId', $customerId)
            ->getQuery()
            ->getResult();
    }

    public function getQuizSession(QuizSession $quizSession): QuizSessionDomain
    {
        $quizSessionEntity = $this->manager->findOneBy([
            'quiz' => $quizSession->getQuiz()->getId(),
            'customer' => $quizSession->getCustomer()->getId()
        ]);

        if (empty($quizSessionEntity)) {
            return new QuizSessionDomain();
        }

        return $quizSessionEntity;
    }

    protected function getFullEntityName(): string
    {
        return QuizSession::class;
    }
}
