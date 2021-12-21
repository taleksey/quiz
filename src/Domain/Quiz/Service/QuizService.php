<?php

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Repository\QuizzesRepository;

class QuizService
{
    private QuizzesRepository $quizRepository;

    /**
     * @param QuizzesRepository $quizRepository
     */
    public function __construct(QuizzesRepository $quizRepository)
    {
        $this->quizRepository = $quizRepository;
    }

    /**
     * @return array
     */
    public function getAllQuizzes(): array
    {
        return $this->quizRepository->getQuizzes();
    }

    /**
     * @param int $id
     * @return Quiz|null
     */
    public function getQuizById(int $id): ?Quiz
    {
        return $this->quizRepository->getQuizById($id);
    }
}
