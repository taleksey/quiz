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
        } catch (OptimisticLockException|TransactionRequiredException|ORMException) {
        }
        return null;
    }

    /**
     * @return string
     */
    protected function getFullEntityName(): string
    {
        return  'App\Domain\Quiz\Entity\Quiz';
    }
}
