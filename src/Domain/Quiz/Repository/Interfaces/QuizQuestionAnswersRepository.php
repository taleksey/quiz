<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Answer;
use App\Presentation\DTO\QuizQuestionAnswerRequestDTO;

interface QuizQuestionAnswersRepository
{
    public function getAnswer(QuizQuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Answer;
}
