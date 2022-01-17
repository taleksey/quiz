<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Question;

interface QuestionsRepositoryInterface
{
    public function getQuestionByQuizIdAndQueue(int $quizId, int $step): ?Question;

    public function getTotalQuestions(int $quizId): int;
}
