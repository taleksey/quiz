<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Repository;

use App\Infrastructure\DB\QuizSession\QuizSession;
use App\Domain\QuizSession\Entity\QuizSession as QuizSessionDomain;

interface QuizSessionRepositoryInterface
{
    public function removeQuizSessionsByCustomerId(int $customerId): void;

    /**
     * @param int $customerId
     * @return array<int, QuizSession>
     */
    public function getQuizSessions(int $customerId): array;

    public function getQuizSession(QuizSession $quizSession): QuizSessionDomain;
}
