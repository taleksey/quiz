<?php

declare(strict_types=1);

namespace App\Infrastructure\Repository\QuizSession;

use App\Domain\QuizSession\Repository\QuizRepositoryInterface;
use App\Infrastructure\DB\QuizSession\Quiz;
use App\Infrastructure\Repository\DbRepository;
use App\Domain\QuizSession\Entity\Quiz as QuizDomain;

/**
 * @extends DbRepository<QuizDomain>
 */
class QuizRepository extends DbRepository implements QuizRepositoryInterface
{
    public function getQuiz(QuizDomain $quiz): Quiz
    {
        return $this->manager->find($quiz->getId());
    }

    protected function getFullEntityName(): string
    {
        return Quiz::class;
    }
}
