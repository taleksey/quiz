<?php

namespace App\Domain\Quiz\Repository;

use App\Domain\Quiz\Entity\Quiz;
use App\Infractructure\Repository\DbRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;

class QuizzesRepository extends DbRepository
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
        try {
            return $this->manager->find($id);
        } catch (OptimisticLockException|TransactionRequiredException|ORMException $e) {
        }
        return null;
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
     * @return string
     */
    protected function getFullEntityName(): string
    {
        return  'App\Domain\Quiz\Entity\Quiz';
    }
}