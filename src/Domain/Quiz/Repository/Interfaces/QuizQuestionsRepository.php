<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Question;

interface QuizQuestionsRepository
{
    public function getQuestionByQuizIdAndQueue(int $quizId, int $step): ?Question;

    public function getTotalQuestions(int $quizId): int;
}
