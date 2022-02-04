<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Repository\Interfaces;

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

    /**
     * @param int $quizId
     * @return array <int|string, string|bool>
     */
    public function getQuizResult(int $quizId): array;

    public function getPrefixKey(): string;

    public function saveAnswers(QuizSession $quizSession): void;
}
