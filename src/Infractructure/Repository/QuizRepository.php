<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

use App\Domain\Quiz\Entity\Quiz;
use App\Domain\Quiz\Repository\Interfaces\RepositoryInterface;

/**
 * @extends DbRepository<Quiz>
 */
class QuizRepository extends DbRepository implements RepositoryInterface
{
    public function getQuizzes(): array
    {
        return $this->manager->findAll();
    }

    /**
     * @param int $id
     * @return Quiz|null
     */
    public function getQuizById(int $id): ?Quiz
    {
        return $this->manager->find($id);
    }

    /**
     * @return int
     */
    public function getTotalQuizzes(): int
    {
        return $this->manager->count([]);
    }

    /**
     * @param Quiz $quiz
     * @return void
     */
    public function save(Quiz $quiz): void
    {
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    /**
     * @return class-string<Quiz>
     */
    protected function getFullEntityName(): string
    {
        return  Quiz::class;
    }
}
