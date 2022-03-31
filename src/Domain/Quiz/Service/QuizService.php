<?php

declare(strict_types=1);

namespace App\Domain\Quiz\Service;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Repository\Interfaces\RepositoryInterface;
use App\Infrastructure\Manager\Quiz\QuizManager;

class QuizService
{
    public function __construct(
        private RepositoryInterface $quizRepository,
        private QuizManager $quizManager
    ) {
    }

    /**
     * @return array<int, Quiz>
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
        $this->quizManager->save($quiz);
    }
}
