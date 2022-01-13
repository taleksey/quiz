<?php

declare(strict_types=1);

namespace App\Infractructure\Repository;

use App\Domain\Quiz\Entity\Answer;
use App\Presentation\DTO\QuizQuestionAnswerRequestDTO;
use Doctrine\ORM\NonUniqueResultException;

class QuizQuestionAnswersRepository extends DbRepository implements \App\Domain\Quiz\Repository\Interfaces\QuizQuestionAnswersRepository
{
    /**
     * @param QuizQuestionAnswerRequestDTO $quizQuestionAnswerDTO
     * @return Answer|null
     * @throws NonUniqueResultException
     */
    public function getAnswer(QuizQuestionAnswerRequestDTO $quizQuestionAnswerDTO): ?Answer
    {
        return $this->manager->createQueryBuilder('a')
            ->join('a.question', 'quest')
            ->join('quest.quiz', 'q')
            ->where('a.id = :answer AND quest.queue = :step AND q.id = :quizId')
            ->setParameters(
                [
                    'answer' => $quizQuestionAnswerDTO->getAnswerId(),
                    'step' => $quizQuestionAnswerDTO->getQuestionStep()->getStepId(),
                    'quizId' => $quizQuestionAnswerDTO->getQuizId()
                ]
            )
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @return string
     */
    protected function getFullEntityName(): string
    {
        return  'App\Domain\Quiz\Entity\Answer';
    }
}
