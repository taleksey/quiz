<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Domain\Quiz\ValueObject\QuizResult;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use DateTime;

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
            $this->quizResultRepository->saveQuizStartDate($quizQuestionAnswerDTO->getQuizId(), new DateTime('NOW'));
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
        $correctAnswers = array_filter($answers, function ($answer, $key) {
            return is_int($key) && $answer;
        }, ARRAY_FILTER_USE_BOTH);

        return count($correctAnswers);
    }

    public function getQuizResult(int $quizId): QuizResult
    {
        return $this->quizResultRepository->getQuizResult($quizId);
    }

    public function getLastPassedQuestion(int $quizId): int
    {
        $quizResult = $this->getQuizResult($quizId);

        return $quizResult->getLastPosition();
    }

    public function cleanQuizAnswers(int $quizId): void
    {
        $this->quizResultRepository->clean($quizId);
    }
}
