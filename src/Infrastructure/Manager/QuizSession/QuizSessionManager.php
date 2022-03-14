<?php

declare(strict_types=1);

namespace App\Infrastructure\Manager\QuizSession;

use App\Domain\QuizSession\Manager\QuizSessionManagerInterface;
use App\Domain\QuizSession\Repository\CustomerRepositoryInterface;
use App\Domain\QuizSession\Repository\QuizRepositoryInterface;
use App\Domain\QuizSession\Repository\QuizSessionRepositoryInterface;
use App\Infrastructure\DB\QuizSession\QuizSession;
use Doctrine\Persistence\ObjectManager;

/** @template T */
class QuizSessionManager implements QuizSessionManagerInterface
{
    public function __construct(
        private ObjectManager $objectManager,
        private QuizSessionRepositoryInterface $quizSessionRepository,
        private QuizRepositoryInterface $quizRepository,
        private CustomerRepositoryInterface $customerRepository
    ) {}

    /**
     * @param array<int, QuizSession> $sessions
     * @return void
     */
    public function save(array $sessions): void
    {
        foreach ($sessions as $quizSession) {
            $quizSessionEntity = $this->quizSessionRepository->getQuizSession($quizSession);

            if ($quizSessionEntity->isEmpty()) {
                $quiz = $this->quizRepository->getQuiz($quizSession->getQuiz());
                $quizSession->setQuiz($quiz);
                $customer = $this->customerRepository->getCustomer($quizSession->getCustomer());
                $quizSession->setCustomer($customer);
            }

            if (! $quizSessionEntity->isEmpty()) {
                $quizSessionEntity->setSession($quizSession->getSession());
                $quizSession = $quizSessionEntity;
            }

            $this->objectManager->persist($quizSession);
        }
        $this->objectManager->flush();
    }

    public function removeQuizSessionsByCustomerId(int $customerId): void
    {
        $this->quizSessionRepository->removeQuizSessionsByCustomerId($customerId);
    }

    /**
     * @param int $customerId
     * @return array<int, QuizSession>
     */
    public function getQuizSessions(int $customerId): array
    {
        return $this->quizSessionRepository->getQuizSessions($customerId);
    }
}
