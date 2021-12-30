<?php

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\QuizResultRepository;
use App\Presentation\DTO\QuizQuestionAnswerDTO;

class QuizQuestionAnswersService
{
    /**
     * @param QuizResultRepository $quizResultRepository
     */
    public function __construct(
        private QuizResultRepository $quizResultRepository
    ) {}

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @param bool $resultAnswer
     * @return void
     */
    public function save(QuizQuestionAnswerDTO $quizQuestionAnswerDTO, bool $resultAnswer): void
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

        $correctAnswers = array_filter($answers, function ($answer){
            return $answer;
        });

        return count($correctAnswers);
    }
}
