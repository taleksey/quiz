<?php

namespace App\Domain\Quiz\Repository;

use App\Domain\Quiz\Entity\Quiz;
use App\Infractructure\Repository\DbRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\TransactionRequiredException;

class QuizzesRepository extends DbRepository
{
    /**
     * @var string
     */
    protected static string $entityName = 'Quiz';

    public function getQuizzes(): array
    {
        return $this->manager->findAll();
    }

    /**
     * @param $id
     * @return Quiz|null
     */
    public function getQuizById($id): ?Quiz
    {
        try {
            return $this->manager->find($id);
        } catch (OptimisticLockException|TransactionRequiredException|ORMException $e) {
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
