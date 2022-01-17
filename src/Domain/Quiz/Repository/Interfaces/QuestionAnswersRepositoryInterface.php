<?php

namespace App\Domain\Quiz\Repository\Interfaces;

use App\Domain\Quiz\Entity\Answer;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;

interface QuestionAnswersRepositoryInterface
{
    public function getAnswer(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Answer;
}
