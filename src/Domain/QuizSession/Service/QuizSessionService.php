<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Service;

use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Domain\QuizSession\Hydrator\QuizSessionHydratorInterface;
use App\Domain\QuizSession\Manager\QuizSessionManagerInterface;

class QuizSessionService
{
    private ResultRepositoryInterface $resultRepository;

    private QuizSessionHydratorInterface $quizSessionHydrator;

    private QuizSessionManagerInterface $quizSessionManager;

    public function __construct(
        ResultRepositoryInterface $resultRepository,
        QuizSessionHydratorInterface $quizSessionHydrator,
        QuizSessionManagerInterface $quizSessionManager
    ) {
        $this->resultRepository = $resultRepository;
        $this->quizSessionHydrator = $quizSessionHydrator;
        $this->quizSessionManager = $quizSessionManager;
    }

    /**
     * @param array<int|string, array<int|string, int|string>> $sessions
     * @param int $customerId
     * @return void
     */
    public function saveNotFinishedQuizzes(array $sessions, int $customerId)
    {
        $quizSessions = [];
        $prefixQuizSession = $this->resultRepository->getPrefixKey();
        $rawQuizSessions = array_filter($sessions, static function ($session, $key) use ($prefixQuizSession) {
            return str_starts_with($key, $prefixQuizSession);
        }, ARRAY_FILTER_USE_BOTH);
        foreach ($rawQuizSessions as $quizSessionKey => $session) {
            $quizId = (int) str_replace($prefixQuizSession, '', $quizSessionKey);

            $quizSessions[] = $this->quizSessionHydrator->hydrate($session, $quizId, $customerId);
        }
        $this->quizSessionManager->removeQuizSessionsByCustomerId($customerId);
        $this->quizSessionManager->save($quizSessions);
    }

    public function restoreNotFinishedQuizzes(int $customerId): void
    {
        $quizSessions = $this->quizSessionManager->getQuizSessions($customerId);

        foreach ($quizSessions as $quizSession) {
            $this->resultRepository->saveAnswers($quizSession);
        }
    }
}
