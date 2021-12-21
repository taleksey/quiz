<?php

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\QuizResultRepository;
use App\Presentation\DTO\QuizQuestionAnswerDTO;

class QuizQuestionAnswersService
{
    private QuizResultRepository $quizResultRepository;

    /**
     * @param QuizResultRepository $quizResultRepository
     */
    public function __construct(QuizResultRepository $quizResultRepository)
    {
        $this->quizResultRepository = $quizResultRepository;
    }

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @param $resultAnswer
     * @return mixed
     */
    public function save(QuizQuestionAnswerDTO $quizQuestionAnswerDTO, $resultAnswer): mixed
    {
        if ($quizQuestionAnswerDTO->getQuestionStep()->isFirstStep()) {
            $this->quizResultRepository->clean($quizQuestionAnswerDTO->getQuizId());
        }

        return $this->quizResultRepository->save($quizQuestionAnswerDTO->getQuizId(), $quizQuestionAnswerDTO->getQuestionStep()->getStepId(), $resultAnswer);
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
