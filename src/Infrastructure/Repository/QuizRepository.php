<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository;

use App\Domain\Quiz\Entity\Quiz as QuizDomain;
use App\Domain\Quiz\Repository\Interfaces\RepositoryInterface;
use App\Infrastructure\DB\Quiz\Quiz;

/**
 * @extends DbRepository<QuizDomain>
 */
class QuizRepository extends DbRepository implements RepositoryInterface
{
    public function getQuizzes(): array
    {
        return $this->manager->findAll();
    }

    /**
     * @param int $id
     * @return QuizDomain|null
     */
    public function getQuizById(int $id): ?QuizDomain
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

    public function save(QuizDomain $quiz): void
    {
        $this->entityManager->persist($quiz);
        $this->entityManager->flush();
    }

    /**
     * @return class-string<QuizDomain>
     */
    protected function getFullEntityName(): string
    {
        return Quiz::class;
    }
}
