<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Repository\Interfaces;

interface ResultRepositoryInterface
{
    public function save(int $quizId, int $step, bool $answerValue): void;

    /**
     * @param int $quizId
     * @return array<int, string>
     */
    public function getSavedCustomerAnswers(int $quizId): array;

    public function clean(int $quizId): bool;
}
