<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

use App\Domain\Quiz\Entity\Question;
use App\Domain\Quiz\Repository\Interfaces\QuestionsRepositoryInterface;
use Throwable;

class QuizQuestionsRepository extends DbRepository implements QuestionsRepositoryInterface
{
    /**
     * @param int $quizId
     * @param int $step
     * @return Question|null
     */
    public function getQuestionByQuizIdAndQueue(int $quizId, int $step): ?Question
    {
        return $this->manager->findOneBy([
            'quiz' => $quizId,
            'queue' => $step
        ]);
    }

    /**
     * @param int $quizId
     * @return int
     */
    public function getTotalQuestions(int $quizId): int
    {
        try {
            return (int) $this->manager
                ->createQueryBuilder('q')
                ->select('count(q.id)')
                ->where('q.quiz = :quizId')
                ->setParameter('quizId', $quizId)
                ->getQuery()
                ->getSingleScalarResult();
        } catch (Throwable) {
        }

        return 0;
    }

    /**
     * @return string
     */
    protected function getFullEntityName(): string
    {
        return  'App\Domain\Quiz\Entity\Question';
    }
}
