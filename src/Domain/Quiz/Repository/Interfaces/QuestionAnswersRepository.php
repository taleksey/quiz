<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Answer;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;

interface QuestionAnswersRepository
{
    public function getAnswer(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Answer;
}
