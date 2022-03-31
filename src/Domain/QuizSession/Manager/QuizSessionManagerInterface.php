<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Manager;

use App\Infrastructure\DB\QuizSession\QuizSession;
use App\Domain\QuizSession\Entity\QuizSession as QuizSessionDomain;

interface QuizSessionManagerInterface
{
    /**
     * @param array<int, QuizSessionDomain> $sessions
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
