<?php

namespace App\Domain\Quiz\Repository;

use App\Domain\Quiz\Entity\Answer;
use App\Infractructure\Repository\DbRepository;
use App\Presentation\DTO\QuizQuestionAnswerDTO;
use Doctrine\ORM\NonUniqueResultException;

class QuizQuestionAnswersRepository extends DbRepository
{
    /**
     * @param QuizQuestionAnswerDTO $quizQuestionAnswerDTO
     * @return Answer|null
     * @throws NonUniqueResultException
     */
    public function getAnswer(QuizQuestionAnswerDTO $quizQuestionAnswerDTO): ?Answer
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
