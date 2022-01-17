<?php

namespace App\Domain\Quiz\Repository\Interfaces;

interface ResultRepositoryInterface
{
    public function save(int $quizId, int $step, bool $answerValue): void;

    public function getSavedCustomerAnswers(int $quizId): array;

    public function clean(int $quizId): bool;
}
