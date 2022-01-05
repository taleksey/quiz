<?php

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Entity\Answer;
use App\Domain\Quiz\Entity\Question;
use App\Domain\Quiz\Repository\QuizQuestionAnswersRepository;
use App\Domain\Quiz\Repository\QuizQuestionsRepository;
use App\Domain\Quiz\Repository\QuizResultRepository;
use App\Domain\Quiz\ValueObject\QuestionStep;
use App\Presentation\DTO\QuizQuestionAnswerDTO;

class QuizQuestionsService
{
    /**
     * @param QuizQuestionsRepository $questionRepository
     * @param QuizQuestionAnswersRepository $quizQuestionAnswersRepository
     * @param QuizResultRepository $quizResultRepository
     */
    public function __construct(
        private QuizQuestionsRepository $questionRepository,
        private QuizQuestionAnswersRepository $quizQuestionAnswersRepository,
        private QuizResultRepository $quizResultRepository
    ) {
    }

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @return Question|null
     */
    public function getQuestionByQuizIdAndQueue(QuizQuestionAnswerDTO $quizQuestionAnswerDTO): ?Question
    {
        return $this->questionRepository->getQuestionByQuizIdAndQueue(
            $quizQuestionAnswerDTO->getQuizId(),
            $quizQuestionAnswerDTO->getQuestionStep()->getStepId()
        );
    }

    /**
     * @param int $quizId
     * @return int
     */
    public function getTotalQuestions(int $quizId): int
    {
        return $this->questionRepository->getTotalQuestions($quizId);
    }

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @return Answer|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAnswer(QuizQuestionAnswerDTO $quizQuestionAnswerDTO): ?Answer
    {
        return $this->quizQuestionAnswersRepository->getAnswer($quizQuestionAnswerDTO);
    }

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @return bool
     */
    public function doesCustomerSelectCorrectStep(QuizQuestionAnswerDTO $quizQuestionAnswerDTO): bool
    {
        if ($quizQuestionAnswerDTO->getQuestionStep()->isFirstStep()) {
            return true;
        }

        $result = $this->quizResultRepository->getSavedCustomerAnswers($quizQuestionAnswerDTO->getQuizId());

        return ! empty($result) && array_key_exists($quizQuestionAnswerDTO->getQuestionStep()->getPrevStepId(), $result);
    }

    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @return int
     */
    public function getNextCorrectStep(QuizQuestionAnswerDTO $quizQuestionAnswerDTO): int
    {
        $result = $this->quizResultRepository->getSavedCustomerAnswers($quizQuestionAnswerDTO->getQuizId());

        if (empty($result)) {
            return $quizQuestionAnswerDTO->getQuestionStep()->firstStepId();
        }

        return (new QuestionStep(array_key_last($result)))->nextStepId();
    }
}
