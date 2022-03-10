<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\QuizSession;

use App\Domain\QuizSession\Repository\QuizSessionRepositoryInterface;
use App\Infrastructure\DB\QuizSession\QuizSession;
use App\Infrastructure\DB\QuizSession\Customer;
use App\Infrastructure\DB\QuizSession\Quiz;
use App\Infrastructure\Repository\DbRepository;
use App\Domain\QuizSession\Entity\QuizSession as QuizSessionDomain;

/**
 * @extends DbRepository<QuizSessionDomain>
 */
class QuizSessionRepository extends DbRepository implements QuizSessionRepositoryInterface
{
    /**
     * @param array<int, QuizSession> $sessions
     * @return void
     */
    public function save(array $sessions): void
    {
        foreach ($sessions as $quizSession) {
            /** @var QuizSessionDomain|null $quizSessionEntity */
            $quizSessionEntity = $this->manager->findOneBy([
                'quiz' => $quizSession->getQuiz()->getId(),
                'customer' => $quizSession->getCustomer()->getId()
            ]);

            if (null === $quizSessionEntity) {
                $quiz = $this->entityManager->find(Quiz::class, $quizSession->getQuiz()->getId());
                $quizSession->setQuiz($quiz);
                $customer = $this->entityManager->find(Customer::class, $quizSession->getCustomer()->getId());
                $quizSession->setCustomer($customer);
            }

            if (null !== $quizSessionEntity) {
                $quizSessionEntity->setSession($quizSession->getSession());
                $quizSession = $quizSessionEntity;
            }
            $this->entityManager->persist($quizSession);
        }

        $this->entityManager->flush();
    }

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

    protected function getFullEntityName(): string
    {
        return QuizSession::class;
    }
}
