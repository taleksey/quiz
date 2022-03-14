<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\ValueObject\QuizResult;
use App\Domain\QuizSession\Entity\QuizSession;

interface ResultRepositoryInterface
{
    /**
     * @param int $quizId
     * @return array<int, string>
     */
    public function getSavedCustomerAnswers(int $quizId): array;

    public function getQuizResult(int $quizId): QuizResult;

    public function getPrefixKey(): string;

    public function getQuizMainKey(int $quizId): string;
}
