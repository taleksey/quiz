<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Quiz;

interface RepositoryInterface
{
    public function getQuizzes(): array;

    public function getQuizById(int $id): ?Quiz;

    public function getTotalQuizzes(): int;

    public function save(Quiz $quiz): void;
}
