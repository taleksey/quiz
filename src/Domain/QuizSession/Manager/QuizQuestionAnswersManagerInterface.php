<?php

declare(strict_types=1);

namespace App\Domain\QuizSession\Manager;

use App\Domain\QuizSession\Entity\QuizSession;

interface QuizQuestionAnswersManagerInterface
{
    public function save(int $quizId, int $step, bool $answerValue): void;

    public function saveQuizStartDate(int $quizId, \DateTime $date): void;

    public function saveAnswers(QuizSession $quizSession): void;

    public function clean(int $quizId): bool;
}
