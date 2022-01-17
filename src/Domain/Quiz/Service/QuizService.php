<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Repository\Interfaces\RepositoryInterface;

class QuizService
{
    /**
     * @param RepositoryInterface $quizRepository
     */
    public function __construct(
        private RepositoryInterface $quizRepository
    ) {
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

    /**
     * @return int
     */
    public function getTotalQuizzes(): int
    {
        return $this->quizRepository->getTotalQuizzes();
    }

    /**
     * @param Quiz $quiz
     * @return void
     */
    public function save(Quiz $quiz): void
    {
        $this->quizRepository->save($quiz);
    }
}
