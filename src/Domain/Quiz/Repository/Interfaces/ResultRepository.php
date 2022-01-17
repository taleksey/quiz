<?php

namespace App\Domain\Quiz\Repository\Interfaces;

interface ResultRepository
{
    public function save(int $quizId, int $step, bool $answerValue): void;

    public function getSavedCustomerAnswers(int $quizId): array;

    public function clean(int $quizId): bool;
}
