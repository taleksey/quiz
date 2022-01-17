<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Entity\Answer;
use App\Domain\Quiz\Entity\Question;
use App\Domain\Quiz\Repository\Interfaces\QuestionAnswersRepositoryInterface;
use App\Domain\Quiz\Repository\Interfaces\QuestionsRepositoryInterface;
use App\Domain\Quiz\Repository\Interfaces\ResultRepositoryInterface;
use App\Domain\Quiz\ValueObject\QuestionStep;
use App\Presentation\DTO\Quiz\QuestionAnswerRequestDTO;
use Doctrine\ORM\NonUniqueResultException;

class QuizQuestionsService
{
    /**
     * @param QuestionsRepositoryInterface $questionRepository
     * @param QuestionAnswersRepositoryInterface $quizQuestionAnswersRepository
     * @param ResultRepositoryInterface $quizResultRepository
     */
    public function __construct(
        private QuestionsRepositoryInterface       $questionRepository,
        private QuestionAnswersRepositoryInterface $quizQuestionAnswersRepository,
        private ResultRepositoryInterface $quizResultRepository
    ) {
    }

    /**
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @return Question|null
     */
    public function getQuestionByQuizIdAndQueue(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Question
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
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @return Answer|null
     * @throws NonUniqueResultException
     */
    public function getAnswer(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Answer
    {
        return $this->quizQuestionAnswersRepository->getAnswer($quizQuestionAnswerDTO);
    }

    /**
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @return bool
     */
    public function doesCustomerSelectCorrectStep(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): bool
    {
        if ($quizQuestionAnswerDTO->getQuestionStep()->isFirstStep()) {
            return true;
        }

        $result = $this->quizResultRepository->getSavedCustomerAnswers($quizQuestionAnswerDTO->getQuizId());

        return ! empty($result) && array_key_exists($quizQuestionAnswerDTO->getQuestionStep()->getPrevStepId(), $result);
    }

    /**
     * @param QuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @return int
     */
    public function getNextCorrectStep(QuestionAnswerRequestDTO $quizQuestionAnswerDTO): int
    {
        $result = $this->quizResultRepository->getSavedCustomerAnswers($quizQuestionAnswerDTO->getQuizId());

        if (empty($result)) {
            return $quizQuestionAnswerDTO->getQuestionStep()->firstStepId();
        }

        return (new QuestionStep(array_key_last($result)))->nextStepId();
    }
}
