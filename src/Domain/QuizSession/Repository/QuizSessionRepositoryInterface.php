<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Repository;

use App\Domain\QuizSession\Entity\QuizSession;

interface QuizSessionRepositoryInterface
{
    /**
     * @param array<int, QuizSession> $sessions
     * @return void
     */
    public function save(array $sessions): void;

    public function removeQuizSessionsByCustomerId(int $customerId): void;

    /**
     * @param int $customerId
     * @return array<int, QuizSession>
     */
    public function getQuizSessions(int $customerId): array;
}
