<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\ValueObject\QuizResult;
use App\Domain\QuizSession\Entity\QuizSession;

interface ResultRepositoryInterface
{
    public function save(int $quizId, int $step, bool $answerValue): void;

    /**
     * @param int $quizId
     * @return array<int, string>
     */
    public function getSavedCustomerAnswers(int $quizId): array;

    public function clean(int $quizId): bool;

    public function saveQuizStartDate(int $quizId, \DateTime $date): void;

    public function getQuizResult(int $quizId): QuizResult;

    public function getPrefixKey(): string;

    public function saveAnswers(QuizSession $quizSession): void;
}
