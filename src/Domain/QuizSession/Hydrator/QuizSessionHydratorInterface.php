<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Hydrator;

use App\Domain\QuizSession\Entity\QuizSession;

interface QuizSessionHydratorInterface
{
    /**
     * @param array<int|string, int|string> $session
     * @param int $quizId
     * @param int $customerId
     * @return QuizSession
     */
    public function hydrate(array $session, int $quizId, int $customerId): QuizSession;
}
