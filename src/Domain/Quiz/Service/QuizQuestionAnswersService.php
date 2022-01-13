<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\Interfaces\ResultRepository;
use App\Presentation\DTO\QuizQuestionAnswerRequestDTO;

class QuizQuestionAnswersService
{
    /**
     * @param ResultRepository $quizResultRepository
     */
    public function __construct(
        private ResultRepository $quizResultRepository
    ) {
    }

    /**
     * @param QuizQuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @param bool $resultAnswer
     * @return void
     */
    public function save(QuizQuestionAnswerRequestDTO $quizQuestionAnswerDTO, bool$resultAnswer): void
    {
        if ($quizQuestionAnswerDTO->getQuestionStep()->isFirstStep()) {
            $this->quizResultRepository->clean($quizQuestionAnswerDTO->getQuizId());
        }

        $this->quizResultRepository->save($quizQuestionAnswerDTO->getQuizId(), $quizQuestionAnswerDTO->getQuestionStep()->getStepId(), $resultAnswer);
    }

    /**
     * @param int $quizId
     * @return int
     */
    public function getTotalCorrectAnswers(int $quizId): int
    {
        $answers =  $this->quizResultRepository->getSavedCustomerAnswers($quizId);

        $correctAnswers = array_filter($answers, function ($answer) {
            return $answer;
        });

        return count($correctAnswers);
    }
}
