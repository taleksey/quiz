<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;

class QuizQuestionAnswersService
{
    /**
     * @param ResultRepositoryInterface $quizResultRepository
     */
    public function __construct(
        private ResultRepositoryInterface $quizResultRepository
    ) {
    }

    /**
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @param bool $resultAnswer
     * @return void
     */
    public function save(QuestionAnswerRequestDTO $quizQuestionAnswerDTO, bool$resultAnswer): void
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
